import { Component, View, CORE_DIRECTIVES, FORM_DIRECTIVES} from 'angular2/angular2';
import { Router, RouteParams, Location, ROUTER_DIRECTIVES } from 'angular2/router';
import { Client, Upload } from 'src/services/api';
import { Material } from 'src/directives/material';
import { InfiniteScroll } from '../../directives/infinite-scroll';
import { CARDS } from 'src/controllers/cards/cards';
import { BlogCard } from 'src/plugins/blog/blog-card';

@Component({
  selector: 'minds-search',
  viewBindings: [ Client ]
})
@View({
  templateUrl: 'templates/search/list.html',
  directives: [ CORE_DIRECTIVES, Material, FORM_DIRECTIVES, ROUTER_DIRECTIVES,
    CARDS, BlogCard, InfiniteScroll ]
})

export class Search {

  q : string = "";
  type : string = "";

  entities: Array<Object> = [];
  offset : string = "";
  inProgress : boolean = false;
  moreData : boolean = true;

  constructor(public client: Client, public params : RouteParams){
    this.q = params.params['q'];
    if(params.params['type'])
      this.type = params.params['type'];
  	this.search();
  }

  /**
   * Search
   */
   search(refresh : boolean = true){
     var self = this;
     this.inProgress = true;
     this.client.get('api/v1/search', { q: this.q, type: this.type, limit: 12 })
      .then((response: any) => {
          self.entities = response.entities;
          self.inProgress = false;
      })
      .catch((e) => {

      });
   }
}
