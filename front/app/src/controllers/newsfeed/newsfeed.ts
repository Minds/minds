import { Component, View, CORE_DIRECTIVES, FORM_DIRECTIVES} from 'angular2/angular2';
import { Router, ROUTER_DIRECTIVES } from 'angular2/router';
import { Client, Upload } from 'src/services/api';
import { Material } from 'src/directives/material';
import { InfiniteScroll } from '../../directives/infinite-scroll';
import { Poster } from './poster';
import { CARDS } from 'src/controllers/cards/cards';
import { MindsActivityObject } from 'src/interfaces/entities';
import { SessionFactory } from 'src/services/session';

import { AnalyticsImpressions } from 'src/components/analytics/impressions';

@Component({
  selector: 'minds-newsfeed',
  viewBindings: [ Client, Upload ]
})
@View({
  templateUrl: 'templates/newsfeed/list.html',
  directives: [ Poster, Material, CORE_DIRECTIVES, FORM_DIRECTIVES, ROUTER_DIRECTIVES,
    InfiniteScroll, AnalyticsImpressions, CARDS ]
})

export class Newsfeed {

	newsfeed : Array<Object> = [];
	offset : string = "";
  inProgress : boolean = false;
  moreData : boolean = true;
  session = SessionFactory.build();
  minds;

  attachment_preview;

  postMeta : any = {
    title: "",
    description: "",
    thumbnail: "",
    url: "",
    active: false,
    attachment_guid: null
  }

	constructor(public client: Client, public upload: Upload, public router: Router){
    if(!this.session.isLoggedIn()){
      router.navigate(['/Login']);
    } else {
  		this.load();
      this.minds = window.Minds;
    }
	}

	/**
	 * Load newsfeed
	 */
	load(refresh : boolean = false){
		var self = this;
    if(this.inProgress){
      //console.log('already loading more..');
      return false;
    }

    if(refresh){
      this.offset = "";
    }

    this.inProgress = true;

		this.client.get('api/v1/newsfeed', {limit:12, offset: this.offset}, {cache: true})
				.then((data : MindsActivityObject) => {
					if(!data.activity){
            self.moreData = false;
            self.inProgress = false;
						return false;
					}
          if(self.newsfeed && !refresh){
            self.newsfeed = self.newsfeed.concat(data.activity);
          } else {
            self.newsfeed = data.activity;
          }
					self.offset = data['load-next'];
          self.inProgress = false;
				})
				.catch(function(e){
					self.inProgress = false;
				});
	}

  prepend(activity : any){
    this.newsfeed.unshift(activity);
  }

  delete(activity){
    for(var i in this.newsfeed){
      if(this.newsfeed[i] == activity)
        this.newsfeed.splice(i,1);
    }
  }
}

export { NewsfeedSingle } from './single';
