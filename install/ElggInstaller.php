<?php

/**
 * Elgg Installer.
 * Controller for installing Elgg. Supports both web-based on CLI installation.
 *
 * This controller steps the user through the install process. The method for
 * each step handles both the GET and POST requests. There is no XSS/CSRF protection
 * on the POST processing since the installer is only run once by the administrator.
 *
 * The installation process can be resumed by hitting the first page. The installer
 * will try to figure out where to pick up again.
 *
 * All the logic for the installation process is in this class, but it depends on
 * the core libraries. To do this, we selectively load a subset of the core libraries
 * for the first few steps and then load the entire engine once the database and
 * site settings are configured. In addition, this controller does its own session
 * handling until the database is setup.
 *
 * There is an aborted attempt in the code at creating the data directory for
 * users as a subdirectory of Elgg's root. The idea was to protect this directory
 * through a .htaccess file. The problem is that a malicious user can upload a
 * .htaccess of his own that overrides the protection for his user directory. The
 * best solution is server level configuration that turns off AllowOverride for the
 * data directory. See ticket #3453 for discussion on this.
 *
 * @package    Elgg.Core
 * @subpackage Installer
 */

use phpcassa\ColumnFamily;
use phpcassa\ColumnSlice;
use phpcassa\Connection\ConnectionPool;
use phpcassa\SystemManager;
use phpcassa\Schema\StrategyClass;
use phpcassa\Index\IndexClause;
use phpcassa\Index\IndexExpression;
use phpcassa\Schema\DataType\LongType;
use phpcassa\UUID;

class ElggInstaller {

	protected $steps = array(
		'welcome',
		'requirements',
		'database',
		'settings',
		'admin',
		'complete',
		);

	protected $status = array(
		'config' => FALSE,
		'database' => FALSE,
		'settings' => FALSE,
		'admin' => FALSE,
	);

	protected $isAction = FALSE;

	protected $autoLogin = TRUE;

	/**
	 * Constructor bootstraps the Elgg engine
	 */
	public function __construct() {
		// load ElggRewriteTester as we depend on it
		require_once(dirname(__FILE__) . "/ElggRewriteTester.php");
		require_once(dirname(dirname(__FILE__)) . '/vendors/phpcassa/lib/autoload.php');	
		$this->isAction = $_SERVER['REQUEST_METHOD'] === 'POST';

		$this->bootstrapConfig();

		$this->bootstrapEngine();

		elgg_set_viewtype('installation');

		set_error_handler('_elgg_php_error_handler');
		set_exception_handler('_elgg_php_exception_handler');

		register_translations(dirname(__FILE__) . '/languages/', TRUE);
	}

	/**
	 * Dispatches a request to one of the step controllers
	 *
	 * @param string $step The installation step to run
	 *
	 * @return void
	 */
	public function run($step) {

		// check if this is a URL rewrite test coming in
		$this->processRewriteTest();

		if (!in_array($step, $this->getSteps())) {
			$msg = elgg_echo('InstallationException:UnknownStep', array($step));
			throw new InstallationException($msg);
		}

		$this->setInstallStatus();

		$this->checkInstallCompletion($step);

		// check if this is an install being resumed
		$this->resumeInstall($step);

		$this->finishBootstraping($step);

		$params = $this->getPostVariables();
		$this->$step($params);
	}

	/**
	 * Set the auto login flag
	 *
	 * @param bool $flag Auto login
	 *
	 * @return void
	 */
	public function setAutoLogin(bool $flag) {
		$this->autoLogin = $value;
	}

	/**
	 * A batch install of Elgg
	 *
	 * All required parameters must be passed in as an associative array. See
	 * $requiredParams for a list of them. This creates the necessary files,
	 * loads the database, configures the site settings, and creates the admin
	 * account. If it fails, an exception is thrown. It does not check any of
	 * the requirements as the multiple step web installer does.
	 *
	 * If the settings.php file exists, it will use that rather than the parameters
	 * passed to this function.
	 *
	 * @param array $params         Array of key value pairs
	 * @param bool  $createHtaccess Should .htaccess be created
	 *
	 * @return void
	 * @throws InstallationException
	 */
	public function batchInstall(array $params, $createHtaccess = FALSE) {
		global $CONFIG;

		restore_error_handler();
		restore_exception_handler();

		$defaults = array(
			'server' => '127.0.0.1',
			'path' => '',
			'language' => 'en',
			'siteaccess' => ACCESS_PUBLIC,
		);
		$params = array_merge($defaults, $params);

		$requiredParams = array(
			'server',
			'keyspace',
			'sitename',
			'wwwroot',
			'dataroot',
			'displayname',
			'email',
			'username',
			'password',
		);
		foreach ($requiredParams as $key) {
			if (empty($params[$key])) {
				$msg = elgg_echo('install:error:requiredfield', array($key));
				throw new InstallationException($msg);
			}
		}

		// password is passed in once
		$params['password1'] = $params['password2'] = $params['password'];

		if ($createHtaccess) {
			$rewriteTester = new ElggRewriteTester();
			if (!$rewriteTester->createHtaccess()) {
				throw new InstallationException(elgg_echo('install:error:htaccess'));
			}
		}

		$this->setInstallStatus();

		if (!$this->status['config']) {
			if (!$this->createSettingsFile($params)) {
				throw new InstallationException(elgg_echo('install:error:settings'));
			}
		}

		if (!$this->connectToDatabase()) {
			throw new InstallationException(elgg_echo('install:error:databasesettings'));
		}

		if (!$this->status['database']) {
			if (!$this->installDatabase()) {
				throw new InstallationException(elgg_echo('install:error:cannotloadtables'));
			}
		}

		// load remaining core libraries
		$this->finishBootstraping('settings');

		if (!$this->saveSiteSettings($params)) {
			throw new InstallationException(elgg_echo('install:error:savesitesettings'));
		}

		if (!$this->createAdminAccount($params)) {
			throw new InstallationException(elgg_echo('install:admin:cannot_create'));
		}
	}

