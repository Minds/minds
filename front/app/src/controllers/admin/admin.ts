import { Component, View, CORE_DIRECTIVES } from 'angular2/angular2';
import { Router, RouteParams, Location, ROUTER_DIRECTIVES } from 'angular2/router';
import { Client, Upload } from 'src/services/api';
import { Material } from 'src/directives/material';

import { AdminAnalytics } from './analytics';
import { AdminBoosts } from './boosts';

@Component({
  selector: 'minds-admin',
  viewBindings: [ Client ]
})
@View({
  template: `
    <minds-admin-analytics *ng-if="filter == 'analytics'"></minds-admin-analytics>
    <minds-admin-boosts *ng-if="filter == 'boosts'"></minds-admin-boosts>
  `,
  directives: [ CORE_DIRECTIVES, Material, ROUTER_DIRECTIVES, AdminAnalytics, AdminBoosts ]
})

export class Admin {

  filter : string = "";

  constructor(public params : RouteParams){
    if(params.params['filter'])
      this.filter = params.params['filter']
  }

}
