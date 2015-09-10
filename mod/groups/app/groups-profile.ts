import { Component, View, CORE_DIRECTIVES, Observable, Inject, FORM_DIRECTIVES} from 'angular2/angular2';
import { RouterLink, RouteParams } from "angular2/router";

import { Client } from 'src/services/api';
import { SessionFactory } from 'src/services/session';
import { Material } from 'src/directives/material';
import { Activity } from 'src/controllers/newsfeed/activity';


interface MindsGroupResponse extends MindsResponse{
  group : MindsGroup
}
interface MindsGroup {
  guid : string,
  name : string,
  banner : boolean
}


@Component({
  selector: 'minds-groups-profile',
  viewBindings: [ Client ]
})
@View({
  templateUrl: 'templates/plugins/groups/profile.html',
  directives: [ CORE_DIRECTIVES, FORM_DIRECTIVES, Material, RouterLink, Activity ]
})

export class GroupsProfile {

  guid;
  group : MindsGroup;
  postMeta = {
    message: ''
  };
  offset : string = "";
  session = SessionFactory.build();

  activity : Array<any> = [];
  offset : string = "";
  inProgress : boolean = false;
  moreData : boolean = true;

	constructor(public client: Client,
    @Inject(RouteParams) public params: RouteParams
    ){
      this.guid = params.params['guid'];
      this.postMeta.container_guid = this.guid;
      this.load();
	}

  load(){
    var self = this;
    this.client.get('api/v1/groups/group/' + this.guid, {})
      .then((response : MindsGroupResponse) => {
          self.group = response.group;
          self.loadFeed();
      })
      .catch((e)=>{

      });
  }

  /**
   * Load a groups newsfeed
   */
  loadFeed(refresh : boolean = false){
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

  post(){
    console.log('posting', this.postMeta);
    var self = this;
		this.client.post('api/v1/newsfeed', this.postMeta)
				.then((data) => {
					self.loadFeed(true);
          console.log(data);
          //reset
          self.postMeta = {
            message: "",
            title: "",
            description: "",
            thumbnail: "",
            url: "",
            active: false
          }
				})
				.catch((e) => {
					console.log(e);
				});
  }

  /**
   * Get rich embed data
   */
  timeout;
  getPostPreview(message){
    var self = this;

    var match = message.value.match(/(\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/ig);
		if (!match) return;
    var url;

		if ( match instanceof Array) {
			url = match[0];
		} else {
			url = match;
		}

		if (!url.length) return;

		url = url.replace("http://", '');
		url = url.replace("https://", '');
    console.log('found url was ' + url)

    self.postMeta.active = true;

    if(this.timeout)
      clearTimeout(this.timeout);

    this.timeout = setTimeout(()=>{
      this.client.get('api/v1/newsfeed/preview', {url: url})
        .then((data : any) => {
          console.log(data);
          self.postMeta.title = data.meta.title;
          self.postMeta.url = data.meta.canonical;
          self.postMeta.description = data.meta.description;
          for (var link of data.links) {
              if (link.rel.indexOf('thumbnail') > -1) {
                  self.postMeta.thumbnail = link.href;
              }
          }
        });
    }, 600);
  }

}
