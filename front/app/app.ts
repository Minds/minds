/// <reference path="../typings/tsd.d.ts" />
import {Component, View, bootstrap, httpInjectables} from 'angular2/angular2';
import {RouteConfig, RouterOutlet, RouterLink, routerInjectables} from 'angular2/router';

import {Topbar} from './src/components/topbar';
import {Navigation} from './src/components/navigation';

import {Login} from './src/controllers/login';
import {Newsfeed} from './src/controllers/newsfeed';
import {Capture} from './src/controllers/capture/capture';

@Component({
  selector: 'minds-app',
})
@RouteConfig([
  { path: '/login', component: Login, as: 'login' },
  { path: '/newsfeed', component: Newsfeed, as: 'newsfeed' },
  { path: '/capture', component: Capture, as: 'capture' },
  { path: '/:username', redirectTo: '/login' }
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