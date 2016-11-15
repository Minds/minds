import { Component, Inject } from '@angular/core';

import { GroupsService } from '../../groups-service';

import { Client } from '../../../../services/api';
import { SessionFactory } from '../../../../services/session';

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
  moduleId: module.id,
  selector: 'minds-groups-profile-feed',
  inputs: [ '_group: group' ],
  templateUrl: 'feed.html'
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
      this.client.get('api/v1/newsfeed/container/' + this.guid, { offset: this.pollingOffset, count: true }, {cache: true})
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

  delete(activity) {
    let i: any;
    for(i in this.activity){
      if(this.activity[i] == activity)
        this.activity.splice(i,1);
    }
  }
}
