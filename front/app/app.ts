import {Component, View, bootstrap} from 'angular2/angular2';
import {RouteConfig, Route, ROUTER_DIRECTIVES, ROUTER_BINDINGS} from 'angular2/router';
import {HTTP_BINDINGS} from 'angular2/http';

import {Topbar} from './src/components/topbar';
import {Navigation} from './src/components/navigation';

import {Login} from './src/controllers/login';
import {Logout} from './src/controllers/logout';
import {Register} from './src/controllers/register';
import {ComingSoon} from './src/controllers/comingsoon';
import {Newsfeed} from './src/controllers/newsfeed/newsfeed';
import {Capture} from './src/controllers/capture/capture';
import {Discovery} from './src/controllers/discovery/discovery';
import {Channel, ChannelSubscribers, ChannelSubscriptions} from './src/controllers/channels/channel';
import {Notifications} from './src/controllers/notifications/notifications';

/**
 * TODO: Load these automagically from gulp
 */
import {Gatherings} from './src/plugins/gatherings/gatherings';
import {Blog, BlogView, BlogEdit} from './src/plugins/blog/blog';
import {Groups, GroupsProfile, GroupsCreator} from './src/plugins/groups/groups';
import {Wallet} from './src/plugins/payments/payments';

@Component({
  selector: 'minds-app',
})
@RouteConfig([
  { path: '/login', component: Login, as: 'login' },
  { path: '/logout', component: Logout, as: 'logout' },
  { path: '/register', component: Register, as: 'register' },

  { path: '/newsfeed', component: Newsfeed, as: 'newsfeed' },
  { path: '/capture', component: Capture, as: 'capture' },

  { path: '/discovery/:filter', component: Discovery, as: 'discovery'},
  { path: '/discovery/:filter/:type', component: Discovery, as: 'discovery'},

  { path: '/messenger', component:  Gatherings, as: 'messenger'},
  { path: '/messenger/:guid', component:  Gatherings, as: 'messenger-conversation'},

  { path: '/blog/:filter', component:  Blog, as: 'blog'},
  { path: '/blog/view/:guid', component:  BlogView, as: 'blog-view'},
  { path: '/blog/edit/:guid', component:  BlogEdit, as: 'blog-edit'},

  { path: '/notifications', component: Notifications, as: 'notifications'},

  { path: '/groups/:filter', component: Groups, as: 'groups'},
  { path: '/groups/create', component: GroupsCreator, as: 'groups-create'},
  { path: '/groups/profile/:guid', component: GroupsProfile, as: 'groups-profile'},
  { path: '/groups/profile/:guid/:filter', component: GroupsProfile, as: 'groups-profile'},

  { path: '/wallet', component: Wallet, as: 'wallet'},

  { path: '/:username', component: Channel, as: 'channel' },
  { path: '/:username/:filter', component: Channel, as: 'channel-filter' },

  { path: '/', redirectTo: '/newsfeed' }
])
@View({
  templateUrl: './templates/index.html',
  directives: [Topbar, Navigation, ROUTER_DIRECTIVES]
})

export class Minds {
  name: string;

  constructor() {
    this.name = 'Minds';
  }
}

bootstrap(Minds, [ROUTER_BINDINGS, HTTP_BINDINGS]);
