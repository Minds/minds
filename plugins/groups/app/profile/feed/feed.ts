import { Component, View, CORE_DIRECTIVES, Observable, Inject, FORM_DIRECTIVES} from 'angular2/angular2';
import { RouterLink, RouteParams } from "angular2/router";

import { Client } from '../../../../services/api';
import { SessionFactory } from '../../../../services/session';
import { Material } from '../../../../directives/material';
import { InfiniteScroll } from '../../../../directives/infinite-scroll';

import { Poster } from '../../../../controllers/newsfeed/poster/poster';
import { CARDS } from '../../../../controllers/cards/cards';


interface MindsGroupResponse{
  group : MindsGroup
}
interface MindsGroup {
  guid : string,
  name : string,
  banner : boolean,
  members : Array<any>
}


@Component({
  selector: 'minds-groups-profile-feed',
  viewBindings: [ Client ],
  inputs: [ '_group: group' ]
})
@View({
  templateUrl: 'src/plugins/groups/profile/feed/feed.html',
  directives: [ CORE_DIRECTIVES, FORM_DIRECTIVES, Material, RouterLink, Poster, CARDS, InfiniteScroll ]
})

export class GroupsProfileFeed {

  guid;
  group : any;

  session = SessionFactory.build();

  activity : Array<any> = [];
  offset : string = "";
  inProgress : boolean = false;
  moreData : boolean = true;

	constructor(public client: Client){
	}

  set _group(value : any){
    this.group = value;
    this.guid = value.guid;
    this.load();
  }

  prepend(activity : any){
    this.activity.unshift(activity);
  }

  /**
   * Load a groups newsfeed
   */
  load(refresh : boolean = false){
    var self = this;

    if(this.inProgress)
      return false;

    if(refresh)
      this.offset = "";

    this.inProgress = true;
    this.client.get('api/v1/newsfeed/container/' + this.guid, { limit: 12, offset: this.offset })
      .then((response : any) => {
        if(!response.activity){
          self.moreData = false;
          self.inProgress = false;
          return false;
        }

        if(self.activity && !refresh){
          for(let activity of response.activity)
            self.activity.push(activity);
        } else {
             self.activity = response.activity;
        }
        self.offset = response['load-next'];
        self.inProgress = false;
      })
      .catch((e)=>{

      });
  }

}
