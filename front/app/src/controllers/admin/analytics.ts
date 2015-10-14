import { Component, View, CORE_DIRECTIVES, FORM_DIRECTIVES} from 'angular2/angular2';
import { Router, RouteParams, Location, ROUTER_DIRECTIVES } from 'angular2/router';
import { Client, Upload } from 'src/services/api';
import { LineGraph } from 'src/components/graphs/line-graph';
import { Material } from 'src/directives/material';

@Component({
  selector: 'minds-search',
  viewBindings: [ Client ]
})
@View({
  templateUrl: 'templates/admin/analytics.html',
  directives: [ CORE_DIRECTIVES, Material, FORM_DIRECTIVES, ROUTER_DIRECTIVES, LineGraph ]
})

export class AdminAnalytics {

  dam;
  mam;

  constructor(public client: Client, public params : RouteParams){
    this.getActives();
    this.getBoosts();
  }

  /**
   * Return active user analytics
   */
  getActives(){
    var self = this;
    this.client.get('api/v1/admin/analytics/active')
      .then((response : any) => {
        self.dam = response['daily'];
        self.mam = response['monthly'];
      });
  }

  /**
   * Return boost analytics
   */
  getBoosts(){

  }

}
