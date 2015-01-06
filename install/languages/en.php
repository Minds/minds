<?php
/**
 * Installer English Language
 *
 * @package MindsLanguage
 * @subpackage Installer
 */

$english = array(
	'install:title' => 'Minds Node Launcher',
	'install:welcome' => 'Welcome',
	'install:requirements' => 'Requirements check',
	'install:database' => 'Database installation',
	'install:settings' => 'Configure site',
	'install:admin' => 'Create admin account',
	'install:theme' => 'Theme setup',
	'install:carousel' => 'Carousel',
	'install:import' => 'Import data',
	'install:email' => 'Email SMTP',
	'install:complete' => 'Finished',
	'install:footer' => 'Footer links',
	'install:dns' => 'DNS',

	'install:next' => 'Next',
	'install:refresh' => 'Refresh',

	'install:welcome:instructions' => "Installing a node  has 6 simple steps and reading this welcome is the first one!


If you are ready to proceed, click the Next button.",
	'install:requirements:instructions:success' => "Your server passed the requirement checks.",
	'install:requirements:instructions:failure' => "Your server failed the requirements check. After you have fixed the below issues, refresh this page. Check the troubleshooting links at the bottom of this page if you need further assistance.",
	'install:requirements:instructions:warning' => "Your server passed the requirements check, but there is at least one warning. We recommend that you check the install troubleshooting page for more details.",

	'install:require:php' => 'PHP',
	'install:require:rewrite' => 'Web server',
	'install:require:settings' => 'Settings file',
	'install:require:database' => 'Database',

	'install:check:root' => 'Your web server does not have permission to create an .htaccess file in the root directory of your node. You have two choices:

		1. Change the permissions on the root directory

		2. Copy the file htaccess_dist to .htaccess',

	'install:check:php:version' => 'Minds requires PHP %s or above. This server is using version %s.',
	'install:check:php:extension' => 'Minds requires the PHP extension %s.',
	'install:check:php:extension:recommend' => 'It is recommended that the PHP extension %s is installed.',
	'install:check:php:open_basedir' => 'The open_basedir PHP directive may prevent Minds from saving files to its data directory.',
	'install:check:php:safe_mode' => 'Running PHP in safe mode is not recommened and may cause problems with Minds.',
	'install:check:php:arg_separator' => 'arg_separator.output must be & for Minds to work and your server\'s value is %s',
	'install:check:php:register_globals' => 'Register globals must be turned off.',
	'install:check:php:session.auto_start' => "session.auto_start must be off for Minds to work. Either change the configuration of your server or add this directive to Minds' .htaccess file.",

	'install:check:enginedir' => 'Your web server does not have permission to create the settings.php file in the engine directory. You have two choices:

		1. Change the permissions on the engine directory

		2. Copy the file settings.example.php to settings.php and follow the instructions in it for setting your database parameters.',
	'install:check:readsettings' => 'A settings file exists in the engine directory, but the web server cannot read it. You can delete the file or change the read permissions on it.',

	'install:check:php:success' => "Your server's PHP satisfies all of Minds' requirements.",
	'install:check:rewrite:success' => 'The test of the rewrite rules was successful.',
	'install:check:database' => 'The database requirements are checked when Minds loads its database.',

	'install:database:instructions' => "Enter the IP address for your Cassandra server and enter the keyspace you wish to use.",
	'install:database:error' => 'There was an error creating the Minds database and installation cannot continue. Review the message above and correct any problems. If you need more help, visit the Install troubleshooting link below or post to the Minds community forums.',

	'install:database:label:server' => 'Cassandra Server IP',
	'install:database:label:keyspace' => 'Cassandra Keyspace',

	'install:database:help:dbuser' => 'User that has full privileges to the MySQL database that you created for Minds',
	'install:database:help:dbpassword' => 'Password for the above database user account',
	'install:database:help:dbname' => 'Name of the Minds database',
	'install:database:help:dbhost' => 'Hostname of the MySQL server (usually localhost)',
	'install:database:help:dbprefix' => "The prefix given to all of Minds' tables (usually elgg_)",

	'install:settings:instructions' => 'We need some information about the site as we configure Minds. If you haven\'t <a href="http://docs.elgg.org/wiki/Data_directory" target="_blank">created a data directory</a> for Minds, you need to do so now.',

	'install:settings:label:sitename' => 'Site Name',
	'install:settings:label:siteemail' => 'Site Email Address',
	'install:settings:label:wwwroot' => 'Site URL',
	'install:settings:label:path' => 'Minds Install Directory',
	'install:settings:label:dataroot' => 'Data Directory',
	'install:settings:label:language' => 'Site Language',
	'install:settings:label:siteaccess' => 'Default Site Access',
	'install:label:combo:dataroot' => 'Minds creates data directory',

	'install:settings:help:sitename' => 'The name of your new Minds site',
	'install:settings:help:siteemail' => 'Email address used by Minds for communication with users',
	'install:settings:help:wwwroot' => 'The address of the site (Minds usually guesses this correctly)',
	'install:settings:help:path' => 'The directory where you put the Minds code (Minds usually guesses this correctly)',
	'install:settings:help:dataroot' => 'The directory that you created for Minds to save files (the permissions on this directory are checked when you click Next). It must be an absolute path.',
	'install:settings:help:dataroot:apache' => 'You have the option of Minds creating the data directory or entering the directory that you already created for storing user files (the permissions on this directory are checked when you click Next)',
	'install:settings:help:language' => 'The default language for the site',
	'install:settings:help:siteaccess' => 'The default access level for new user created content',

	'install:admin:instructions' => "It is now time to create an administrator's account for your new site. This account will be able to control your entire site.",

	'install:admin:label:displayname' => 'Display Name',
	'install:admin:label:email' => 'Email Address',
	'install:admin:label:username' => 'Username',
	'install:admin:label:password1' => 'Password',
	'install:admin:label:password2' => 'Password Again',

	'install:admin:help:displayname' => 'The name that is displayed on the site for this account',
	'install:admin:help:email' => '',
	'install:admin:help:username' => 'Account username used for logging in',
	'install:admin:help:password1' => "Account password must be at least %u characters long",
	'install:admin:help:password2' => 'Retype password to confirm',

	'install:admin:password:mismatch' => 'Password must match.',
	'install:admin:password:empty' => 'Password cannot be empty.',
	'install:admin:password:tooshort' => 'Your password was too short',
	'install:admin:cannot_create' => 'Unable to create an admin account.',

	'install:theme:label:logo' => 'Logo',
	'install:theme:help:logo' => 'Your site logo',
	'install:theme:label:favicon' => 'Favicon',
	'install:theme:help:favicon' => 'The small icon that displays in address bar or on tabs',
	'install:theme:label:style' => 'Style',
	'install:theme:help:style' => 'Select a style for your node',
	'install:theme:label:page_header' => 'Site header',
	'install:theme:help:page_header' => 'Enter text you want for the front page header',
	'install:theme:label:themeset' => 'Themset',
	'install:theme:help:themeset' => 'Select the layout for your new node',
    
	'install:footer:label:copyright' => 'Copyright',
	'install:footer:help:copyright' => '',
	'install:footer:label:networks_facebook' => 'Facebook',
	'install:footer:help:networks_facebook' => 'Link to your Facebook profile',
	'install:footer:label:networks_twitter' => 'Twitter',
	'install:footer:help:networks_twitter' => 'Link to your Twitter profile',
	'install:footer:label:networks_gplus' => 'Google+',
	'install:footer:help:networks_gplus' => 'Link to your Google+ profile',
	'install:footer:label:networks_linkedin' => 'LinkedIn',
	'install:footer:help:networks_linkedin' => 'Link to your LinkedIn profile',
	'install:footer:label:networks_tumblr' => 'Tumblr',
	'install:footer:help:networks_tumblr' => 'Link to your Tumblr account',
	'install:footer:label:networks_pinterest' => 'Pinterest',
	'install:footer:help:networks_pinterest' => 'Link to your Pinterest profile',
	'install:footer:label:networks_vimeo' => 'Vimeo',
	'install:footer:help:networks_vimeo' => 'Link to your Vimeo page',
	'install:footer:label:networks_github' => 'Github',
	'install:footer:help:networks_github' => 'Link to your Github account',

	'install:import:label:import' => 'Do you want to import trending blogs from minds.com?',
	'install:import:help:import' => 'If you are new to Minds then we recommend you have some content on your node to help you get started',	
	'install:import:label:scraper' => 'Import an rss feed',
	'install:import:help:scraper' => 'Rss feeds are a great way to keep new content flowing into your site',

	'install:complete:instructions' => 'Your Minds site is now ready to be used. Click the button below to be taken to your site.',
	'install:complete:gotosite' => 'Go to site',

	'InstallationException:UnknownStep' => '%s is an unknown installation step.',

	'install:success:database' => 'Database has been installed.',
	'install:success:settings' => 'Site settings have been saved.',
	'install:success:admin' => 'Admin account has been created.',
	'install:success:theme' => 'Theme configurations are now set.',
	'install:success:import' => 'Import configurations have been set.',	

	'install:error:htaccess' => 'Unable to create an .htaccess',
	'install:error:settings' => 'Unable to create the settings file',
	'install:error:databasesettings' => 'Unable to connect to the database with these settings.',
	'install:error:oldmysql' => 'MySQL must be version 5.0 or above. Your server is using %s.',
	'install:error:nodatabase' => 'Unable to use database %s. It may not exist.',
	'install:error:cannotloadtables' => 'Cannot load the database tables',
	'install:error:tables_exist' => 'There are already Minds tables in the database. You need to either drop those tables or restart the installer and we will attempt to use them. To restart the installer, remove \'?step=database\' from the URL in your browser\'s address bar and press Enter.',
	'install:error:readsettingsphp' => 'Unable to read engine/settings.example.php',
	'install:error:writesettingphp' => 'Unable to write engine/settings.php',
	'install:error:requiredfield' => '%s is required',
	'install:error:relative_path' => 'We don\'t think "%s" is an absoluate path for your data directory',
	'install:error:datadirectoryexists' => 'Your data directory %s does not exist.',
	'install:error:writedatadirectory' => 'Your data directory %s is not writable by the web server.',
	'install:error:locationdatadirectory' => 'Your data directory %s must be outside of your install path for security.',
	'install:error:emailaddress' => '%s is not a valid email address',
	'install:error:createsite' => 'Unable to create the site.',
	'install:error:savesitesettings' => 'Unable to save site settings',
	'install:error:loadadmin' => 'Unable to load admin user.',
	'install:error:adminaccess' => 'Unable to give new user account admin privileges.',
	'install:error:adminlogin' => 'Unable to login the new admin user automatically.',
	'install:error:rewrite:apache' => 'We think your server is running the Apache web server.',
	'install:error:rewrite:nginx' => 'We think your server is running the Nginx web server.',
	'install:error:rewrite:lighttpd' => 'We think your server is running the Lighttpd web server.',
	'install:error:rewrite:iis' => 'We think your server is running the IIS web server.',
	'install:error:rewrite:allowoverride' => "The rewrite test failed and the most likely cause is that AllowOverride is not set to All for Minds' directory. This prevents Apache from processing the .htaccess file which contains the rewrite rules.
				\n\nA less likely cause is Apache is configured with an alias for your Minds directory and you need to set the RewriteBase in your .htaccess. There are further instructions in the .htaccess file in your Minds directory.",
	'install:error:rewrite:htaccess:write_permission' => 'Your web server does not have permission to create the .htaccess file in Minds\'s directory. You need to manually copy htaccess_dist to .htaccess or change the permissions on the directory.',
	'install:error:rewrite:htaccess:read_permission' => 'There is an .htaccess file in Minds\'s directory, but your web server does not have permission to read it.',
	'install:error:rewrite:htaccess:non_elgg_htaccess' => 'There is an .htaccess file in Minds\'s directory that was not not created by Minds. Please remove it.',
	'install:error:rewrite:htaccess:old_elgg_htaccess' => 'There appears to be an old Minds .htaccess file in Minds\'s directory. It does not contain the rewrite rule for testing the web server.',
	'install:error:rewrite:htaccess:cannot_copy' => 'A unknown error occurred while creating the .htaccess file. You need to manually copy htaccess_dist to .htaccess in Minds\'s directory.',
	'install:error:rewrite:altserver' => 'The rewrite rules test failed. You need to configure your web server with Minds\'s rewrite rules and try again.',
	'install:error:rewrite:unknown' => 'Oof. We couldn\'t figure out what kind of web server is running on your server and it failed the rewrite rules. We cannot offer any specific advice. Please check the troubleshooting link.',
	'install:warning:rewrite:unknown' => 'Your server does not support automatic testing of the rewrite rules and your browser does not support checking via JavaScript. You can continue the installation, but you may experience problems with your site. You can manually test the rewrite rules by clicking this link: <a href="%s" target="_blank">test</a>. You will see the word success if the rules are working.',
);

add_translation("en", $english);
