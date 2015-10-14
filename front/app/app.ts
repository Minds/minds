import {Component, View, provide, bootstrap} from 'angular2/angular2';
import {RouteConfig, Route, ROUTER_DIRECTIVES, ROUTER_PROVIDERS, ROUTER_PRIMARY_COMPONENT} from 'angular2/router';
import {HTTP_PROVIDERS} from 'angular2/http';

import {Topbar} from './src/components/topbar';
import {SidebarNavigation} from './src/components/sidebar-navigation';

import { Homepage } from 'src/controllers/homepage';
import {Login} from './src/controllers/login';
import {Logout} from './src/controllers/logout';
import {Register} from './src/controllers/register';
import {ForgotPassword} from './src/controllers/forgot-password';
import {ComingSoon} from './src/controllers/comingsoon';
import {Newsfeed, NewsfeedSingle} from './src/controllers/newsfeed/newsfeed';
import {Capture} from './src/controllers/capture/capture';
import {Discovery} from './src/controllers/discovery/discovery';
import {Channel, ChannelSubscribers, ChannelSubscriptions, ChannelEdit} from './src/controllers/channels/channel';
import {Notifications} from './src/controllers/notifications/notifications';
import {Search} from './src/controllers/search/search';
import {Wallet} from './src/controllers/wallet/wallet';
import {Settings} from './src/controllers/settings/settings';
import {AdminAnalytics} from './src/controllers/admin/analytics';

/**
 * TODO: Load these automagically from gulp
 */
import {Gatherings} from './src/plugins/gatherings/gatherings';
import {Blog, BlogView, BlogEdit} from './src/plugins/blog/blog';
import {ArchiveView, ArchiveEdit} from './src/plugins/archive/archive';
import {Groups, GroupsProfile, GroupsCreator} from './src/plugins/groups/groups';

@Component({
  selector: 'minds-app',
})
@RouteConfig([
  { path: '/login', component: Login, as: 'Login' },
  { path: '/logout', component: Logout, as: 'Logout' },
  { path: '/register', component: Register, as: 'Register' },
  { path: '/forgot-password', component: ForgotPassword, as: 'Forgot-Password' },

  { path: '/newsfeed', component: Newsfeed, as: 'Newsfeed' },
  { path: '/newsfeed/:guid', component: NewsfeedSingle, as: 'Activity' },
  { path: '/capture', component: Capture, as: 'Capture' },

  { path: '/discovery/:filter', component: Discovery, as: 'Discovery'},
  { path: '/discovery/:filter/:type', component: Discovery, as: 'Discovery'},

  { path: '/messenger', component:  Gatherings, as: 'Messenger'},
  { path: '/messenger/:guid', component:  Gatherings, as: 'Messenger-Conversation'},

  { path: '/blog/:filter', component:  Blog, as: 'Blog'},
  { path: '/blog/view/:guid', component:  BlogView, as: 'Blog-View'},
  { path: '/blog/edit/:guid', component:  BlogEdit, as: 'Blog-Edit'},

  { path: '/archive/view/:guid', component: ArchiveView, as: 'Archive-View'},
  { path: '/archive/edit/:guid', component: ArchiveEdit, as: 'Archive-Edit'},

  { path: '/notifications', component: Notifications, as: 'Notifications'},

  { path: '/groups/:filter', component: Groups, as: 'Groups'},
  { path: '/groups/create', component: GroupsCreator, as: 'Groups-Create'},
  { path: '/groups/profile/:guid', component: GroupsProfile, as: 'Groups-Profile'},
  { path: '/groups/profile/:guid/:filter', component: GroupsProfile, as: 'Groups-Profile'},

  { path: '/wallet', component: Wallet, as: 'Wallet'},
  { path: '/wallet/:filter', component: Wallet, as: 'Wallet-Filter'},

  { path: '/search', component: Search, as: 'Search' },

  { path: '/:username', component: Channel, as: 'Channel' },
  { path: '/:username/:filter', component: Channel, as: 'Channel-Filter' },

  { path: '/settings/:filter', component: Settings, as: 'Settings' },

  { path: '/admin/:filter', component: AdminAnalytics, as: 'Admin' },

  { path: '/', component: Homepage, as: 'Homepage' }

])
@View({
  templateUrl: './templates/index.html',
  directives: [Topbar, SidebarNavigation, ROUTER_DIRECTIVES]
})

export class Minds {
  name: string;

  constructor() {
    this.name = 'Minds';
  }

}

bootstrap(Minds, [ROUTER_PROVIDERS, provide(ROUTER_PRIMARY_COMPONENT, {useValue:Minds}), HTTP_PROVIDERS]);
