import { Component, View, CORE_DIRECTIVES, FORM_DIRECTIVES, Inject } from 'angular2/angular2';
import { Router, ROUTER_DIRECTIVES, RouteParams } from 'angular2/router';
import { Client } from 'src/services/api';
import { Material } from 'src/directives/material';
import { SessionFactory } from 'src/services/session';
import { InfiniteScroll } from 'src/directives/infinite-scroll';
import { SubscribeButton } from 'src/directives/subscribe-button';
import { AutoGrow } from 'src/directives/autogrow';
import { Activity } from 'src/controllers/newsfeed/activity';
import { MindsActivityObject } from 'src/interfaces/entities';
import { MindsUser } from 'src/interfaces/entities';
import { MindsChannelResponse } from 'src/interfaces/responses';

import { ChannelSubscribers } from './subscribers';
import { ChannelSubscriptions } from './subscriptions';

@Component({
  selector: 'minds-channel',
  viewBindings: [ Client ]
})
@View({
  templateUrl: 'templates/channels/channel.html',
  directives: [ ROUTER_DIRECTIVES, CORE_DIRECTIVES, FORM_DIRECTIVES, Material, InfiniteScroll, Activity, AutoGrow, ChannelSubscribers, ChannelSubscriptions, SubscribeButton ]
})

export class Channel {

  _filter : string = "feed";
  session = SessionFactory.build();
  username : string;
  user : MindsUser;
  feed : Array<Object> = [];
  offset : string = "";
  moreData : boolean = true;
  inProgress : boolean = false;
  editing : string = "";
  error: string = "";


  constructor(public client: Client, params: RouteParams){
      this.username = params.params['username'];
      if(params.params['filter'])
        this._filter = params.params['filter'];
      this.load();
  }

  load(){
    var self = this;
    this.client.get('api/v1/channel/' + this.username, {})
              .then((data : MindsChannelResponse) => {
                if(data.status != "success"){
                  self.error = data.message;
                  return false;
                }
                self.user = data.channel;
                if(self._filter == "feed")
                  self.loadFeed(true);
                })
              .catch((e) => {
                console.log('couldnt load channel', e);
                });
  }

  loadFeed(refresh : boolean = false){
    var self = this;
    if(this.inProgress){
      //console.log('already loading more..');
      return false;
    }

    if(refresh){
      this.offset = "";
    }

    this.inProgress = true;

    this.client.get('api/v1/newsfeed/personal/' + this.user.guid, {limit:12, offset: this.offset}, {cache: true})
        .then((data : MindsActivityObject) => {
          if(!data.activity){
            self.moreData = false;
            self.inProgress = false;
            return false;
          }
          if(self.feed && !refresh){
            for(let activity of data.activity)
              self.feed.push(activity);
          } else {
               self.feed = data.activity;
          }
          self.offset = data['load-next'];
          self.inProgress = false;
        })
        .catch(function(e){
          console.log(e);
        });
  }

  isOwner(){
    return this.session.getLoggedInUser().guid == this.user.guid;
  }

  toggleEditing(section : string){
    if(this.editing == section)
      this.editing = "";
    else
      this.editing = section;
  }

  updateField(field : string){
    if(!field)
      return false;

    var self = this;
    let data = {};
    data[field] = this.user[field];
    this.client.post('api/v1/channel/info', data)
              .then((data : any) => {
                if(data.status != "success"){
                  alert('error saving');
                  return false;
                }
                self.editing = "";
                });
  }

}

export { ChannelSubscribers } from './subscribers';
export { ChannelSubscriptions } from './subscriptions';
