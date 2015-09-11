import { Component, View, CORE_DIRECTIVES, Observable, Inject, FORM_DIRECTIVES} from 'angular2/angular2';
import { RouterLink, RouteParams } from "angular2/router";

import { Client } from 'src/services/api';
import { SessionFactory } from 'src/services/session';
import { Material } from 'src/directives/material';
import { Activity } from 'src/controllers/newsfeed/activity';

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
  members : Array<any>
}


@Component({
  selector: 'minds-groups-profile',
  viewBindings: [ Client ]
})
@View({
  templateUrl: 'templates/plugins/groups/profile.html',
  directives: [ CORE_DIRECTIVES, FORM_DIRECTIVES, Material, RouterLink, Activity, GroupsProfileMembers,
    GroupsProfileFeed, GroupsProfileSettings, GroupsProfileRequests ]
})

export class GroupsProfile {

  guid;
  filter = "activity";
  group : MindsGroup;
  postMeta : any = {
    message: '',
    container_guid: 0
  };
  session = SessionFactory.build();

  activity : Array<any> = [];
  offset : string = "";
  inProgress : boolean = false;
  moreData : boolean = true;

	constructor(public client: Client,
    @Inject(RouteParams) public params: RouteParams
    ){
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
          self.group.members = [];
      })
      .catch((e)=>{

      });
  }

}
