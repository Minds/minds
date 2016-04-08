import { Component, View } from 'angular2/core';
import { CORE_DIRECTIVES, FORM_DIRECTIVES } from 'angular2/common';
import { Router, RouterLink } from "angular2/router";

import { GroupsService } from '../groups-service';

import { MindsTitle } from '../../../services/ux/title';
import { SessionFactory } from '../../../services/session';
import { Material } from '../../../directives/material';
import { MindsBanner } from '../../../components/banner';
import { MindsAvatar } from '../../../components/avatar';
import { TagsInput } from '../../../components/forms/tags-input/tags';

import { GroupsProfileMembersInvite } from '../profile/members/invite/invite';


@Component({
  selector: 'minds-groups-create',

  bindings: [ MindsTitle, GroupsService ]
})
@View({
  templateUrl: 'src/plugins/Groups/create/create.html',
  directives: [ CORE_DIRECTIVES, Material, RouterLink, FORM_DIRECTIVES, MindsBanner, MindsAvatar, GroupsProfileMembersInvite, TagsInput ]
})

export class GroupsCreator {

  minds = window.Minds;

  session = SessionFactory.build();
  banner : any = false;
  avatar : any = false;
  group : any = {
    name: '',
    description: '',
    membership: 2,
    tags: '',
    invitees: ''
  };
  invitees : Array<any> = [];
  editing : boolean = true;
  editDone : boolean = false;
  inProgress : boolean = false;

  constructor(public service: GroupsService, public router: Router, public title: MindsTitle){
    this.title.setTitle("Create Group");
  }

  addBanner(banner : any){
    this.banner = banner.file;
    this.group.banner_position = banner.top;
  }

  addAvatar(avatar : any){
    this.avatar = avatar;
  }

  membershipChange(value){
    this.group.membership = value;
  }


  invite(user : any){
    for(let i of this.invitees){
      if(i.guid == user.guid)
        return;
    }
    this.invitees.push(user);
  }

  removeInvitee(i) {
    this.invitees.splice(i, 1);
  }

  save(){

    if(!this.group.name){
      return;
    }

    this.editing = false;
    this.editDone = true;
    this.inProgress = true;

    this.group.invitees = this.invitees.map((user) => {
      return user.guid;
    });

    this.service.save(this.group)
    .then((guid: any) => {

      this.service.upload({
          guid,
          banner_position: this.group.banner_position
        }, {
          banner: this.banner,
          avatar: this.avatar
        })
        .then(() => {
          this.router.navigate(['/Groups-Profile', { guid, filter: '' }]);
        });

    })
    .catch(e => {
      this.editing = true;
      this.inProgress = false;
    });
  }

}
