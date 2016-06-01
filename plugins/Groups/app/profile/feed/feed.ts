import { Component, Inject } from 'angular2/core';
import { CORE_DIRECTIVES, FORM_DIRECTIVES } from 'angular2/common';
import { RouterLink, RouteParams } from "angular2/router";

import { GroupsService } from '../../groups-service';

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
  bindings: [ GroupsService ],
  inputs: [ '_group: group' ],
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

	constructor(public client : Client, public service: GroupsService){
	}

  set _group(value : any){
    this.group = value;
    this.guid = value.guid;
    this.load(true);
    this.setUpPoll();
  }

  pollingTimer: any;
  pollingOffset: string = '';
  pollingNewPosts: number = 0;

  setUpPoll() {
    this.pollingTimer = setInterval(() => {
      this.client.get('api/v1/newsfeed/count/container/' + this.guid, { offset: this.pollingOffset }, {cache: true})
        .then((response: any) => {
          if (typeof response.count === 'undefined') {
            return;
          }

          this.pollingNewPosts += response.count;
          this.pollingOffset = response['load-next'];
        })
        .catch(e => { console.error('Newsfeed polling', e); });
    }, 60000);
  }

  pollingLoadNew() {
    this.load(true);
  }

  ngOnDestroy() {
    clearInterval(this.pollingTimer);
  }

  prepend(activity : any){
    this.activity.unshift(activity);
    this.pollingOffset = activity.guid;
  }

  /**
   * Load a groups newsfeed
   */
  load(refresh : boolean = false){
    if(this.inProgress)
      return false;

    if(refresh) {
      this.offset = "";
      this.pollingOffset = '';
      this.pollingNewPosts = 0;
    }

    this.inProgress = true;
    this.client.get('api/v1/newsfeed/container/' + this.guid, { limit: 12, offset: this.offset })
      .then((response : any) => {
        if(!response.activity){
          this.moreData = false;
          this.inProgress = false;
          return false;
        }

        if(this.activity && !refresh){
          for(let activity of response.activity)
            this.activity.push(activity);
        } else {
             this.activity = response.activity;

             if (typeof response.activity[0] !== 'undefined') {
               this.pollingOffset = response.activity[0].guid;
             }
        }
        this.offset = response['load-next'];
        this.inProgress = false;
      });
  }

}
