Minds
==========

___Copyright (c) Copyleft 2008-2015___

Branch  | Status | |
------------- | ------------- | ----------
Master | [![Build Status](https://magnum.travis-ci.com/Minds/Minds.png?token=vHzWaxguqXbJqkudCFTn&branch=master)](https://magnum.travis-ci.com/Minds/Minds)  | main working branch |
Production | [![Build Status](https://magnum.travis-ci.com/Minds/Minds.png?token=vHzWaxguqXbJqkudCFTn&branch=production)](https://magnum.travis-ci.com/Minds/Minds) | safe for use on production servers
Angular | [![Build Status](https://magnum.travis-ci.com/Minds/Minds.png?token=vHzWaxguqXbJqkudCFTn&branch=angular)](https://magnum.travis-ci.com/Minds/Minds) | a new front end

## Introduction
Minds is the free and open-source social networking platform. 

This *readme* file should hopefuly explain all you need to get started, if not, please add to it or email **mark@minds.com**.

----


## Installation

We recommend you use our vagrant/chef cookbook for quick setup. https://github.com/Minds/minds-cookbook

### Requirements
- PHP 5.4+
- Cassandra
- Nginx
- RabbitMQ (ability to disable to low traffic sites coming soon)
- Unix/Linux

_Optional_
- Neo4j (for suggested users)
- Elasticsearch (for search only)
- Redis (for caching in clustered environments)
- MongoDB (for boost functions)
- Cinemr (for video features only)

### Development Tools

##### Composer

- Run `curl -sS https://getcomposer.org/installer | php`

##### NPM

- Visit https://nodejs.org/ and install nodejs

##### Gulp 

- Run `sudo npm install -g gulp`


### Setup

The new minds front end requires plugins to be compiled. Run `gulp build`.

- Go to localhost/install.php and follow the instruction. (debugging may be needed)

### Generate new docs (core devs only)

- php vendor/bin/apigen generate -s src -d ./docs/api

--------

## Getting started
Minds is gradually implementing an Object Orientated code base. Elgg functions can still be called, but it is preferred for new plugins to follow the following structure.


### Calling the database
Minds implements Cassandra as its database. Cassandra is a NoSQL datastore. 

You should generally store your indexes on **write** and retrieve later with a lookup query.

##### Example of indexes
```
$index = new \Minds\Core\Data\indexes();

$guids = $index->get('object:blog', array('limit'=>12, 'offset'=>''));

//adding and index, or to the index
$index->set('index:key:separated:by:colon', array('key'=>'value'));

```
##### Example user lookup
``` 
$email = 'mark@minds.com'
$lookup = new \Minds\Core\Data\lookup();

$guid = $lookup->get($email, array('limit'=>1));
$user = new \minds\entities\user($guid);

```

--------
### Entities
Elgg's 'classes' folder is large and unstructured. Entities have now been moved to their own folder in **engine/entities** and can be called with the new namespace **minds\entities**.

```
use minds\entities;
// load a user
$user = entities\user('mark);

// load an object
$object = entities\object(1234345662);
```

--------
### Writing a plugin
The core Minds plugins are gradually being migrated to the newly structured Minds OOP method. OOP (Object Orientated PHP) provides a much cleaner code base and avoids replicated code and complexity. 

##### Setup
- You need a manifest file
- You need a file with the same name as your folder eg. market. This is your init file and plugin controller.

##### Init file
Below is an example of how to start your plugin.

```
<?php 
namespace minds\plugin\myplugin;

use Minds\Core;

class myplugin extends core\plugin{
	public function init(){
		//this is called upon every page load. 
	}
}
```
##### Handling pages
The old Elgg page handler only supported *GET* requests by default and brought with it an uneeded level of complexity. 

```
	//place this in the init of your plugin
	public funciton init(){
		\Minds\Core\router::registerRoutes(array(
			'/myplugin' => '\minds\plugin\myplugin\pages\default',
			'/myplugin/another_page' => '\minds\plugin\myplugin\pages\another_page'
		));
	}

```

For example, when you hit http://MYSITE/myplugin, minds will now load your page handler found in **mod/myplugin/pages/default.php** and it should have the following structure

```
<?php 
namespace minds\plugin\myplugin\pages;

use minds\interfaces;
use Minds\Core;
class default extends core\page inherits interfaces\page{
	public function get($pages){
		echo "This is my plugin default page";
	}
	public function post($pages){}
	public function put($pages){}
	public function delete($pages){}
}
```

--------
## Copyright and licenses
###Minds

http://minds.com, http://minds.org

*Minds.org, Inc.* is a free and open source social network.

####Co-Creators 
- Mark Harding (mark@minds.com)
- Bill Ottman (bill@minds.com)
- John Ottman (john@minds.com)
- Marcus Povey (marcus@minds.com)




Minds is released under the GNU Affero General Public License (AGPL) Version 3. See LICENSE.txt 
in the root of the package you downloaded.

For installation instructions, see INSTALL.txt.

For upgrade instructions, see UPGRADE.txt.
