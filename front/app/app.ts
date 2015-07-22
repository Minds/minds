/// <reference path="../typings/tsd.d.ts" />
import {Component, View, bootstrap, httpInjectables} from 'angular2/angular2';
import {RouteConfig, RouterOutlet, RouterLink, routerInjectables} from 'angular2/router';

import {Topbar} from './src/components/topbar';
import {Navigation} from './src/components/navigation';

import {Login} from './src/controllers/login';
import {Logout} from './src/controllers/logout';
import {ComingSoon} from './src/controllers/comingsoon';
import {Newsfeed} from './src/controllers/newsfeed/newsfeed';
import {Capture} from './src/controllers/capture/capture';
import {Discovery} from './src/controllers/discovery/discovery';
import {Channel} from './src/controllers/channels/channel';
import {Gatherings} from './src/plugins/gatherings/gatherings';

@Component({
  selector: 'minds-app',
})
@RouteConfig([
  { path: '/login', component: Login, as: 'login' },
  { path: '/logout', component: Logout, as: 'logout' },
  { path: '/newsfeed', component: Newsfeed, as: 'newsfeed' },
  { path: '/capture', component: Capture, as: 'capture' },

  { path: '/discovery/:filter', component: Discovery, as: 'discovery'},
  { path: '/discovery/:filter/:type', component: Discovery, as: 'discovery'},

  { path: '/messenger', component:  Gatherings, as: 'messenger'},

  { path: '/notifications', component: ComingSoon, as: 'notifications'},
  { path: '/groups', component: ComingSoon, as: 'groups'},

  { path: '/:username', component: Channel, as: 'channel' }
])
@View({
  templateUrl: './templates/index.html',
  directives: [Topbar, Navigation, RouterOutlet, RouterLink]
})

class Minds {
  name: string;

  constructor() {
    this.name = 'Minds';
  }
}

bootstrap(Minds, [routerInjectables, httpInjectables]);
