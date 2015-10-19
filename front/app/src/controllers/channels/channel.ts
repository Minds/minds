import { Component, View, CORE_DIRECTIVES, FORM_DIRECTIVES, Inject } from 'angular2/angular2';
import { Router, ROUTER_DIRECTIVES, RouteParams } from 'angular2/router';
import { Client, Upload } from 'src/services/api';
import { Material } from 'src/directives/material';
import { SessionFactory } from 'src/services/session';
import { ScrollFactory } from 'src/services/ux/scroll';
import { InfiniteScroll } from 'src/directives/infinite-scroll';
import { BUTTON_COMPONENTS } from 'src/components/buttons';
import { MindsCarousel } from 'src/components/carousel';

import { AutoGrow } from 'src/directives/autogrow';
import { Activity } from 'src/controllers/newsfeed/activity';
import { MindsActivityObject } from 'src/interfaces/entities';
import { MindsUser } from 'src/interfaces/entities';
import { MindsChannelResponse } from 'src/interfaces/responses';
import { Poster } from 'src/controllers/newsfeed/poster';

import { ChannelSubscribers } from './subscribers';
import { ChannelSubscriptions } from './subscriptions';
import { ChannelEdit } from './edit';

@Component({
  selector: 'minds-channel',
  viewBindings: [ Client, Upload ]
})
@View({
  templateUrl: 'templates/channels/channel.html',
  directives: [ ROUTER_DIRECTIVES, CORE_DIRECTIVES, FORM_DIRECTIVES, Material, InfiniteScroll, Activity,
    AutoGrow, ChannelSubscribers, ChannelSubscriptions, BUTTON_COMPONENTS, ChannelEdit, MindsCarousel, Poster ]
})

export class Channel {

  _filter : string = "feed";
  session = SessionFactory.build();
  scroll = ScrollFactory.build();
  isLocked : boolean = false;

  username : string;
  user : MindsUser;
  feed : Array<Object> = [];
  offset : string = "";
  moreData : boolean = true;
  inProgress : boolean = false;
  editing : boolean = false
  error: string = "";
  media : Array<Object> = [];
  blogs : Array<Object> = [];
  isLoadingMedia : boolean = false;
  isLoadingBlogs : boolean = false;

  constructor(public client: Client, public upload: Upload, params: RouteParams){
      this.username = params.params['username'];
      if(params.params['filter'])
        this._filter = params.params['filter'];
      this.load();
      this.onScroll();
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
      self.loadMedia();
      self.loadBlogs();
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
    this.isLoadingMedia = true;
    this.isLoadingBlogs = true;

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
          self.inProgress = false;
        });
  }

  loadMedia(){
    var self = this;
    this.client.get('api/v1/entities/owner/'+ this.user.guid, {limit:9, offset:""})
    .then((data : any) => {
      if(!data.entities)
      return false;

      self.media = data.entities;
      self.isLoadingMedia = false;
    })
    .catch(function(e){
      self.isLoadingMedia = false;
    });
  }

  loadBlogs(){
    var self = this;
    this.client.get('api/v1/blog/owner/' + self.user.guid, { limit: 5, offset: ""})
    .then((data : any) => {
      if(!data.blogs)
      return false;

      self.blogs = data.blogs;
      self.isLoadingBlogs = false;
    })
    .catch(function(e){
      self.isLoadingBlogs = false;
    });
  }

  isOwner(){
    return this.session.getLoggedInUser().guid == this.user.guid;
  }

  toggleEditing(){
    this.editing = !this.editing;
  }

  onScroll(){
    var listen = this.scroll.listen((view) => {
      if(view.top > 250)
        this.isLocked = true;
      if(view.top < 250)
        this.isLocked = false;
    });
  }

  updateCarousels(value : any){
    console.log('carousel editing done', value);
    for(var banner of value){
      var options : any = { top: banner.top };
      if(banner.guid)
        options.guid = banner.guid;
      this.upload.post('api/v1/channel/carousel', [banner.file], options);
    }
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

  delete(activity){
    for(var i in this.feed){
      if(this.feed[i] == activity)
        this.feed.splice(i,1);
    }
  }

  prepend(activity : any){
    this.feed.unshift(activity);
  }

}

export { ChannelSubscribers } from './subscribers';
export { ChannelSubscriptions } from './subscriptions';
export { ChannelEdit } from './edit';
