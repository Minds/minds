import { Component } from 'angular2/core';
import { CORE_DIRECTIVES } from 'angular2/common';

import { Client } from '../../../../../services/api';
import { Material } from '../../../../../directives/material';


@Component({
  selector: 'minds-groups-profile-members-invite',

  inputs: ['_group : group'],
  templateUrl: 'src/plugins/Groups/profile/members/invite/invite.html',
  directives: [ CORE_DIRECTIVES, Material ]
})

export class GroupsProfileMembersInvite {

  group : any;

  inviteInProgress: boolean = false;
  inviteLastUser: string = '';
  inviteError: string = '';

  constructor(public client: Client){

  }

  set _group(value : any){
    this.group = value;
  }

  invite(invitation) {
    this.inviteInProgress = true;
    this.inviteLastUser = '';
    this.inviteError = '';

    this.client.put(`api/v1/groups/invitations/${this.group.guid}`, { invitee: invitation.value.user })
    .then((response : any) => {

      this.inviteInProgress = false;

      if (response.done) {
        this.inviteLastUser = invitation.value.user;
        // NOTE: [emi] Check hosting component in front/multi
        window.document.querySelector('.minds-groups-invite-form #user').value = '';
      }
      else {
        this.inviteError = response.error ? response.error : 'Internal error';
      }

    })
    .catch((e)=>{
      this.inviteInProgress = false;
      this.inviteError = 'Connectivity error';
    });
  }

}
