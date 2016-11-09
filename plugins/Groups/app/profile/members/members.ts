import { Component } from '@angular/core';

import { GroupsService } from '../../groups-service';

import { Client } from '../../../../services/api';
import { SessionFactory } from '../../../../services/session';

@Component({
  moduleId: module.id,
  selector: 'minds-groups-profile-members',

  inputs: ['_group : group'],
  templateUrl: 'members.html',
  providers: [ GroupsService ]
})

export class GroupsProfileMembers {

  minds = window.Minds;

  group : any;
  session = SessionFactory.build();

  invitees : any = [];
  members : Array<any> = [];
  offset : string = "";
  inProgress : boolean = false;
  moreData : boolean = true;
  canInvite: boolean = false;

	constructor(public client : Client, public service: GroupsService){

	}

  set _group(value : any){
    this.group = value;
    this.load();
  }

  load(refresh : boolean = false){
    if(this.inProgress)
      return;

    // TODO: [emi] Send this via API
    this.canInvite = false;

    if (this.group.membership == 0 && this.group['is:owner']) {
      this.canInvite = true;
    } else if (this.group.membership == 2 && this.group['is:member']) {
      this.canInvite = true;
    }

    this.inProgress = true;
    this.client.get('api/v1/groups/membership/' + this.group.guid, { limit: 12, offset: this.offset })
      .then((response : any) => {

        if(!response.members){
          this.moreData = false;
          this.inProgress = false;
          return false;
        }

        if(refresh){
          this.members = response.members;
        } else {
          this.members = this.members.concat(response.members);
        }
        this.offset = response['load-next'];
        this.inProgress = false;

      })
      .catch((e)=>{
        this.inProgress = false;
      });
  }

  invite(user : any){
    for(let i of this.invitees){
      if(i.guid == user.guid)
        return;
    }
    this.invitees.push(user);
  }

}
