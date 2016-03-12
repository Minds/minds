import { Component, View, Inject } from 'angular2/core';
import { CORE_DIRECTIVES, FORM_DIRECTIVES } from 'angular2/common';
import { RouterLink, RouteParams } from "angular2/router";

import { GroupsService } from '../groups-service';

import { MindsTitle } from '../../../services/ux/title';
import { SessionFactory } from '../../../services/session';
import { MDL_DIRECTIVES } from '../../../directives/material';
import { CARDS } from '../../../controllers/cards/cards';
import { BUTTON_COMPONENTS } from '../../../components/buttons';
import { MindsBanner } from '../../../components/banner';
import { MindsAvatar } from '../../../components/avatar';

import { GroupsJoinButton } from '../groups-join-button';
import { GroupsSettingsButton } from './groups-settings-button';
import { GroupsProfileMembers } from './members/members';
import { GroupsProfileRequests } from './requests/requests';
import { GroupsProfileFeed } from './feed/feed';

import { ChannelModules } from '../../../controllers/channels/modules/modules';


@Component({
  selector: 'minds-groups-profile',

  bindings: [ MindsTitle, GroupsService ]
})
@View({
  templateUrl: 'src/plugins/Groups/profile/profile.html',
  directives: [ CORE_DIRECTIVES, FORM_DIRECTIVES, MDL_DIRECTIVES, BUTTON_COMPONENTS, RouterLink, CARDS, GroupsJoinButton,
    GroupsProfileMembers, GroupsProfileFeed, GroupsProfileRequests, MindsBanner, MindsAvatar, GroupsSettingsButton, ChannelModules ]
})

export class GroupsProfile {

  guid;
  filter = "activity";
  group;
  postMeta : any = {
    message: '',
    container_guid: 0
  };
  editing : boolean = false;
  editDone: boolean = false;
  session = SessionFactory.build();
  minds = window.Minds;

  activity : Array<any> = [];
  offset : string = "";
  inProgress : boolean = false;
  moreData : boolean = true;

	constructor(public service: GroupsService, public params: RouteParams, public title: MindsTitle){
      this.guid = params.params['guid'];
      if(params.params['filter'])
        this.filter = params.params['filter'];
      this.postMeta.container_guid = this.guid;
      this.load();
	}

  load(){
    this.service.load(this.guid)
    .then((group) => {
      this.group = group;
      this.title.setTitle(this.group.name);
    });
  }

  save(){
    this.service.save({
      guid: this.group.guid,
      name: this.group.name,
      briefdescription: this.group.briefdescription,
      tags: this.group.tags,
      membership: this.group.membership
    });

    this.editing = false;
    this.editDone = true;
  }

  toggleEdit(){
    this.editing = !this.editing;

    if (this.editing) {
      this.editDone = false;
    }
  }

  add_banner(file : any){
    this.service.upload({
      guid: this.group.guid,
      banner_position: file.top
    }, { banner: file.file });

    this.group.banner = true;
  }

  upload_avatar(file : any){
    this.service.upload({
      guid: this.group.guid
    }, { avatar: file });
  }

}
