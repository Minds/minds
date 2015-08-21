/// <reference path="../typings/tsd.d.ts" />
import {Component, View, bootstrap} from 'angular2/angular2';
import {RouteConfig, RouterOutlet, RouterLink, Route, ROUTER_BINDINGS} from 'angular2/router';
import {HTTP_BINDINGS} from 'http/http';

import {Topbar} from './src/components/topbar';
import {Navigation} from './src/components/navigation';

import {Login} from './src/controllers/login';
import {Logout} from './src/controllers/logout';
import {ComingSoon} from './src/controllers/comingsoon';
import {Newsfeed} from './src/controllers/newsfeed/newsfeed';
import {Capture} from './src/controllers/capture/capture';
import {Discovery} from './src/controllers/discovery/discovery';
import {Channel} from './src/controllers/channels/channel';
import {Notifications} from './src/controllers/notifications/notifications';

/**
 * TODO: Load these automagically from gulp
 */
import {Gatherings} from './src/plugins/gatherings/gatherings';
import {Groups, GroupsProfile, GroupsCreator} from './src/plugins/groups/groups';

@Component({
  selector: 'minds-app',
})
@RouteConfig([
  new Route({ path: '/login', component: Login, as: 'login' }),
  { path: '/logout', component: Logout, as: 'logout' },
  { path: '/newsfeed', component: Newsfeed, as: 'newsfeed' },
  { path: '/capture', component: Capture, as: 'capture' },

  { path: '/discovery/:filter', component: Discovery, as: 'discovery'},
  { path: '/discovery/:filter/:type', component: Discovery, as: 'discovery'},

  { path: '/messenger', component:  Gatherings, as: 'messenger'},

  { path: '/notifications', component: Notifications, as: 'notifications'},

  { path: '/groups/:filter', component: Groups, as: 'groups'},
  { path: '/groups/create', component: GroupsCreator, as: 'groups-create'},
  { path: '/groups/profile/:guid', component: GroupsProfile, as: 'groups-profile'},


  { path: '/:username', component: Channel, as: 'channel' },

  { path: '/', redirectTo: '/newsfeed' }
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

bootstrap(Minds, [ROUTER_BINDINGS, HTTP_BINDINGS]);
