<?php

/**
 * Minds Multisite Installer.
 */
class MindsMultiInstaller extends ElggInstaller {

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

	if(get_input('debug')){
		$db = new \minds\core\data\call('entities');
		var_dump(elgg_get_plugins('active'));
		//$db->insert('hello', array('testing'=>'it works'));
	//	var_dump($db-> getCf('entities'),$db->getRow('hello'));
		exit;
	}

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
     * Footer configuration
     * @param type $submissionVars
     */
    protected function footer($submissionVars) {
	
	$networks = minds_config_social_links();
	
	$formVars = array(
            'copyright' => array(
                'type' => 'text',
                'value' => '',
                'required' => FALSE,
            ),
        );
	
	foreach($networks as $network => $n) {
	    $formVars["networks_$network"] = array(
		'type' => 'text',
                'value' => '',
		
                'required' => FALSE,
	    );
	}

        if ($this->isAction) {
            do {
		elgg_set_plugin_setting('copyright',  $submissionVars['copyright'], 'minds_themeconfig');
		
		foreach($networks as $network => $n) {
		    
		    elgg_set_plugin_setting($network.':url', $submissionVars["networks_$network"], "minds_themeconfig");
		    
		}

                system_message(elgg_echo('install:success:footer'));

                $this->continueToNextStep('footer');
            } while (FALSE);  // PHP doesn't support breaking out of if statements
        }

        $formVars = $this->makeFormSticky($formVars, $submissionVars);

        $this->render('footer', array('variables' => $formVars));
    }
    
