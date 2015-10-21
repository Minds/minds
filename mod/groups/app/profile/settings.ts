import { Component, View, CORE_DIRECTIVES, Observable, Inject, FORM_DIRECTIVES} from 'angular2/angular2';
import { Router, RouterLink, RouteParams } from "angular2/router";

import { Client } from 'src/services/api';
import { SessionFactory } from 'src/services/session';
import { MDL_DIRECTIVES } from 'src/directives/material';

@Component({
  selector: 'minds-groups-profile-settings',
  viewBindings: [ Client ],
  properties: ['_group : group']
})
@View({
  templateUrl: 'templates/plugins/groups/profile/settings.html',
  directives: [ CORE_DIRECTIVES, FORM_DIRECTIVES, MDL_DIRECTIVES, RouterLink ]
})

export class GroupsProfileSettings {

  group : any;
  session = SessionFactory.build();

  members : Array<any> = [];
  offset : string = "";
  inProgress : boolean = false;
  moreData : boolean = true;

	constructor(public client: Client, public router: Router){

	}

  set _group(value : any){
    this.group = value;
  }

  membershipChange(value){
    console.log(value);
    this.group.membership = value;
  }

  save(){
    var self = this;
    this.client.post('api/v1/groups/group/' + this.group.guid, this.group)
      .then((response : any) => {
        self.router.navigate(['/Groups-Profile', {guid: self.group.guid}]);
      })
      .catch((e)=>{

      });
  }

}
