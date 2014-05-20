<?php

/**
 * Installs Minds.
 * 
 * @todo Make this a lot cleaner and SMALLER
 * 
 */

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
		
		define('__MINDS_INSTALLING__', true);
		
		require_once(__MINDS_ROOT__. '/engine/autoload.php');
		$minds = new minds\core\minds();
		$minds->loadLegacy();
		
		$this->isAction = $_SERVER['REQUEST_METHOD'] === 'POST';

		$this->bootstrapConfig();

		elgg_set_viewtype('installation');

		set_error_handler('_elgg_php_error_handler');
		set_exception_handler('_elgg_php_exception_handler');

		register_translations(dirname(__FILE__) . '/languages/', TRUE);
	}
	
	/**
	 * Bootstraping
	 */
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

		if ($stepIndex > $dbIndex) {
			// once the database has been created, load rest of engine
			global $CONFIG;
			$minds = new minds\core\minds();
			$minds->loadConfigs();
			//$minds->start();//we can start the engine now

			if ($stepIndex > $settingsIndex) {
				$CONFIG->site_guid = (int) datalist_get('default_site');
				$CONFIG->site_id = $CONFIG->site_guid;
				$CONFIG->site = get_entity($CONFIG->site_guid, 'site');
				$CONFIG->dataroot = datalist_get('dataroot');
			}
			
		}
	}


	/**
	 * Dispatches a request to one of the step controllers
	 *
	 * @param string $step The installation step to run
	 *
	 * @return void
	 */
	public function run($step) {

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
			/*$rewriteTester = new ElggRewriteTester();
			if (!$rewriteTester->createHtaccess()) {
				throw new InstallationException(elgg_echo('install:error:htaccess'));
			}*/
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

		if (!$this->createAdminAccount($params, true)) {
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
	//	$this->render('welcome');
		$this->continueToNextStep('welcome');
		
	//	forward('?step=settings');
	}

	/**
	 * Requirements controller
	 *
	 * Checks version of php, libraries, permissions
	 *
	 * @param array $vars Vars
	 *
	 * @return void
	 */
	protected function requirements($vars) {

		$report = array();

		// check PHP parameters and libraries
		$this->checkPHP($report);


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

				if (!$this->createAdminAccount($submissionVars, true)) {
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
			$params['destination'] = 'register/orientation';
		} else {
			$params['destination'] = 'index.php';
		}
		
		$this->disableInstallation();

		forward( 'register/orientation');
	//	$this->render('complete', $params);
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
		global $CONFIG; 
		if(isset($CONFIG->fully_installed) && $CONFIG->fully_installed){
			exit;
		}
		if ($step != 'complete') {
			if (!in_array(FALSE, $this->status)) {
				// install complete but someone is trying to view an install page
				forward();
			}
		} 
	}
	
	protected function disableInstallation(){
		$file_data = file_get_contents(__MINDS_ROOT__.'/engine/settings.php');
		$file_data .= "\n\n \$CONFIG->fully_installed = true; \n\n";
		file_put_contents(__MINDS_ROOT__.'/engine/settings.php', $file_data);
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
			$db = new minds\core\data\call(NULL, $keyspace, array($server));
			$attrs = array(	  "strategy_options" => array("replication_factor" => "2"));	
			$db->createKeyspace($attrs);
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
			$db = new minds\core\data\call(NULL, NULL,$CONFIG->cassandra->servers);	
			$db->keyspaceExists();
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

		try{
			$db = new minds\core\data\call(null, $CONFIG->cassandra->keyspace, $CONFIG->cassandra->servers);
			$db->installSchema();
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
		/**
		 * Default plugins to install, ordering included
		 */
		$defaults = array(
			'htmlawed',
			'logbrowser',
			'logrotate',
			'oauth2', 
			'oauth_api', 
			'channel', 
			'groups',
			'wall',
			'tidypics', 
			'archive', 
			'embed',
			'embed_extender',
			'blog',
			'thumbs',
			'minds_search', 
			'minds_comments',
			'minds_social',
			'minds_webservices',
			'minds_wordpress',
			'persona',
			'notifications',
			'orientation',
			'mobile',
			'minds'
		);
		foreach($defaults as $plugin_id){
			try{
				$plugin = new ElggPlugin($plugin_id);
				$plugin->save();
			//	$plugin->setPriority('last');
				$plugin->activate();
			} catch(Exception $e){
				
				var_dump($e);
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

		$user->validated = true;
		$user->validated_method = 'admin_user';
		$user->save();

		if ($login) {
			if (login($user) == FALSE) {
				register_error(elgg_echo('install:error:adminlogin'));
			}
		}
		elgg_set_ignore_access(false);
		return TRUE;
	}
        
}

