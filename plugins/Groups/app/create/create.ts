import { Component, View } from 'angular2/core';
import { CORE_DIRECTIVES, FORM_DIRECTIVES } from 'angular2/common';
import { Router, RouterLink } from "angular2/router";

import { Client, Upload } from '../../../services/api';
import { MindsTitle } from '../../../services/ux/title';
import { SessionFactory } from '../../../services/session';
import { Material } from '../../../directives/material';
import { MindsBanner } from '../../../components/banner';
import { MindsAvatar } from '../../../components/avatar';


@Component({
  selector: 'minds-groups-create',

  bindings: [MindsTitle ]
})
@View({
  templateUrl: 'src/plugins/Groups/create/create.html',
  directives: [ CORE_DIRECTIVES, Material, RouterLink, FORM_DIRECTIVES, MindsBanner, MindsAvatar ]
})

export class GroupsCreator {

  session = SessionFactory.build();
  banner;
  avatar;
  group : any = {
    name: '',
    description: '',
    membership: 2,
    tags: '',
    invitees: ''
  };
  invitees: string[] = [];
  editing: boolean = true;

  constructor(public client: Client, public upload: Upload, public router: Router, public title: MindsTitle){
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

  addInvitee(input, $event = null) {
    if ($event) {
      $event.preventDefault();
      $event.stopPropagation();
    }

    if (!input.value) {
      return;
    }

    let user = input.value;

    input.value = '';

    if (this.invitees.indexOf(user) > -1) {
      return;
    }

    this.client.post(`api/v1/groups/invitations/preinvite`, { user: user })
    .then((response: any) => {
      if (response.done) {
        this.invitees.push(user)
      }
    })
    .catch(e => {

    })
  }

  removeInvitee(i) {
    this.invitees.splice(i, 1);
  }

  save(){
    var self = this;
    this.editing = false;

    this.group.invitees = this.invitees.join(',');

    this.upload.post('api/v1/groups/group', [this.banner, this.avatar], this.group)
      .then((response : any) => {
        self.router.navigate(['/Groups-Profile', {guid: response.guid, filter: ''}]);
      })
      .catch((e)=>{
        this.editing = true;
      });
  }

}
