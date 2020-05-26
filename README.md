Minds
=====
Minds is an open-source, encrypted and reward-based social networking platform. https://minds.com

## Docs
Full documentation can be found at [https://developers.minds.com/](https://developers.minds.com/)

## Repositories

Minds is split into multiple repositories:

- [Engine](https://gitlab.com/minds/engine) - Backend code & APIs
- [Front](https://gitlab.com/minds/front) - Client side Angular2 web app
- [Sockets](https://gitlab.com/minds/sockets) - WebSocket server for real-time communication
- [Mobile](https://gitlab.com/minds/mobile-native) - React Native mobile apps


## Development System Requirements

- > 10GB RAM (be sure to set it in your docker settings)
- > 100GB Disk space
- [Docker Compose](https://docs.docker.com/compose/)

## Development Installation

[See our local setup guide](local/README.md)

## Troubleshooting

The local setup guide sets up the tools to both run and rebuild the stack.

### Is everything running?
```minds up```

### Needs a kick?
```minds restart```

### When in doubt, re-run the local stack installation. 
```minds install```

## Production System Requirements

At this time it is not advisable to run Minds in production, however it is possible so long as you are aware of the risks.

- 3 Cassandra Nodes (Min 30gb RAM, 1TB SSD, 8 CPU)
- 1 ElasticSearch Node (Min 16GB RAM, 250GB SSD, 8 CPU) #2 nodes are recommended for failover
- 1 Docker Machine (Min 60gb RAM, 50GB SSD, 32 CPU)


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
