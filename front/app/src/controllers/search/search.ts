import { Component, View, NgFor, NgIf, FORM_DIRECTIVES} from 'angular2/angular2';
import { Router, RouteParams } from 'angular2/router';
import { Client, Upload } from 'src/services/api';
import { Material } from 'src/directives/material';
import { InfiniteScroll } from '../../directives/infinite-scroll';
import { MindsActivityObject } from 'src/interfaces/entities';

@Component({
  selector: 'minds-search',
  viewBindings: [ Client ]
})
@View({
  templateUrl: 'templates/search/list.html',
  directives: [ NgFor, NgIf, Material, FORM_DIRECTIVES, InfiniteScroll ]
})

export class Search {

  q : string = "";

	entities: Array<Object> = [];
	offset : string = "";
  inProgress : boolean = false;
  moreData : boolean = true;

	constructor(public client: Client, public params : RouteParams){
    this.q = params.params['q'];
		this.search();
	}

	/**
	 * Search
	 */
   search(){
     var self = this;
     this.client.get('search', { q: this.q, limit: 12 })
      .then((response: any) => {

      })
      .catch((e) => {

      });
   }
}