	/**
	 * Renders the data passed by a controller
	 *
	 * @param string $step The current step
	 * @param array  $vars Array of vars to pass to the view
	 *
	 * @return void
	 */
	protected function render($step, $vars = array()) {

		$vars['next_step'] = $this->getNextStep($step);

		$title = elgg_echo("install:$step");
		$body = elgg_view("install/pages/$step", $vars);
		echo elgg_view_page(
				$title,
				$body,
				'default',
				array(
					'step' => $step,
					'steps' => $this->getSteps(),
					)
				);
		exit;
	}

	/**
	 * Step controllers
	 */

	/**
	 * Welcome controller
	 *
	 * @param array $vars Not used
	 *
	 * @return void
	 */
	protected function welcome($vars) {
		$this->render('welcome');
	}

	/**
	 * Requirements controller
	 *
	 * Checks version of php, libraries, permissions, and rewrite rules
	 *
	 * @param array $vars Vars
	 *
	 * @return void
	 */
	protected function requirements($vars) {

		$report = array();

		// check PHP parameters and libraries
		$this->checkPHP($report);

		// check URL rewriting
		$this->checkRewriteRules($report);

		// check for existence of settings file
		if ($this->checkSettingsFile($report) != TRUE) {
			// no file, so check permissions on engine directory
			$this->checkEngineDir($report);
		}

		// check the database later
		$report['database'] = array(array(
			'severity' => 'info',
			'message' => elgg_echo('install:check:database')
		));

		// any failures?
		$numFailures = $this->countNumConditions($report, 'failure');

		// any warnings
		$numWarnings = $this->countNumConditions($report, 'warning');


		$params = array(
			'report' => $report,
			'num_failures' => $numFailures,
			'num_warnings' => $numWarnings,
		);

		$this->render('requirements', $params);
	}

	/**
	 * Database set up controller
	 *
	 * Creates the settings.php file and creates the database tables
	 *
	 * @param array $submissionVars Submitted form variables
	 *
	 * @return void
	 */
	protected function database($submissionVars) {
		
		$formVars = array(
			'server' => array(
				'type' => 'text',
				'value' => '',
				'required' => TRUE,
				),
			'keyspace' => array(
				'type' => 'text',
				'value' => '',
				'required' => FALSE,
				)
		);

		if ($this->checkSettingsFile()) {
			// user manually created settings file so we fake out action test
			$this->isAction = TRUE;
		}

		if ($this->isAction) {
			do {
				// only create settings file if it doesn't exist
				if (!$this->checkSettingsFile()) {
					if (!$this->validateDatabaseVars($submissionVars, $formVars)) {
						// error so we break out of action and serve same page
						break;
					}
					
					if (!$this->createSettingsFile($submissionVars)) {
						break;
					}
				}
				
				// check db version and connect
				if (!$this->connectToDatabase()) {
					break;
				}

				if (!$this->installDatabase()) {
					break;
				}

				system_message(elgg_echo('install:success:database'));

				$this->continueToNextStep('database');
			} while (FALSE);  // PHP doesn't support breaking out of if statements
		}
		
		$formVars = $this->makeFormSticky($formVars, $submissionVars);

		$params = array('variables' => $formVars,);

		if ($this->checkSettingsFile()) {
			// settings file exists and we're here so failed to create database
			$params['failure'] = TRUE;
		}

		$this->render('database', $params);
	}

	/**
	 * Site settings controller
	 *
	 * Sets the site name, URL, data directory, etc.
	 *
	 * @param array $submissionVars Submitted vars
	 *
	 * @return void
	 */
	protected function settings($submissionVars) {
		global $CONFIG;
		db_init(); //load the db
		$formVars = array(
			'sitename' => array(
				'type' => 'text',
				'value' => 'My New Node',
				'required' => TRUE,
				),
			'siteemail' => array(
				'type' => 'text',
				'value' => '',
				'required' => FALSE,
				),
			'wwwroot' => array(
				'type' => 'text',
				'value' => elgg_get_site_url(),
				'required' => TRUE,
				),
			'path' => array(
				'type' => 'text',
				'value' => $CONFIG->default_path,
				'required' => TRUE,
				),
			'dataroot' => array(
				'type' => 'text',
				'value' => '',
				'required' => TRUE,
				),
			'siteaccess' => array(
				'type' => 'access',
				'value' =>  ACCESS_PUBLIC,
				'required' => TRUE,
				),
		);

		// if Apache, we give user option of having Elgg create data directory
		//if (ElggRewriteTester::guessWebServer() == 'apache') {
		//	$formVars['dataroot']['type'] = 'combo';
		//	$CONFIG->translations['en']['install:settings:help:dataroot'] =
		//			$CONFIG->translations['en']['install:settings:help:dataroot:apache'];
		//}

		if ($this->isAction) {
			do {
				//if (!$this->createDataDirectory($submissionVars, $formVars)) {
				//	break;
				//}

				if (!$this->validateSettingsVars($submissionVars, $formVars)) {
					break;
				}

				if (!$this->saveSiteSettings($submissionVars)) {
					break;
				}

				system_message(elgg_echo('install:success:settings'));

				$this->continueToNextStep('settings');

			} while (FALSE);  // PHP doesn't support breaking out of if statements
		}

		$formVars = $this->makeFormSticky($formVars, $submissionVars);

		$this->render('settings', array('variables' => $formVars));
	}

