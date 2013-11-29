<?php

/**
 * Minds Multisite Installer.
 */
class MindsMultiInstaller extends ElggInstaller {

    protected $steps = array(
        'welcome',
//        'requirements',
        //       'database',
        'settings',
        'admin',
        'minds',
	'theme',
	'import',
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

        // Set the web services URL 
        global $CONFIG;
        if (isset($CONFIG->web_services_url))
            $this->web_services_url = $CONFIG->web_services_url;
        
        // Now, see if we're passed any setup options - if so, save them to session
        if ($name = get_input('name'))
                $_SESSION['m_name'] = $name;
        if ($email = get_input('email'))
                $_SESSION['m_email'] = $email;
        if ($username = get_input('username'))
                $_SESSION['m_username'] = $username;
	
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

        $CONFIG->wwwroot = $CONFIG->elgg_multisite_settings->wwwroot;
        $CONFIG->url = $CONFIG->wwwroot;
        $CONFIG->path = dirname(dirname(__FILE__)) . '/';
        $CONFIG->viewpath = $CONFIG->path . 'views/';
        $CONFIG->pluginspath = $CONFIG->path . 'mod/';
        $CONFIG->context = array();
        $CONFIG->entity_types = array('group', 'object', 'site', 'user');
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
        db_init();
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
             
            }
        }
    }

    /**
     * Enable plugins, toggle what we can see.
     */
    protected function enablePlugins() {
        parent::enablePlugins();

        global $CONFIG;

        // Ensure plugin manager is activated
        $plugin = elgg_get_plugin_from_id('pluginmanager');
        if ($plugin instanceof ElggPlugin) {
            $plugin->setPriority('last');
            $plugin->activate();
        }

        $plugin = elgg_get_plugin_from_id('mindsmulti_pluginmanager');
        if ($plugin instanceof ElggPlugin) {
            $plugin->setPriority('last');
            $plugin->activate();
        }

        // Now, specify what plugins are visible for any given domain
        $domain = $CONFIG->elgg_multisite_settings;
        $user_editable_plugins = array(
            //'uservalidationbyemail', 
            //'htmlawed',
            //'logbrowser',
            //'logrotate',
            //'oauth2', 
            //'oauth_api', 
            'channel',
            'groups',
            'wall',
            //'tidypics', 
            'analytics',
		'archive',
            //'embed',
            //'embed_extender',
            'blog',
            //'thumbs',
            //'minds_search', 
            //'minds_comments',
            //'minds_social',
            //'minds_webservices',
            'persona',
            'notifications',
            'minds_connect',
                //'bootcamp',
            'mobile',
                //'minds'
            'anypage',
            'Login-As',
            'minds_widgets',
        );
        foreach ($user_editable_plugins as $plugin_id) {
            elggmulti_toggle_plugin($domain->getID(), $plugin_id);
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

    protected function setInstallStatus() {
        parent::setInstallStatus();

        if ($this->status['settings']) {
            // See if minds has been connected

            if ($this->checkMindsConnect()) {
                $this->status['minds'] = TRUE;
            } else {
                return;
            }
        }
        return false;
    }

    /**
     * Minds controller
     *
     * @param array $vars Not used
     *
     * @return void
     */
    protected function minds($submissionVars) {

        /* $formVars = array(
          'client_id' => array(
          'type' => 'text',
          'value' => '',
          'required' => TRUE,
          'id' => 'client_id'
          ),
          'client_secret' => array(
          'type' => 'text',
          'value' => '',
          'required' => FALSE,
          'id' => 'client_secret'
          ),
          ); */

        $formVars = array(
            'minds_username' => array(
                'type' => 'text',
                'value' => $_SESSION['m_username'],
                'required' => TRUE,
            ),
            'minds_password' => array(
                'type' => 'password',
                'value' => '',
                'required' => TRUE,
            ),
        );

        if ($this->isAction) {
            do {

                if (!$this->connectMinds($submissionVars)) {
                    register_error(elgg_echo('install:fail:minds'));

                    $this->continueToNextStep('minds');
                }

                system_message(elgg_echo('install:success:minds'));

                $this->continueToNextStep('minds');
            } while (FALSE);  // PHP doesn't support breaking out of if statements
        }

        $formVars = $this->makeFormSticky($formVars, $submissionVars);

        $this->render('minds', array('variables' => $formVars, 'endpoint' => $this->web_services_url));
    }

    /**
     * Check whether this install has been connected to Minds
     */
    protected function checkMindsConnect() {
        global $CONFIG;

        // kludge: make sure these files are loaded to avoid WSOD
        include_once($CONFIG->path . 'engine/lib/private_settings.php');
        include_once($CONFIG->path . 'engine/lib/memcache.php');
        include_once($CONFIG->path . 'engine/lib/plugins.php');
        include_once($CONFIG->path . 'engine/lib/entities.php');

        if (elgg_get_plugin_setting('client_id', 'minds_connect') && elgg_get_plugin_setting('client_secret', 'minds_connect'))
            return true;

        return false;
    }

    /**
     * Save minds connection settings.
     * @param type $submissionVars
     */
    protected function connectMinds($submissionVars) {

        global $CONFIG;

        // Call endpoint
        if (!$_SESSION["minds-connect-token"]) {
            $url_bits = parse_url(elgg_get_site_url());
            $result = json_decode(file_get_contents($this->web_services_url . '?' . implode("&", array(
                                'method=oauth2.application.register',
                                'minds_username=' . $submissionVars['minds_username'],
                                'minds_password=' . $submissionVars['minds_password'],
                                'url=' . $url_bits['host']
            ))));
            $client_id = $result->result->client_id;
            $client_secret = $result->result->client_secret;
		if(!$client_id || !$client_secret){
			return false;
		}
		
            return elgg_set_plugin_setting('client_id', $client_id, 'minds_connect') &&
                    elgg_set_plugin_setting('client_secret', $client_secret, 'minds_connect') &&
                    elgg_set_plugin_setting('minds_url', isset($CONFIG->minds_url) ? $CONFIG->minds_url : 'https://www.minds.com', 'minds_connect');
        }

        return false;
    }

    /**
     * Load the theme selector
     * @param array $vars Not used
     *
     * @return void
     */
    protected function theme($submissionVars) {

        $formVars = array(
            'logo' => array(
                'type' => 'file',
                'value' => '',
                'required' => FALSE,
            ),
            /*'style' => array(
                'type' => 'radio',
                'value' => 'theme1',
		'options' => array('theme1'=>'theme1', 'theme2'=>'theme2'),
                'required' => TRUE,
            ),*/
	    'page_header' => array(
		'type' => 'text',
		'value' => elgg_get_plugin_setting('frontpagetext','minds_themeconfig'),
		'required' => FALSE
	    )
        );

        if ($this->isAction) {
            do {
		if (!$this->saveTheme($submissionVars, $_FILES['logo'])) {
                    register_error(elgg_echo('install:fail:theme'));

                    $this->continueToNextStep('theme');
                }

                system_message(elgg_echo('install:success:theme'));

                $this->continueToNextStep('import');
            } while (FALSE);  // PHP doesn't support breaking out of if statements
        }

        $formVars = $this->makeFormSticky($formVars, $submissionVars);

        $this->render('theme', array('variables' => $formVars));
    }    
 
    /**
     * Save theme settings.
     * @param type $submissionVars
     */
    protected function saveTheme($submissionVars){

        global $CONFIG;
    
	// Save logo (generate a couple of sizes)
	$files = array();
	$sizes = array(
                'logo_main' => array(
            'w' => 200,
            'h' => 90,
            'square' => false,
            'upscale' => true
                ),
                'logo_topbar' => array(
            'w' => 78,
            'h' => 30,
            'square' => false,
            'upscale' => true
                ));
	foreach ($sizes as $name => $size_info) {

		// If our file exists ...
	if (isset($submissionVars['logo']) && $submissionVars['logo']['error'] == 0) {
 		$resized = get_resized_image_from_existing_file($submissionVars['logo']['tmp_name'], $size_info['w'], $size_info['h'], $size_info['square'], 0, 0, 0, 0, $size_info['upscale']);
	}

		if ($resized) {
                	global $CONFIG;
                	$theme_dir = $CONFIG->dataroot . 'minds_themeconfig/';
                	@mkdir($theme_dir);

                	file_put_contents($theme_dir . $name.'.jpg', $resized);
                
                	elgg_set_plugin_setting('logo_override', 'true', 'minds_themeconfig');
            	}
		if (isset($_FILES['logo']) && ($_FILES['logo']['error'] != UPLOAD_ERR_NO_FILE) && $_FILES['logo']['error'] != 0) {
               		register_error(minds_themeconfig_codeToMessage($_FILES['logo']['error'])); // Debug uploads
           	 }
	}
	// Save frontpage text
	elgg_set_plugin_setting('frontpagetext', $submissionVars['page_header'], 'minds_themeconfig');
    
    }

    /**
     * Load the minds content importer (gives the option to import trending blogs from minds.com)
     * @param array $vars Not used
     *
     * @return void
     */
    protected function import($submissionVars) {

        $formVars = array(
            'import' => array(
                'type' => 'radio',
                'value' => true,
                'options' => array('Yes'=>'yes', 'No'=>'no'),
                'required' => TRUE,
            ),
        );

        if ($this->isAction) {
            do {
                if ($submissionVars['import'] == 'yes') {
			$this->doImport($submissionVars);
		     system_message(elgg_echo('install:success:import'));
                }

                $this->continueToNextStep('import');
            } while (FALSE);  // PHP doesn't support breaking out of if statements
        }

        $formVars = $this->makeFormSticky($formVars, $submissionVars);

        $this->render('import', array('variables' => $formVars));
    }

    /**
     * Save theme settings.
     * @param type $submissionVars
     */
    protected function doImport($submissionVars) {

        global $CONFIG;
	$endpoint = 'http://www.minds.com/blog/trending?view=json';
	$result = json_decode(file_get_contents($endpoint));
	$blogs = $result->object->blog;
	
	//get the only user registered...
	$options = array('type' => 'user', 'full_view' => false, 'limit'=>1);
	$admin = elgg_get_entities($options);

	foreach($blogs as $blog){
		$g = new GUID(); 
		$b = new ElggObject();
		$b->subtype = 'blog';
		$b->owner_guid = $admin[0]->guid;
		$b->title = $blog->title;
		$b->description = $blog->description;
		$b->minds_import = true;
		$b->featured_id = $g->generate();
		$b->featured = 1;
		$b->save();

		db_insert('object:featured', array('type'=>'entities_by_time',$b->featured_id => $b->getGUID()));
		db_insert('object:'.$b->subtype.':featured', array('type'=>'entities_by_time',$b->featured_id => $b->getGUID()));
	
		add_to_river('river/object/'.$b->getSubtype().'/feature', 'feature', $b->getOwnerGUID(), $b->getGuid());


	}
        return true;
    }
}
