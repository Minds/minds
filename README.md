Minds
==========

## Introduction
An open-source, encrypted and reward-based social networking platform. https://minds.com | https://minds.org

## Security reports
Please report all security issues to security@minds.com.

## License

AGPLv3. Please see the license file of each repository.

## Repositories

Minds is split into multiple components:

- [Engine](https://github.com/Minds/engine) - Backend code & APIs
- [Front](https://github.com/Minds/front) - Client side Angular2 web app
- [Sockets](https://github.com/Minds/sockets) - WebSocket server for real-time communication (WIP)
- [Docs](https://github.com/Minds/docs) - Documentation of public and private apis (WIP)

Please also see:
- [Mobile](https://github.com/Minds/mobile) - WebSocket server for real-time communication

Plugins will eventually be migrated to their own repositories.

## Download and Provisioning

- Git
- Vagrant

Clone this repository and run `init.sh` (or `init.bat` if you're on Windows) to clone submodules. Run
`vagrant up` to create, provision and run the VM.

## Installing and Building Developer version

- NodeJS >= 4
- NPM >= 3
- Grunt CLI

On `front` directory:
- `npm install`
- `gulp build`
- `gulp build.index`

## Building Prodution version
- NodeJS >= 4
- NPM >= 3

On `front` directory:
- `npm install`
- `npm run build`

## Testing

- `gulp test`

## Credits

[PHP](https://php.net), [Cassandra](http://cassandra.apache.org/), [Angular2](http://angular.io), [Nginx](https://nginx.com), [Ubuntu](https://ubuntu.com), [OpenSSL](https://www.openssl.org/), [RabbitMQ](https://www.rabbitmq.com/), [Elasticsearch](https://www.elastic.co/), [Cordova](https://cordova.apache.org/), [Neo4j](https://neo4j.com/), [Elgg](http://elgg.org), [Node.js](https://nodejs.org/en/), [MongoDB](https://www.mongodb.com/), [Redis](http://redis.io/), [WebRTC](https://webrtc.org/), [Socket.io](http://socket.io/), [TinyMCE](https://www.tinymce.com/), [Ionic](http://ionicframework.com/), [Requirejs](http://requirejs.org/), [OAuth](http://oauth.net/2/), [Apigen](http://www.apigen.org/), [Braintree](https://www.braintreepayments.com/). If any are missing please feel free to add.

___Copyright Minds 2012 - 2016___

Copyright for portions of Minds are held by [Elgg](http://elgg.org), 2013 as part of the [Elgg](http://elgg.org) project. All other copyright for Minds is held by Minds, Inc.
