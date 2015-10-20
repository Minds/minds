import { Component, View, NgFor, NgIf, NgClass, Observable, Inject, FORM_DIRECTIVES} from 'angular2/angular2';
import { Router, RouterLink } from "angular2/router";

import { MindsTitle } from 'src/services/ux/title';
import { Client } from 'src/services/api';
import { SessionFactory } from 'src/services/session';
import { Material } from 'src/directives/material';

@Component({
  selector: 'minds-groups-create',
  viewBindings: [ Client ],
  bindings: [ MindsTitle ]

})
@View({
  templateUrl: 'templates/plugins/groups/create.html',
  directives: [ NgFor, NgIf, NgClass, Material, RouterLink, FORM_DIRECTIVES ]
})

export class GroupsCreator {

  session = SessionFactory.build();
  group = {
    name: '',
    description: '',
    membership: 2
  };

  constructor(public client: Client, @Inject(Router) public router: Router, public title: MindsTitle){
    this.title.setTitle("Create Group");
  }

  membershipChange(value){
    console.log(value);
    this.group.membership = value;
  }

  save(){
    console.log(this.group);

    var self = this;
    this.client.post('api/v1/groups/group', this.group)
      .then((response) => {

      })
      .catch((e)=>{

      });
  }

}