	/**
	 * Admin account controller
	 *
	 * Creates an admin user account
	 *
	 * @param array $submissionVars Submitted vars
	 *
	 * @return void
	 */
	protected function admin($submissionVars) {
		$formVars = array(
			'displayname' => array(
				'type' => 'text',
				'value' => '',
				'required' => TRUE,
				),
			'email' => array(
				'type' => 'text',
				'value' => '',
				'required' => TRUE,
				),
			'username' => array(
				'type' => 'text',
				'value' => '',
				'required' => TRUE,
				),
			'password1' => array(
				'type' => 'password',
				'value' => '',
				'required' => TRUE,
				),
			'password2' => array(
				'type' => 'password',
				'value' => '',
				'required' => TRUE,
				),
		);
		
		if ($this->isAction) {
			do {
				if (!$this->validateAdminVars($submissionVars, $formVars)) {
					break;
				}

				if (!$this->createAdminAccount($submissionVars, $this->autoLogin)) {
					break;
				}

				system_message(elgg_echo('install:success:admin'));

				$this->continueToNextStep('admin');

			} while (FALSE);  // PHP doesn't support breaking out of if statements
		}

		// bit of a hack to get the password help to show right number of characters
		global $CONFIG;
		$lang = get_current_language();
		$CONFIG->translations[$lang]['install:admin:help:password1'] =
				sprintf($CONFIG->translations[$lang]['install:admin:help:password1'],
				$CONFIG->min_password_length);

		$formVars = $this->makeFormSticky($formVars, $submissionVars);

		$this->render('admin', array('variables' => $formVars));
	}

	/**
	 * Controller for last step
	 *
	 * @return void
	 */
	protected function complete() {
		$params = array();
		if ($this->autoLogin) {
			$params['destination'] = 'admin/appearance/themeselection';
		} else {
			$params['destination'] = 'index.php';
		}

		$this->render('complete', $params);
	}

	/**
	 * Step management
	 */

	/**
	 * Get an array of steps
	 *
	 * @return array
	 */
	protected function getSteps() {
		return $this->steps;
	}

	/**
	 * Forwards the browser to the next step
	 *
	 * @param string $currentStep Current installation step
	 *
	 * @return void
	 */
	protected function continueToNextStep($currentStep) {
		$this->isAction = FALSE;
		forward($this->getNextStepUrl($currentStep));
	}

	/**
	 * Get the next step as a string
	 *
	 * @param string $currentStep Current installation step
	 *
	 * @return string
	 */
	protected function getNextStep($currentStep) {
		$index = 1 + array_search($currentStep, $this->steps);
		if (isset($this->steps[$index])) {
			return $this->steps[$index];
		} else {
			return null;
		}
	}

	/**
	 * Get the URL of the next step
	 *
	 * @param string $currentStep Current installation step
	 *
	 * @return string
	 */
	protected function getNextStepUrl($currentStep) {
		global $CONFIG;
		$nextStep = $this->getNextStep($currentStep);
		return elgg_get_site_url() . "install.php?step=$nextStep";
	}

	/**
	 * Check the different install steps for completion
	 *
	 * @return void
	 */
	protected function setInstallStatus() {
		global $CONFIG;

		if (!is_readable("{$CONFIG->default_path}engine/settings.php")) {
			return;
		}

		$this->loadSettingsFile();

		$this->status['config'] = TRUE;

		// must be able to connect to database to jump install steps
		$dbSettingsPass = $this->checkDatabaseSettings(
				$CONFIG->server,
				$CONFIG->keyspace
				);
		if ($dbSettingsPass == FALSE) {
			return;
		}

		if (!include_once("{$CONFIG->default_path}engine/lib/database.php")) {
			$msg = elgg_echo('InstallationException:MissingLibrary', array('database.php'));
			throw new InstallationException($msg);
		}

		// check that the config table has been created
		$query = "show tables";
		$result = get_data($query);
		if ($result) {
			foreach ($result as $table) {
				$table = (array) $table;
				if (in_array("{$CONFIG->dbprefix}config", $table)) {
					$this->status['database'] = TRUE;
				}
			}
			if ($this->status['database'] == FALSE) {
				return;
			}
		} else {
			// no tables
			return;
		}

		// check that the config table has entries
		$query = "SELECT COUNT(*) AS total FROM {$CONFIG->dbprefix}config";
		$result = get_data($query);
		if ($result && $result[0]->total > 0) {
			$this->status['settings'] = TRUE;
		} else {
			return;
		}

		// check that the users entity table has an entry
		$query = "SELECT COUNT(*) AS total FROM {$CONFIG->dbprefix}users_entity";
		$result = get_data($query);
		if ($result && $result[0]->total > 0) {
			$this->status['admin'] = TRUE;
		} else {
			return;
		}
                
	}

