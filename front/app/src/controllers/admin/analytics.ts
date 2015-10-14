import { Component, View, CORE_DIRECTIVES, FORM_DIRECTIVES} from 'angular2/angular2';
import { Router, RouteParams, Location, ROUTER_DIRECTIVES } from 'angular2/router';
import { Client, Upload } from 'src/services/api';
import { MINDS_GRAPHS } from 'src/components/graphs';
import { Material } from 'src/directives/material';

@Component({
  selector: 'minds-admin-analytics',
  viewBindings: [ Client ]
})
@View({
  templateUrl: 'templates/admin/analytics.html',
  directives: [ CORE_DIRECTIVES, Material, FORM_DIRECTIVES, ROUTER_DIRECTIVES, MINDS_GRAPHS ]
})

export class AdminAnalytics {

  dam;
  mam;
  boost_newsfeed = {
    review: 0,
    approved: 0,
    percent: 50,
    total: 0
  };

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
    var self = this;
    this.client.get('api/v1/admin/analytics/boost')
      .then((response : any) => {
        self.boost_newsfeed = response.newsfeed;
        self.boost_newsfeed.total = self.boost_newsfeed.review + self.boost_newsfeed.approved;
        self.boost_newsfeed.percent = (self.boost_newsfeed.approved / self.boost_newsfeed.total) * 100;
      });
  }

}
