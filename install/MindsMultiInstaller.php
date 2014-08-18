<?php

/**
 * Minds Multisite Installer.
 */
class MindsMultiInstaller extends ElggInstaller {

	protected $type = 'multi';

    protected $steps = array(
        'welcome',
        'settings',
        'admin',
       // 'minds',
		'theme',
		'footer',
		'carousel',
		'import',
		'email',
        'complete',
    );
    
    protected $status = array(
        'config' => FALSE,
        'database' => FALSE,
        'settings' => FALSE,
        'admin' => FALSE,
        'minds' => FALSE,
    );
   
    protected $web_services_url = 'https://www.minds.com/services/api/rest/json/';

    public function __construct() {
        parent::__construct();

        // Load minds translations
        register_translations(dirname(__FILE__) . '/languages/minds/', TRUE);

		// Always log admin in
		$this->setAutoLogin(true);

    }

    /**
     * Set up configuration variables
     *
     * @return void
     */
    protected function bootstrapConfig() {
        
	parent::bootstrapConfig(); 
        
        global $CONFIG;
        if (!isset($CONFIG)) {
            $CONFIG = new stdClass;
        }

        //$CONFIG->wwwroot =  
        $CONFIG->url = $CONFIG->wwwroot;
        $CONFIG->path = dirname(dirname(__FILE__)) . '/';
        $CONFIG->viewpath = $CONFIG->path . 'views/';
        $CONFIG->pluginspath = $CONFIG->path . 'mod/';
        $CONFIG->context = array();
        $CONFIG->entity_types = array('group', 'object', 'site', 'user');
    }

    protected function finishBootstraping($step){
	parent::finishBootstraping($step);
	
	if ($name = get_input('name'))
                $_SESSION['m_name'] = $name;
        if ($email = get_input('email'))
                $_SESSION['m_email'] = $email;
        if ($username = get_input('username'))
                $_SESSION['m_username'] = $username;
    
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
        $body = elgg_view("install/mindspages/$step", $vars);
        echo elgg_view_page(
                $title, $body, 'default', array(
            'step' => $step,
            'steps' => $this->getSteps(),
                )
        );
        exit;
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
                           'value' => $_SESSION['m_name'],
                           'required' => TRUE,
                           ),
                   'email' => array(
                           'type' => 'text',
                           'value' => $_SESSION['m_email'],
                           'required' => TRUE,
                           ),
                   'username' => array(
                           'type' => 'text',
                           'value' => $_SESSION['m_username'],
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
    * We never want to create a settings file...
    * @param type $params
    * @return boolean
    */
   protected function createSettingsFile($params) {
       return true;
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
                'value' => 'My New Community',
                'required' => TRUE,
            ),
            'siteemail' => array(
                'type' => 'text',
                'value' => $_SESSION['m_email'],
                'required' => FALSE,
            ),
            'wwwroot' => array(
                'type' => 'hidden',
                'value' => elgg_get_site_url(),
                'required' => TRUE,
            ),
            'path' => array(
                'type' => 'hidden',
                'value' => $CONFIG->path,
                'required' => TRUE,
            ),
            'dataroot' => array(
                'type' => 'hidden',
                'value' => $CONFIG->dataroot,
                'required' => TRUE,
            ),
            'siteaccess' => array(
                'type' => 'access',
                'value' => ACCESS_PUBLIC,
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
     * Handle resume better for our purposes.
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
            //forward("install.php?step=settings");
            return;
        }

        if ($this->status['admin'] == FALSE) {
            forward("install.php?step=admin");
        }

        if ($this->status['minds'] == FALSE) {
            forward("install.php?step=minds");
        }

        // everything appears to be set up
        forward("install.php?step=complete");

        return;
    }

    /**
     * Batch install database and verify base config, since this has already been done.
     * @global type $CONFIG
     * @throws InstallationException
     */
    public function setupMulti() {
	new minds\core\multisite();
        $this->setInstallStatus();

	global $CONFIG;
        try{
		$node = new minds\multisite\models\node($_SERVER['HTTP_HOST']);
		
		if($node->installed == true){
			throw new Exception("This site is already installed");
		}
	} catch(Exception $e){
		var_dump($e->getMessage());
		exit;
	}

        if (!$this->status['config']) {
            if (!$this->createSettingsFile($params)) {
                throw new InstallationException(elgg_echo('install:error:settings'));
            }
        }
	
	if (!$this->status['database']) {
        	$this->installDatabase() ;
        }
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
			if(!$db->keyspaceExists($CONFIG->cassandra->keyspace)){
				$attrs = array(	  "strategy_options" => array("replication_factor" => "2"));	
				$db->createKeyspace($attrs);
			}
			$db->installSchema();
		} catch (Exception $e){
			register_error($e->why);
			var_dump($e);
			exit;
			return false;
		}
		
		return true;
	}

    /**
     * Enable plugins, toggle what we can see.
     */
    protected function enablePlugins() {
        parent::enablePlugins();

        global $CONFIG;

        // Now, specify what plugins are visible for any given domain
        $domain = $CONFIG->elgg_multisite_settings;
        $user_editable_plugins = array(
            'groups',
	    'archive',
            'blog',
   		'deck', 
            'persona',
            'notifications',
            //'minds_connect',
            'mobile',
	    'minds_social',
            'minds_themeconfig',
		'anypage',
		'tinymce'
            //'minds_wordpress',
        );
        foreach ($user_editable_plugins as $plugin_id) {
        	$plugin = new ElggPlugin($plugin_id);
		$plugin->save();
		$plugin = new ElggPlugin($plugin_id);
		$plugin->activate();
        }
        
        // Now configure some plugins
        if (is_array($CONFIG->plugin_install_defaults)) {
            foreach ($CONFIG->plugin_install_defaults as $plugin => $settings) {
                if (is_array($settings)) {
                    foreach ($settings as $key => $value)
                        elgg_set_plugin_setting($key, $value, $plugin);
                }
            }
        }
        
        
    }


    protected function disableInstallation(){
		global $CONFIG;
    	$node = new minds\multisite\models\node($_SERVER['HTTP_HOST']);
		$node->installed = true;
		$node->save();
		unlink("/tmp/nodes/".$_SERVER['HTTP_HOST']);
    }

}