    /**
     * Load the theme selector
     * @param array $vars Not used
     *
     * @return void
     */
    protected function theme($submissionVars) {

	//Themeset options
	$themesets = minds_themeconfig_get_themesets();

	$themeset_options = array();
	foreach($themesets as $themeset){
		$icon = "<img src='". minds_themeconfig_get_themeset_icon($themeset) . "'/>";
		$content = "$icon <h3>$themeset</h3>";
		$themeset_options[$content] = $themeset;
	}


        $formVars = array(
            'logo' => array(
                'type' => 'file',
                'value' => '',
                'required' => FALSE,
            ),
	    'favicon' => array(
		'type' => 'file',
		'value' => '',
		'required' => FALSE
	    ),
            'themeset' => array(
                'type' => 'radio',
                'value' => 'minds-left',
		'options' => $themeset_options,
                'required' => TRUE,
     		'class'=> 'themesets'
	       ),
	    /*'page_header' => array(
		'type' => 'text',
		'value' => elgg_get_plugin_setting('frontpagetext','minds_themeconfig'),
		'required' => FALSE
	    )*/
        );

        if ($this->isAction) {
            do {
		if (!$this->saveTheme($submissionVars, $_FILES['logo'])) {
                    register_error(elgg_echo('install:fail:theme'));

                    $this->continueToNextStep('theme');
                }

                system_message(elgg_echo('install:success:theme'));

                $this->continueToNextStep('theme');
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
    
	/**
	 * MAIN LOGO
	 */
	$files = array();
	$sizes = array(
                'logo_main' => array(
            //'w' => 400,
            'h' => 200,
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
 		$resized = get_resized_image_from_existing_file($submissionVars['logo']['tmp_name'], $size_info['w'], $size_info['h'], $size_info['square'], 0, 0, 0, 0, $size_info['upscale'], 'png');
	}

		if ($resized) {
                	global $CONFIG;
                	$theme_dir = $CONFIG->dataroot . 'minds_themeconfig/';
                	@mkdir($theme_dir);

                	file_put_contents($theme_dir . $name.'.png', $resized);
                	
                	elgg_set_plugin_setting('logo_override', 'true', 'minds_themeconfig');
	        	 elgg_set_plugin_setting('logo_override_ts', time(), 'minds_themeconfig');    
		}
		if (isset($_FILES['logo']) && ($_FILES['logo']['error'] != UPLOAD_ERR_NO_FILE) && $_FILES['logo']['error'] != 0) {
               	//	register_error(minds_themeconfig_codeToMessage($_FILES['logo']['error'])); // Debug uploads
           	 }
	}
	/**
	 * FAVICON UPLOADER 
	 */
	// Favicon
	foreach (array(
        	'logo_favicon' => array(
          	 	'w' => 32,
            		'h' => 32,
           		'square' => true,
         		'upscale' => true
       		)
  		) as $name => $size_info) { 
      		$resized = get_resized_image_from_existing_file($submissionVars['favicon']['tmp_name'], $size_info['w'], $size_info['h'], $size_info['square'], 0, 0, 0, 0, $size_info['upscale'], 'jpeg');

       		 if ($resized) {
            		global $CONFIG;
            		$theme_dir = $CONFIG->dataroot . 'minds_themeconfig/';
           	 	@mkdir($theme_dir);

            		file_put_contents($theme_dir . $name.'.jpg', $resized);

            		elgg_set_plugin_setting('logo_favicon', 'true', 'minds_themeconfig');
            		elgg_set_plugin_setting('logo_favicon_ts', time(), 'minds_themeconfig');
            
        	} 

        	if (isset($_FILES['favicon']) && ($_FILES['favicon']['error'] != UPLOAD_ERR_NO_FILE) && $_FILES['favicon']['error'] != 0) {
            	//	register_error(minds_themeconfig_codeToMessage($_FILES['favicon']['error'])); // Debug uploads
        	}
	}

	// Save frontpage text
	//elgg_set_plugin_setting('frontpagetext', $submissionVars['page_header'], 'minds_themeconfig');
  
	elgg_set_plugin_setting('themeset', $submissionVars['themeset'], 'minds_themeconfig');
	datalist_set('site_featured_menu_use_icons', 'no');
	
	return true; 
    }

    public function carousel($submissionVars){
	 $this->continueToNextStep('carousel');
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
                'value' => 'no',
                'options' => array('Yes'=>'yes', 'No'=>'no'),
                'required' => FALSE,
            ),
	    'scraper' => array(
		'type'=>'text',
		'placeholder'=> 'eg. http://mysite.com/feed.rss',
		'requied'=> false
	    )
        );

        if ($this->isAction) {
            do {
			$this->doImport($submissionVars);
		     system_message(elgg_echo('install:success:import'));

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

	if ($submissionVars['import'] == 'yes') {
		$endpoint = 'http://www.minds.com/blog/trending?view=json';
		$result = json_decode(file_get_contents($endpoint));
		$blogs = $result->object->blog;
		
		//get the only user registered...
		$options = array('type' => 'user', 'full_view' => false, 'limit'=>1);
		$admin = elgg_get_entities($options);

		foreach($blogs as $blog){
			$g = new GUID(); 
			$b = new ElggBlog();
			$b->owner_guid = $admin[0]->guid;
			$b->title = $blog->title;
			$b->description = $blog->description;
			$b->minds_import = true;
			$b->featured_id = $g->generate();
			$b->featured = 1;
			$b->save();
			$b->feature();
		
			add_to_river('river/object/'.$b->getSubtype().'/feature', 'feature', $b->getOwnerGUID(), $b->getGuid());
		}
	}
        
	if($submissionVars['scraper']){
		$scraper = new MindsScraper();
		$scraper->feed_url = $submissionVars['scraper'];
		$scraper->save();
	}
	return true;
    }
	
     /**
     * Configure the sites smtp settings
     * @param array $vars Not used
     *
     * @return void
     */
    protected function email($submissionVars) {

	$this->continueToNextStep('email');
	return true;	

        $formVars = array(
            'default' => array(
                'type' => 'radio',
                'value' => 'yes',
                'options' => array('Yes'=>'yes', 'No'=>'no'),
                'required' => True,
            ),
            'scraper' => array(
                'type'=>'text',
                'placeholder'=> 'eg. http://mysite.com/feed.rss',
                'requied'=> false
            )
        );

        if ($this->isAction) {
            do {
                        $this->doImport($submissionVars);
                     system_message(elgg_echo('install:success:import'));

                $this->continueToNextStep('import');
            } while (FALSE);  // PHP doesn't support breaking out of if statements
        }

        $formVars = $this->makeFormSticky($formVars, $submissionVars);

        $this->render('import', array('variables' => $formVars));
    }

    protected function disableInstallation(){
	global $CONFIG;
    	$node = new minds\multisite\models\node($_SERVER['HTTP_HOST']);
	$node->installed = true;
	$node->save();
    }

}