	/**
	 * Security check to ensure the installer cannot be run after installation
	 * has finished. If this is detected, the viewer is sent to the front page.
	 *
	 * @param string $step Installation step to check against
	 *
	 * @return void
	 */
	protected function checkInstallCompletion($step) {
		if ($step != 'complete') {
			if (!in_array(FALSE, $this->status)) {
				// install complete but someone is trying to view an install page
				forward();
			}
		}
	}

	/**
	 * Check if this is a case of a install being resumed and figure
	 * out where to continue from. Returns the best guess on the step.
	 *
	 * @param string $step Installation step to resume from
	 *
	 * @return string
	 */
	protected function resumeInstall($step) {
		global $CONFIG;

		// only do a resume from the first step
		if ($step !== 'welcome') {
			return;
		}

		if ($this->status['database'] == FALSE) {
			return;
		}

		if ($this->status['settings'] == FALSE) {
			forward("install.php?step=settings");
		}

		if ($this->status['admin'] == FALSE) {
			forward("install.php?step=admin");
		}
                
		// everything appears to be set up
		forward("install.php?step=complete");
	}

	/**
	 * Bootstraping
	 */

	/**
	 * Load the essential libraries of the engine
	 *
	 * @return void
	 */
	protected function bootstrapEngine() {
		global $CONFIG;

		$lib_dir = "{$CONFIG->default_path}engine/lib/";

		// bootstrapping with required files in a required order
		$required_files = array(
			'elgglib.php', 'views.php', 'access.php', 'system_log.php', 'export.php', 
			'configuration.php', 'sessions.php', 'languages.php', 'pageowner.php',
			'input.php', 'cache.php', 'output.php',
		);

		foreach ($required_files as $file) {
			$path = $lib_dir . $file;
			if (!include($path)) {
				echo "Could not load file '$path'. "
					. 'Please check your Elgg installation for all required files.';
				exit;
			}
		}
	}

	/**
	 * Load remaining engine libraries and complete bootstraping (see start.php)
	 *
	 * @param string $step Which step to boot strap for. Required because
	 *                     boot strapping is different until the DB is populated.
	 *
	 * @return void
	 */
	protected function finishBootstraping($step) {

		$dbIndex = array_search('database', $this->getSteps());
		$settingsIndex = array_search('settings', $this->getSteps());
		$adminIndex = array_search('admin', $this->getSteps());
		$completeIndex = array_search('complete', $this->getSteps());
		$stepIndex = array_search($step, $this->getSteps());

		// To log in the user, we need to use the Elgg core session handling.
		// Otherwise, use default php session handling
		$useElggSession = ($stepIndex == $adminIndex && $this->isAction) ||
				$stepIndex == $completeIndex;
		if (!$useElggSession) {
			session_name('Elgg_install');
			session_start();
			elgg_unregister_event_handler('boot', 'system', 'session_init');
		}

		if ($stepIndex > $dbIndex) {
			// once the database has been created, load rest of engine
			global $CONFIG;
			$lib_dir = "{$CONFIG->default_path}engine/lib/";

			$this->loadSettingsFile();

			$lib_files = array(
				// these want to be loaded first apparently?
				'database.php', 'actions.php',

				'admin.php', 'annotations.php',
				'calendar.php', 'cron.php', 'entities.php',
				'extender.php', 'filestore.php', 'group.php',
				'location.php', 'mb_wrapper.php',
				'memcache.php', 'metadata.php', 'metastrings.php',
				'navigation.php', 'notification.php',
				'objects.php', 'opendd.php', 'pagehandler.php',
				'pam.php', 'plugins.php',
				'private_settings.php', 'relationships.php', 'river.php',
				'sites.php', 'statistics.php', 'tags.php', 'user_settings.php',
				'users.php', 'upgrade.php', 'web_services.php',
				'widgets.php', 'xml.php', 'xml-rpc.php',
				'deprecated-1.7.php', 'deprecated-1.8.php',
			);

			foreach ($lib_files as $file) {
				$path = $lib_dir . $file;
				if (!include_once($path)) {
					$msg = elgg_echo('InstallationException:MissingLibrary', array($file));
					throw new InstallationException($msg);
				}
			}

			//setup_db_connections();
			register_translations(dirname(dirname(__FILE__)) . "/languages/");

                        db_init(); // DB needs to be initialised before session start.
			if ($stepIndex > $settingsIndex) {
				$CONFIG->site_guid = (int) datalist_get('default_site');
				$CONFIG->site_id = $CONFIG->site_guid;
				$CONFIG->site = get_entity($CONFIG->site_guid, 'site');
				$CONFIG->dataroot = datalist_get('dataroot');
				_elgg_session_boot(NULL, NULL, NULL);
			}

			elgg_trigger_event('init', 'system');
		}
	}

	/**
	 * Set up configuration variables
	 *
	 * @return void
	 */
	protected function bootstrapConfig() {
		global $CONFIG;
		if (!isset($CONFIG)) {
			$CONFIG = new stdClass;
		}

		$CONFIG->wwwroot = $this->getBaseUrl();
		$CONFIG->url = $CONFIG->wwwroot;
		$CONFIG->default_path = dirname(dirname(__FILE__)) . '/';
		$CONFIG->viewpath =	$CONFIG->default_path . 'views/';
		$CONFIG->pluginspath = $CONFIG->default_path . 'mod/';
		$CONFIG->context = array();
		$CONFIG->entity_types = array('group', 'object', 'site', 'user');
	}

