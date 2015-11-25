import { Component, View, CORE_DIRECTIVES, Observable, Inject, FORM_DIRECTIVES} from 'angular2/angular2';
import { RouterLink, RouteParams } from "angular2/router";

import { Client } from '../../../../services/api';
import { SessionFactory } from '../../../../services/session';
import { Material } from '../../../../directives/material';
import { InfiniteScroll } from '../../../../directives/infinite-scroll';
import { UserCard } from '../../../../controllers/cards/cards';

@Component({
  selector: 'minds-groups-profile-members',
  viewBindings: [ Client ],
  properties: ['_group : group']
})
@View({
  templateUrl: 'src/plugins/groups/profile/members/members.html',
  directives: [ CORE_DIRECTIVES, FORM_DIRECTIVES, Material, RouterLink, UserCard, InfiniteScroll ]
})

export class GroupsProfileMembers {

  group : any;
  session = SessionFactory.build();

  members : Array<any> = [];
  offset : string = "";
  inProgress : boolean = false;
  moreData : boolean = true;

	constructor(public client: Client){

	}

  set _group(value : any){
    this.group = value;
    this.load();
  }

  load(refresh : boolean = false){
    var self = this;

    if(this.inProgress)
      return;

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
          this.members.push(member);
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

}
