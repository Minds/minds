Minds
=====
Minds is an open-source, encrypted and reward-based social networking platform. https://minds.com

## Repositories
Minds is split into multiple repositories:

- [Engine](https://github.com/Minds/engine) - Backend code & APIs
- [Front](https://github.com/Minds/front) - Client side Angular2 web app
- [Sockets](https://github.com/Minds/sockets) - WebSocket server for real-time communication
- [Mobile](https://github.com/Minds/mobile-native) - React Native mobile apps


## Documentation

The below do

Documentation for Minds can be found at [minds.org/docs](https://www.minds.org/docs)
1. [Installation](https://www.minds.org/docs/install.html)
  1. [Requirements](https://www.minds.org/docs/install/requirements.html)
  2. [Download](https://www.minds.org/docs/install/download.html)
  3. [Vagrant Development Environment](https://www.minds.org/docs/install/vagrant.html)
  4. [Install & Build](https://www.minds.org/docs/install/preparation.html)
    1. [Front End](https://www.minds.org/docs/install/preparation.html#front-end)
    2. [Engine](https://www.minds.org/docs/install/preparation.html#engine-php)
    3. [Install Script](https://www.minds.org/docs/install/installation.html)
  5. [Troubleshooting](https://www.minds.org/docs/install/troubleshooting.html)
2. [Testing](https://www.minds.org/docs/testing.html)
3. [Contributing](https://www.minds.org/docs/contributing.html)

## Docker setup

The Docker environment is currently a work in progress and we intend on streamlining the installation phase.

1. Run `docker ps` and look for the minds_php-fpm container
2. Run `docker exec -it CONTAINER_ID_HERE php /var/www/Minds/engine/cli.php install keys`
3. Run `docker exec -it CONTAINER_ID_HERE php /var/www/Minds/engine/cli.php install --graceful-storage-provision --domain=dev.minds.io --username=minds     --password=password --email=minds@dev.minds.io --private-key=/.dev/minds.pem --public-key=/.dev/minds.pub --cassandra-server=cassandra`

## Contributing
If you'd like to contribute to the Minds project, check out the [Contribution](https://www.minds.org/docs/contributing.html) section of Minds.org or head right over to the [Minds Open Source Community](https://www.minds.com/groups/profile/365903183068794880).  If you've found or fixed a bug, let us know in the [Minds Help and Support Group](https://www.minds.com/groups/profile/100000000000000681/activity)!

## Security reports
Please report all security issues to [security@minds.com](mailto:security@minds.com).

## License
[AGPLv3](https://www.minds.org/docs/license.html). Please see the license file of each repository.

## Credits
[PHP](https://php.net), [Cassandra](http://cassandra.apache.org/), [Angular2](http://angular.io), [Nginx](https://nginx.com), [Ubuntu](https://ubuntu.com), [OpenSSL](https://www.openssl.org/), [RabbitMQ](https://www.rabbitmq.com/), [Elasticsearch](https://www.elastic.co/), [Cordova](https://cordova.apache.org/), [Neo4j](https://neo4j.com/), [Elgg](http://elgg.org), [Node.js](https://nodejs.org/en/), [MongoDB](https://www.mongodb.com/), [Redis](http://redis.io/), [WebRTC](https://webrtc.org/), [Socket.io](http://socket.io/), [TinyMCE](https://www.tinymce.com/), [Ionic](http://ionicframework.com/), [Requirejs](http://requirejs.org/), [OAuth](http://oauth.net/2/), [Apigen](http://www.apigen.org/), [Braintree](https://www.braintreepayments.com/). If any are missing please feel free to add.

___Copyright Minds 2012 - 2018___

Copyright for portions of Minds are held by [Elgg](http://elgg.org), 2013 as part of the [Elgg](http://elgg.org) project. All other copyright for Minds is held by Minds, Inc.