	/**
	 * Get the best guess at the base URL
	 *
	 * @note Cannot use current_page_url() because it depends on $CONFIG->wwwroot
	 * @todo Should this be a core function?
	 *
	 * @return string
	 */
	protected function getBaseUrl() {
		$protocol = 'http';
		if (!empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {
			$protocol = 'https';
		}
		$port = ':' . $_SERVER["SERVER_PORT"];
		if ($port == ':80' || $port == ':443') {
			$port = '';
		}
		$uri = $_SERVER['REQUEST_URI'];
		$cutoff = strpos($uri, 'install.php');
		$uri = substr($uri, 0, $cutoff);

		$url = "$protocol://{$_SERVER['SERVER_NAME']}$port{$uri}";
		return $url;
	}

	/**
	 * Load settings.php
	 *
	 * @return void
	 * @throws InstallationException
	 */
	protected function loadSettingsFile() {
		global $CONFIG;
		
		if (!include_once("{$CONFIG->default_path}engine/settings.php")) {
			$msg = elgg_echo('InstallationException:CannotLoadSettings');
			throw new InstallationException($msg);
		}
	}

	/**
	 * Action handling methods
	 */

	/**
	 * Return an associative array of post variables
	 * (could be selective based on expected variables)
	 *
	 * Does not filter as person installing the site should not be attempting
	 * XSS attacks. If filtering is added, it should not be done for passwords.
	 *
	 * @return array
	 */
	protected function getPostVariables() {
		$vars = array();
		foreach ($_POST as $k => $v) {
			$vars[$k] = $v;
		}
		//do files too...
		foreach($_FILES as $k => $v){
			$vars[$k] = $v;
		}
		return $vars;
	}

	/**
	 * If form is reshown, remember previously submitted variables
	 *
	 * @param array $formVars       Vars int he form
	 * @param array $submissionVars Submitted vars
	 *
	 * @return array
	 */
	protected function makeFormSticky($formVars, $submissionVars) {
		foreach ($submissionVars as $field => $value) {
			$formVars[$field]['value'] = $value;
		}
		return $formVars;
	}

	/**
	 * Requirement checks support methods
	 */

	/**
	 * Check that the engine dir is writable
	 *
	 * @param array &$report The requirements report object
	 *
	 * @return bool
	 */
	protected function checkEngineDir(&$report) {
		global $CONFIG;

		$writable = is_writable("{$CONFIG->default_path}engine");
		if (!$writable) {
			$report['settings'] = array(
				array(
					'severity' => 'failure',
					'message' => elgg_echo('install:check:enginedir'),
				)
			);
			return FALSE;
		}

		return TRUE;
	}

	/**
	 * Check that the settings file exists
	 *
	 * @param array &$report The requirements report array
	 *
	 * @return bool
	 */
	protected function checkSettingsFile(&$report = array()) {
		global $CONFIG;

		if (!file_exists("{$CONFIG->default_path}engine/settings.php")) {
			return FALSE;
		}

		if (!is_readable("{$CONFIG->default_path}engine/settings.php")) {
			$report['settings'] = array(
				array(
					'severity' => 'failure',
					'message' => elgg_echo('install:check:readsettings'),
				)
			);
		}

		return TRUE;
	}

	/**
	 * Check version of PHP, extensions, and variables
	 *
	 * @param array &$report The requirements report array
	 *
	 * @return void
	 */
	protected function checkPHP(&$report) {
		$phpReport = array();

		$elgg_php_version = '5.2.0';
		if (version_compare(PHP_VERSION, $elgg_php_version, '<')) {
			$phpReport[] = array(
				'severity' => 'failure',
				'message' => elgg_echo('install:check:php:version', array($elgg_php_version, PHP_VERSION))
			);
		}

		$this->checkPhpExtensions($phpReport);

		$this->checkPhpDirectives($phpReport);

		if (count($phpReport) == 0) {
			$phpReport[] = array(
				'severity' => 'pass',
				'message' => elgg_echo('install:check:php:success')
			);
		}

		$report['php'] = $phpReport;
	}

	/**
	 * Check the server's PHP extensions
	 *
	 * @param array &$phpReport The PHP requirements report array
	 *
	 * @return void
	 */
	protected function checkPhpExtensions(&$phpReport) {
		$extensions = get_loaded_extensions();
		$requiredExtensions = array(
			'mysql',
			'json',
			'xml',
			'gd',
		);
		foreach ($requiredExtensions as $extension) {
			if (!in_array($extension, $extensions)) {
				$phpReport[] = array(
					'severity' => 'failure',
					'message' => elgg_echo('install:check:php:extension', array($extension))
				);
			}
		}

		$recommendedExtensions = array(
			'mbstring',
		);
		foreach ($recommendedExtensions as $extension) {
			if (!in_array($extension, $extensions)) {
				$phpReport[] = array(
					'severity' => 'warning',
					'message' => elgg_echo('install:check:php:extension:recommend', array($extension))
				);
			}
		}
	}

	/**
	 * Check PHP parameters
	 *
	 * @param array &$phpReport The PHP requirements report array
	 *
	 * @return void
	 */
	protected function checkPhpDirectives(&$phpReport) {
		if (ini_get('open_basedir')) {
			$phpReport[] = array(
				'severity' => 'warning',
				'message' => elgg_echo("install:check:php:open_basedir")
			);
		}

		if (ini_get('safe_mode')) {
			$phpReport[] = array(
				'severity' => 'warning',
				'message' => elgg_echo("install:check:php:safe_mode")
			);
		}

		if (ini_get('arg_separator.output') !== '&') {
			$separator = htmlspecialchars(ini_get('arg_separator.output'));
			$msg = elgg_echo("install:check:php:arg_separator", array($separator));
			$phpReport[] = array(
				'severity' => 'failure',
				'message' => $msg,
			);
		}

		if (ini_get('register_globals')) {
			$phpReport[] = array(
				'severity' => 'failure',
				'message' => elgg_echo("install:check:php:register_globals")
			);
		}

		if (ini_get('session.auto_start')) {
			$phpReport[] = array(
				'severity' => 'failure',
				'message' => elgg_echo("install:check:php:session.auto_start")
			);
		}
	}

	/**
	 * Confirm that the rewrite rules are firing
	 *
	 * @param array &$report The requirements report array
	 *
	 * @return void
	 */
	protected function checkRewriteRules(&$report) {
		global $CONFIG;

		$tester = new ElggRewriteTester();
		$url = elgg_get_site_url() . "rewrite.php";
		$report['rewrite'] = array($tester->run($url, $CONFIG->default_path));
	}

	/**
	 * Check if the request is coming from the URL rewrite test on the
	 * requirements page.
	 *
	 * @return void
	 */
	protected function processRewriteTest() {
		if (strpos($_SERVER['REQUEST_URI'], 'rewrite.php') !== FALSE) {
			echo 'success';
			exit;
		}
	}

	/**
	 * Count the number of failures in the requirements report
	 *
	 * @param array  $report    The requirements report array
	 * @param string $condition 'failure' or 'warning'
	 *
	 * @return int
	 */
	protected function countNumConditions($report, $condition) {
		$count = 0;
		foreach ($report as $category => $checks) {
			foreach ($checks as $check) {
				if ($check['severity'] === $condition) {
					$count++;
				}
			}
		}

		return $count;
	}


	/**
	 * Database support methods
	 */

	/**
	 * Validate the variables for the database step
	 *
	 * @param array $submissionVars Submitted vars
	 * @param array $formVars       Vars in the form
	 *
	 * @return bool
	 */
	protected function validateDatabaseVars($submissionVars, $formVars) {

		foreach ($formVars as $field => $info) {
			if ($info['required'] == TRUE && !$submissionVars[$field]) {
				$name = elgg_echo("install:database:label:$field");
				register_error("$name is required");
				return FALSE;
			}
		}
	
		return $this->checkDatabaseSettings($submissionVars['server'], $submissionVars['keyspace']);
	}

	/**
	 * Confirm the settings for the database
	 *
	 * @param string $server	Cassandra IP Address
	 *
	 * @return bool
	 */
	protected function checkDatabaseSettings($server, $keyspace) {

		try{
			$sys = new SystemManager($server);
			$attrs = array(	  "strategy_options" => array("replication_factor" => "2"));	
                        $sys->create_keyspace($keyspace, $attrs);
			return true;
		} catch (Exception $e){
			register_error(elgg_echo('install:error:databasesettings'));
			register_error($e->why);
			return false;
		}
	}

	/**
	 * Writes the settings file to the engine directory
	 *
	 * @param array $params Array of inputted params from the user
	 *
	 * @return bool
	 */
	protected function createSettingsFile($params) {
		global $CONFIG;

		//Check if we already have settings.php so we can ammend
		$settingsFilename = "{$CONFIG->default_path}engine/settings.php";
		if(!file_exists($settingsFilename)){

			$templateFile = "{$CONFIG->default_path}engine/settings.example.php";
			$template = file_get_contents($templateFile);
			if (!$template) {
				register_error(elgg_echo('install:error:readsettingsphp'));
				return FALSE;
			}

		} else {
			$template = file_get_contents($settingsFilename);
		}

		foreach ($params as $k => $v) {
			$template = str_replace("{{" . $k . "}}", $v, $template);
		}

		$settingsFilename = "{$CONFIG->default_path}engine/settings.php";
		$result = file_put_contents($settingsFilename, $template);
		if (!$result) {
			register_error(elgg_echo('install:error:writesettingphp'));
			return FALSE;
		}

		return TRUE;
	}

	/**
	 * Bootstrap database connection before entire engine is available
	 *
	 * @return bool
	 */
	protected function connectToDatabase() {
		global $CONFIG;

		if (!include_once("{$CONFIG->default_path}engine/settings.php")) {
			register_error(elgg_echo('InstallationException:CannotLoadSettings'));
			return FALSE;
		}

		if (!include_once("{$CONFIG->default_path}engine/lib/database.php")) {
			$msg = elgg_echo('InstallationException:MissingLibrary', array('database.php'));
			register_error($msg);
			return FALSE;
		}

		try  {
			$this->cassandra = new SystemManager($CONFIG->cassandra->servers[0]);	
		} catch (Exception $e) {
			register_error($e->getMessage());
			return FALSE;
		}

		return TRUE;
	}

	/**
	 * Create the database tables
	 *
	 * @return bool
	 */
	protected function installDatabase() {
		global $CONFIG;

		$sys = $this->cassandra;

		try{
			//the required column families
			$cfs = array(	'site' => array('site_id' => 'UTF8Type'),
					'plugin' => array('active' => 'IntegerType'),
					'config' => array(),
					'entities_by_time' => array(),
					'object' => array('owner_guid'=>'UTF8Type', 'access_id'=>'IntegerType', 'subtype'=>'UTF8Type', 'container_guid'=>'UTF8Type'),
					'user' => array(),
					'user_index_to_guid' => array(),
					'group' => array('container_guid' => 'UTF8Type'	),
					'widget' => array('owner_guid'=>'UTF8Type', 'access_id'=>'IntegerType' ),

					'notification' => array('to_guid' => 'UTF8Type'	),
					'session' => array(),

					'annotation' => array(),	

					'friends' => array(),
					'friendsof' => array(),
					'newsfeed' => array(),
					'timeline' => array(),
                                        
                    'token' => array('owner_guid'=>'UTF8Type', 'expires' =>'IntegerType' )
				);
			
			//create the cfs
			foreach($cfs as $cf => $indexes){

				$attr = array(	"comparator_type" => "UTF8Type",
						"default_validation_class" => "UTF8Type",
						"key_validation_class" => "UTF8Type"
						);

				$sys->create_column_family($CONFIG->cassandra->keyspace, $cf, $attr);

				foreach($indexes as $index => $data_type){

					$sys->create_index($CONFIG->cassandra->keyspace, $cf, $index, $data_type);
			
				}
			}	

		} catch (Exception $e){
			register_error($e->why);
			return false;
		}
	
		return true;
	}

	/**
	 * Site settings support methods
	 */

	/**
	 * Create the data directory if requested
	 *
	 * @param array $submissionVars Submitted vars
	 * @param array $formVars       Variables in the form
	 * @return bool
	 */
	protected function createDataDirectory(&$submissionVars, $formVars) {
		// did the user have option of Elgg creating the data directory
		if ($formVars['dataroot']['type'] != 'combo') {
			return TRUE;
		}

		// did the user select the option
		if ($submissionVars['dataroot'] != 'dataroot-checkbox') {
			return TRUE;
		}

		$dir = sanitise_filepath($submissionVars['path']) . 'data';
		if (file_exists($dir) || mkdir($dir, 0700)) {
			$submissionVars['dataroot'] = $dir;
			if (!file_exists("$dir/.htaccess")) {
				$htaccess = "Order Deny,Allow\nDeny from All\n";
				if (!file_put_contents("$dir/.htaccess", $htaccess)) {
					return FALSE;
				}
			}
			return TRUE;
		}

		return FALSE;
	}

	/**
	 * Validate the site settings form variables
	 *
	 * @param array $submissionVars Submitted vars
	 * @param array $formVars       Vars in the form
	 *
	 * @return bool
	 */
	protected function validateSettingsVars($submissionVars, $formVars) {
		global $CONFIG;

		foreach ($formVars as $field => $info) {
			$submissionVars[$field] = trim($submissionVars[$field]);
			if ($info['required'] == TRUE && $submissionVars[$field] === '') {
				$name = elgg_echo("install:settings:label:$field");
				register_error(elgg_echo('install:error:requiredfield', array($name)));
				return FALSE;
			}
		}

		// check that data root is absolute path
		if (stripos(PHP_OS, 'win') === 0) {
			if (strpos($submissionVars['dataroot'], ':') !== 1) {
				$msg = elgg_echo('install:error:relative_path', array($submissionVars['dataroot']));
				register_error($msg);
				return FALSE;
			}
		} else {
			if (strpos($submissionVars['dataroot'], '/') !== 0) {
				$msg = elgg_echo('install:error:relative_path', array($submissionVars['dataroot']));
				register_error($msg);
				return FALSE;
			}
		}

		// check that data root exists
		if (!file_exists($submissionVars['dataroot'])) {
			$msg = elgg_echo('install:error:datadirectoryexists', array($submissionVars['dataroot']));
			register_error($msg);
			return FALSE;
		}

		// check that data root is writable
		if (!is_writable($submissionVars['dataroot'])) {
			$msg = elgg_echo('install:error:writedatadirectory', array($submissionVars['dataroot']));
			register_error($msg);
			return FALSE;
		}

		if (!isset($CONFIG->data_dir_override) || !$CONFIG->data_dir_override) {
			// check that data root is not subdirectory of Elgg root
			if (stripos($submissionVars['dataroot'], $submissionVars['path']) === 0) {
				$msg = elgg_echo('install:error:locationdatadirectory', array($submissionVars['dataroot']));
				register_error($msg);
				return FALSE;
			}
		}

		// check that email address is email address
		if ($submissionVars['siteemail'] && !is_email_address($submissionVars['siteemail'])) {
			$msg = elgg_echo('install:error:emailaddress', array($submissionVars['siteemail']));
			register_error($msg);
			return FALSE;
		}

		// @todo check that url is a url
		// @note filter_var cannot be used because it doesn't work on international urls

		return TRUE;
	}

	/**
	 * Initialize the site including site entity, plugins, and configuration
	 *
	 * @param array $submissionVars Submitted vars
	 *
	 * @return bool
	 */
	protected function saveSiteSettings($submissionVars) {
		global $CONFIG;

		// ensure that file path, data path, and www root end in /
		$submissionVars['path'] = sanitise_filepath($submissionVars['path']);
		$submissionVars['dataroot'] = sanitise_filepath($submissionVars['dataroot']);
		$submissionVars['wwwroot'] = sanitise_filepath($submissionVars['wwwroot']);

		$site = new ElggSite();
		$site->name = $submissionVars['sitename'];
		$site->url = $submissionVars['wwwroot'];
		$site->access_id = ACCESS_PUBLIC;
		$site->email = $submissionVars['siteemail'];
		$guid = $site->save();

		if (!$guid) {
			register_error(elgg_echo('install:error:createsite'));
			return FALSE;
		}

		// bootstrap site info
		$CONFIG->site_guid = $guid;
		$CONFIG->site = $site;

		//settings previsouly in datalist will now reside in setting.php

		datalist_set('simplecache_enabled', 1);
		datalist_set('system_cache_enabled', 1);

		$settings = array( 	'installed' => time(),
					'path' => $submissionVars['path'],
					'dataroot' => $submissionVars['dataroot'],
					'default_site' => $site->getGUID(),
					'site_secret' => md5(rand() . microtime())
				);

		$this->createSettingsFile($settings);

		set_config('view', 'default', $guid);
		set_config('language', 'en', $guid);
		set_config('default_access', $submissionVars['siteaccess'], $guid);
		set_config('allow_registration', TRUE, $guid);
		set_config('walled_garden', FALSE, $guid);
		set_config('allow_user_default_access', '', $guid);
		set_config('simplecache_enabled', 0, $guid);
		set_config('system_cache_enabled', 0, $guid);

		$this->enablePlugins();

		return TRUE;
	}

	/**
	 * Enable a set of default plugins
	 *
	 * @return void
	 */
	protected function enablePlugins() {
		/*elgg_generate_plugin_entities();
		$plugins = elgg_get_plugins('any');
		foreach ($plugins as $plugin) {
			if ($plugin->getManifest()) {
				if ($plugin->getManifest()->getActivateOnInstall()) {
					$plugin->activate();
				}
			}
		}*/
		elgg_generate_plugin_entities();
		$installed_plugins = elgg_get_plugins('any');

		/**
		 * Default plugins to install, ordering included
		 */
		$defaults = array(	'uservalidationbyemail', 
							'htmlawed',
							'logbrowser',
							'logrotate',
							'oauth2', 
							'oauth_api', 
							'channel', 
							'groups',
							'wall',
							'tidypics', 
							'analytics',
							'archive', 
							'embed',
							'embed_extender',
							'blog',
							'thumbs',
							'minds_search', 
							'minds_comments',
							'minds_social',
							'minds_webservices',
                                                        'minds_themeconfig',
                                                        'minds_theme_selector',
                                                        //'anypage',
							'persona',
							'notifications',
							'minds_connect',
							'bootcamp',
							'mobile',
							'minds'
						);
		foreach($defaults as $plugin_id){
			$plugin = elgg_get_plugin_from_id($plugin_id);
			if($plugin instanceof ElggPlugin){
				$plugin->setPriority('last');
				$plugin->activate();
			}
		}
	}

	/**
	 * Admin account support methods
	 */

	/**
	 * Validate account form variables
	 *
	 * @param array $submissionVars Submitted vars
	 * @param array $formVars       Form vars
	 *
	 * @return bool
	 */
	protected function validateAdminVars($submissionVars, $formVars) {

		foreach ($formVars as $field => $info) {
			if ($info['required'] == TRUE && !$submissionVars[$field]) {
				$name = elgg_echo("install:admin:label:$field");
				register_error(elgg_echo('install:error:requiredfield', array($name)));
				return FALSE;
			}
		}

		if ($submissionVars['password1'] !== $submissionVars['password2']) {
			register_error(elgg_echo('install:admin:password:mismatch'));
			return FALSE;
		}

		if (trim($submissionVars['password1']) == "") {
			register_error(elgg_echo('install:admin:password:empty'));
			return FALSE;
		}

		$minLength = get_config('min_password_length');
		if (strlen($submissionVars['password1']) < $minLength) {
			register_error(elgg_echo('install:admin:password:tooshort'));
			return FALSE;
		}

		// check that email address is email address
		if ($submissionVars['email'] && !is_email_address($submissionVars['email'])) {
			$msg = elgg_echo('install:error:emailaddress', array($submissionVars['email']));
			register_error($msg);
			return FALSE;
		}

		return TRUE;
	}

	/**
	 * Create a user account for the admin
	 *
	 * @param array $submissionVars Submitted vars
	 * @param bool  $login          Login in the admin user?
	 *
	 * @return bool
	 */
	protected function createAdminAccount($submissionVars, $login = FALSE) {
		global $CONFIG;
		db_init();
		try {
			$guid = register_user(
					$submissionVars['username'],
					$submissionVars['password1'],
					$submissionVars['displayname'],
					$submissionVars['email']
					);
		} catch (Exception $e) {
			register_error($e->getMessage());
			return false;
		}

		if (!$guid) {
			register_error(elgg_echo('install:admin:cannot_create'));
			return false;
		}

		$user = get_entity($guid,'user');
		if (!$user) {
			register_error(elgg_echo('install:error:loadadmin'));
			return false;
		}

		elgg_set_ignore_access(TRUE);
		if ($user->makeAdmin() == FALSE) {
			register_error(elgg_echo('install:error:adminaccess'));
		} else {
			datalist_set('admin_registered', 1);
		}
		elgg_set_ignore_access(false);

		$user->validated = true;
		$user->validated_method = 'admin_user';
		$user->save();

		if ($login) {
			if (login($user) == FALSE) {
				register_error(elgg_echo('install:error:adminlogin'));
			}
		}

		return TRUE;
	}
        
}

