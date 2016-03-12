import { Component } from 'angular2/core';
import { CORE_DIRECTIVES } from 'angular2/common';

import { Material } from '../../../../../directives/material';
import { GroupsService } from '../../../groups-service';


@Component({
  selector: 'minds-groups-profile-members-invite',

  inputs: ['_group : group'],
  templateUrl: 'src/plugins/Groups/profile/members/invite/invite.html',
  directives: [ CORE_DIRECTIVES, Material ],
  bindings: [ GroupsService ]
})

export class GroupsProfileMembersInvite {

  group : any;

  inviteInProgress: boolean = false;
  inviteLastUser: string = '';
  inviteError: string = '';

  constructor(public service: GroupsService) {
  }

  set _group(value : any){
    this.group = value;
  }

  invite(invitation) {
    this.inviteInProgress = true;
    this.inviteLastUser = '';
    this.inviteError = '';

    this.service.invite(this.group, invitation.value.user)
    .then(() => {
      this.inviteInProgress = false;
      this.inviteLastUser = invitation.value.user;

      // NOTE: [emi] Check hosting component in front/multi
      window.document.querySelector('.minds-groups-invite-form #user').value = '';
    })
    .catch(e => {
      this.inviteInProgress = false;
      this.inviteError = e;
    });
  }

}
