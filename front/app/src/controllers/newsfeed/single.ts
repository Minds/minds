import { Component, View, CORE_DIRECTIVES, FORM_DIRECTIVES} from 'angular2/angular2';
import { Router, RouteParams, ROUTER_DIRECTIVES } from 'angular2/router';
import { Client, Upload } from 'src/services/api';
import { Material } from 'src/directives/material';
import { InfiniteScroll } from '../../directives/infinite-scroll';
import { Poster } from './poster';
import { Activity } from './activity';
import { MindsActivityObject } from 'src/interfaces/entities';
import { SessionFactory } from 'src/services/session';

import { GraphImpressions } from 'src/components/graphs/impressions';

@Component({
  selector: 'minds-newsfeed-single',
  viewBindings: [ Client, Upload ],
//  inputs: [ "prepend" ]
})
@View({
  templateUrl: 'templates/newsfeed/single.html',
  directives: [ Poster, Activity, Material, CORE_DIRECTIVES, FORM_DIRECTIVES, ROUTER_DIRECTIVES,
    InfiniteScroll, GraphImpressions ]
})

export class NewsfeedSingle {


  session = SessionFactory.build();
  minds;
  inProgress : boolean = false;
  activity : MindsActivityObject;


	constructor(public client: Client, public upload: Upload, public router: Router, public params: RouteParams){
    if(params.params['guid'])
      this.load(params.params['guid']);
	}

	/**
	 * Load newsfeed
	 */
	load(guid : string){
		var self = this;
		this.client.get('api/v1/newsfeed/single/' + guid, { }, {cache: true})
				.then((data : MindsActivityObject) => {
					self.activity = data.activity;
				})
				.catch(function(e){
					self.inProgress = false;
				});
	}

  delete(activity){
    this.router.navigate(['/Newsfeed']);
  }
}
