import { Component } from '@angular/core';

import { GroupsService } from '../../groups-service';

import { Client } from '../../../../services/api';
import { SessionFactory } from '../../../../services/session';

@Component({
  moduleId: module.id,
  selector: 'minds-groups-profile-members',

  inputs: ['_group : group'],
  templateUrl: 'members.html'
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

    q : string = "";

	constructor(public client : Client, public service: GroupsService){

	}

  set _group(value : any){
    this.group = value;
    this.load();
  }

  ngOnDestroy() {
    if (this.searchDelayTimer) {
      clearTimeout(this.searchDelayTimer);
    }
  }

  private lastQuery;
  load(refresh: boolean = false, query = null) {
    if(this.inProgress)
      return;
    
    if (query !== null && query !== this.lastQuery) {
      refresh = true;
      this.lastQuery = query;
    }

    if (refresh) {
      this.offset = '';
      this.moreData = true;
    }

    // TODO: [emi] Send this via API
    this.canInvite = false;

    if (this.group.membership == 0 && this.group['is:owner']) {
      this.canInvite = true;
    } else if (this.group.membership == 2 && this.group['is:member']) {
      this.canInvite = true;
    }

    let endpoint = `api/v1/groups/membership/${this.group.guid}`,
      params: { limit, offset, q?: string } = { limit: 12, offset: this.offset };

    if (this.lastQuery) {
      endpoint = `${endpoint}/search`;
      params.q = this.lastQuery;
    }

    this.inProgress = true;
    this.client.get(endpoint, params)
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
        
        if (response['load-next']) {
          this.offset = response['load-next'];
        } else {
          this.moreData = false;
        }

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

  private searchDelayTimer;
  search(q) {
    if (this.searchDelayTimer) {
      clearTimeout(this.searchDelayTimer);
    }

    this.searchDelayTimer = setTimeout(() => {
      this.load(true, q);
    }, 500);
  }

}
