import { Component, View, NgFor, NgIf, Inject, FORM_DIRECTIVES} from 'angular2/angular2';
import { Router, RouteParams } from 'angular2/router';
import { Client } from 'src/services/api';
import { Material } from 'src/directives/material';
import { SessionFactory } from '../../services/session';
import { InfiniteScroll } from '../../directives/infinite-scroll';
import { AutoGrow } from '../../directives/autogrow';
import { Activity } from 'src/controllers/newsfeed/activity';

import { ChannelSubscribers } from './subscribers';
import { ChannelSubscriptions } from './subscriptions';

@Component({
  selector: 'minds-channel',
  viewBindings: [ Client ]
})
@View({
  templateUrl: 'templates/channels/channel.html',
  directives: [ NgFor, NgIf, Material, FORM_DIRECTIVES, InfiniteScroll, Activity, AutoGrow, ChannelSubscribers, ChannelSubscriptions ]
})


export class Channel {

  _filter : string = "feed";
  session = SessionFactory.build();
  username : string;
  user : Object;
  feed : Array<Object> = [];
  offset : string = "";
  moreData : boolean = true;
  inProgress : boolean = false;
  editing : string = "";
  error: string = "";

  constructor(public client: Client,
    @Inject(Router) public router: Router,
    @Inject(RouteParams) public params: RouteParams
    ){
      this.username = params.params['username'];
      if(params.params['filter'])
        this._filter = params.params['filter'];
      this.load();
  }

  load(){
    var self = this;
    this.client.get('api/v1/channel/' + this.username, {})
              .then((data : Array<any>) => {
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
