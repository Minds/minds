import { Component, View, CORE_DIRECTIVES, FORM_DIRECTIVES, Inject } from 'angular2/angular2';
import { Router, ROUTER_DIRECTIVES, RouteParams } from 'angular2/router';
import { Client, Upload } from 'src/services/api';
import { MindsTitle } from 'src/services/ux/title';
import { Material } from 'src/directives/material';
import { SessionFactory } from 'src/services/session';
import { ScrollFactory } from 'src/services/ux/scroll';
import { InfiniteScroll } from 'src/directives/infinite-scroll';
import { BUTTON_COMPONENTS } from 'src/components/buttons';
import { MindsCarousel } from 'src/components/carousel';
import { TagsPipe } from 'src/pipes/tags';

import { AutoGrow } from 'src/directives/autogrow';
import { CARDS } from 'src/controllers/cards/cards';
import { MindsActivityObject } from 'src/interfaces/entities';
import { MindsUser } from 'src/interfaces/entities';
import { MindsChannelResponse } from 'src/interfaces/responses';
import { Poster } from 'src/controllers/newsfeed/poster';
import { MindsAvatar } from 'src/components/avatar';

import { ChannelSubscribers } from './subscribers';
import { ChannelSubscriptions } from './subscriptions';
import { ChannelEdit } from './edit';

@Component({
  selector: 'minds-channel',
  viewBindings: [ Client, Upload ],
  bindings: [ MindsTitle ]
})
@View({
  templateUrl: 'templates/channels/channel.html',
  pipes: [ TagsPipe ],
  directives: [ ROUTER_DIRECTIVES, CORE_DIRECTIVES, FORM_DIRECTIVES, Material, InfiniteScroll, CARDS,
    AutoGrow, ChannelSubscribers, ChannelSubscriptions, BUTTON_COMPONENTS, ChannelEdit, MindsCarousel, Poster, MindsAvatar ]
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

  constructor(public client: Client, public upload: Upload, params: RouteParams, public title: MindsTitle){
      this.username = params.params['username'];
      if(params.params['filter'])
        this._filter = params.params['filter'];

      this.title.setTitle("Channel");
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
      this.title.setTitle(self.user.username);

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
    this.client.get('api/v1/entities/owner/all/'+ this.user.guid, {limit:9, offset:""})
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
    if(this.editing){
      this.update();
    }
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

    if(!value.length)
      return;
    for(var banner of value){
      var options : any = { top: banner.top };
      if(banner.guid)
        options.guid = banner.guid;
      this.upload.post('api/v1/channel/carousel', [banner.file], options)
        .then((response : any) => {
          response.index = banner.index;
          this.user.carousels[banner.index] = response.carousel;
        });
    }

  }

  removeCarousel(value : any){
    if(value.guid)
      this.client.delete('api/v1/channel/carousel/' + value.guid);
  }

  update(){
    var self = this;
    this.client.post('api/v1/channel/info', this.user)
      .then((data : any) => {
        self.editing = false;
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

  upload_avatar(file){
    var self = this;
    this.upload.post('api/v1/channel/avatar', [file], {filekey : 'file'})
      .then((response : any) => {
        self.user.icontime = Date.now();
        window.Minds.user.icontime = Date.now();
      })
      .catch((exception)=>{
      });
  }

}

export { ChannelSubscribers } from './subscribers';
export { ChannelSubscriptions } from './subscriptions';
export { ChannelEdit } from './edit';
