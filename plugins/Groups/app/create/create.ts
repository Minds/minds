import { Component } from '@angular/core';
import { Router } from "@angular/router";

import { GroupsService } from '../groups-service';

import { MindsTitle } from '../../../services/ux/title';
import { SessionFactory } from '../../../services/session';

@Component({
  moduleId: module.id,
  selector: 'minds-groups-create',
  host: {
    '(keydown)': 'keyDown($event)'
  },
  providers: [ MindsTitle, GroupsService ],
  templateUrl: 'create.html'
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

  keyDown(e){
    if(e.keyCode == 13){
      e.preventDefault();
      return false;
    }
  }

  save(e){

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
          this.router.navigate(['/groups/profile', guid]);
        });

    })
    .catch(e => {
      this.editing = true;
      this.inProgress = false;
    });
  }

}
