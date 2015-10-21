import { Component, View, CORE_DIRECTIVES, Observable, Inject, FORM_DIRECTIVES} from 'angular2/angular2';
import { RouterLink, RouteParams } from "angular2/router";

import { Client, Upload } from 'src/services/api';
import { SessionFactory } from 'src/services/session';
import { MDL_DIRECTIVES } from 'src/directives/material';
import { CARDS } from 'src/controllers/cards/cards';
import { MindsBanner } from 'src/components/banner'
import { GroupsJoinButton } from './groups-join-button';
import { GroupsProfileMembers } from './profile/members';
import { GroupsProfileRequests } from './profile/requests';
import { GroupsProfileFeed } from './profile/feed';
import { GroupsProfileSettings } from './profile/settings';

interface MindsGroupResponse{
  group : MindsGroup
}
interface MindsGroup {
  guid : string,
  name : string,
  banner : boolean,
  banner_position : number,
  members : Array<any>
}


@Component({
  selector: 'minds-groups-profile',
  viewBindings: [ Client, Upload ]
})
@View({
  templateUrl: 'templates/plugins/groups/profile.html',
  directives: [ CORE_DIRECTIVES, FORM_DIRECTIVES, MDL_DIRECTIVES, RouterLink, CARDS, GroupsJoinButton,
    GroupsProfileMembers, GroupsProfileFeed, GroupsProfileSettings, GroupsProfileRequests, MindsBanner ]
})

export class GroupsProfile {

  guid;
  filter = "activity";
  group : MindsGroup;
  postMeta : any = {
    message: '',
    container_guid: 0
  };
  editing : boolean = false;
  session = SessionFactory.build();

  activity : Array<any> = [];
  offset : string = "";
  inProgress : boolean = false;
  moreData : boolean = true;

	constructor(public client: Client, public upload: Upload, public params: RouteParams){
      this.guid = params.params['guid'];
      if(params.params['filter'])
        this.filter = params.params['filter'];
      this.postMeta.container_guid = this.guid;
      this.load();
	}

  load(){
    var self = this;
    this.client.get('api/v1/groups/group/' + this.guid, {})
      .then((response : MindsGroupResponse) => {
          self.group = response.group;
      })
      .catch((e)=>{

      });
  }

  save(){
    var self = this;
    this.client.post('api/v1/groups/group/' + this.group.guid, {
        name: this.group.name
      })
      .then((response : any) => {

      })
      .catch((e) => {

      });
    this.editing = false;
  }

  toggleEdit(){
    this.editing = !this.editing;
  }

  add_banner(file : any){
    this.upload.post('api/v1/groups/group/' + this.group.guid + '/banner', [file.file], { banner_position: file.top })
      .then((response : any) => {

      })
      .catch((e) => {

      });
    console.log('new banne added', file);
  }

}
