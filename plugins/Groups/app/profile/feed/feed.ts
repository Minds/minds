import { Component, View, Inject } from 'angular2/core';
import { CORE_DIRECTIVES, FORM_DIRECTIVES } from 'angular2/common';
import { RouterLink, RouteParams } from "angular2/router";

import { GroupsService } from '../../groups-service';

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
  bindings: [ GroupsService ],
  inputs: [ '_group: group' ]
})
@View({
  templateUrl: 'src/plugins/Groups/profile/feed/feed.html',
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

	constructor(public service: GroupsService){
	}

  set _group(value : any){
    this.group = value;
    this.guid = value.guid;
    this.load(true);
  }

  prepend(activity : any){
    this.activity.unshift(activity);
  }

  /**
   * Load a groups newsfeed
   */
  load(refresh : boolean = false){
    this.service.infiniteList(this, {
      url: `api/v1/newsfeed/container/${this.guid}`,
      refresh,
      collection: 'activity',
      query: {
        limit: 12
      }
    });
  }

}
