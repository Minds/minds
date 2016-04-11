import { Component, EventEmitter } from 'angular2/core';
import { CORE_DIRECTIVES } from 'angular2/common';

import { Client } from '../../../../../services/api';
import { Material } from '../../../../../directives/material';
import { GroupsService } from '../../../groups-service';


@Component({
  selector: 'minds-groups-profile-members-invite',
  inputs: ['_group : group'],
  events: ['invited'],
  templateUrl: 'src/plugins/Groups/profile/members/invite/invite.html',
  directives: [ CORE_DIRECTIVES, Material ],
  bindings: [ GroupsService ]
})

export class GroupsProfileMembersInvite {

  minds = window.Minds;

  group : any;
  invited : EventEmitter<any> = new EventEmitter();

  users : Array<any> = [];
  searching : boolean = false;
  q : string = "";

  inviteInProgress: boolean = false;
  inviteLastUser: string = '';
  inviteError: string = '';

  constructor(public client: Client, public service: GroupsService) {
  }

  set _group(value : any){
    this.group = value;
  }

  invite(user) {

    if(!user.subscriber){
      return alert('You can only invite users who are subscribed to you');
    }

    this.q = "";
    this.users = [];
    if(!this.group){
      this.invited.next(user);
      return;
    }
    this.inviteInProgress = true;
    this.inviteLastUser = '';
    this.inviteError = '';

    this.service.invite(this.group, user)
      .then(() => {
        this.inviteInProgress = false;
      })
      .catch(e => {
        this.inviteInProgress = false;
        this.inviteError = e;
      });
  }

  timeout;
  search(q) {
    if(this.timeout)
      clearTimeout(this.timeout);

    this.searching = true;
    if (this.q.charAt(0) != '@') {
      this.q = '@' + this.q;
    }

    var query = this.q;
    if (query.charAt(0) == '@') {
      query = query.substr(1);
    }

    this.timeout = setTimeout(() => {
      this.client.get('api/v1/search', {
          q: query,
          type: 'user',
          view: 'json',
          limit: 5
        })
        .then((success : any)=> {
          if (success.entities){
            this.users = success.entities;
          }
        })
        .catch((error)=>{
          console.log(error);
        });
    }, 600);
  };

}
