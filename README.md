Minds
==========

___Copyright (c) Copyleft 2008-2016___


[![Sauce Test Status](https://saucelabs.com/browser-matrix/minds.svg?auth=240a8242ffaed2a145f48323ab0762f9)](https://saucelabs.com/u/minds)

## Introduction
Minds is the free and open-source social networking platform.

## License

Please see the license file of each repository.

## Repositories

Minds is split into multiple components:

- [Engine](https://github.com/Minds/engine) - Backend code & APIs
- [Front](https://github.com/Minds/front) - Client side Angular2 web app
- [Sockets](https://github.com/Minds/sockets) - WebSocket server for real-time communication
- [Docs](https://github.com/Minds/docs) - Documentation of public and private apis (work in progress)

Please also see:
- [Mobile](https://github.com/Minds/mobile) - WebSocket server for real-time communication

Plugins will eventually be migrated to their own repositories.

## Setup

- `npm install -g gulp`
- `npm install`
- `gulp init`

If that fails then try running:

- `gulp install`

## Building

- `gulp build`

## Testing

- `gulp test`
