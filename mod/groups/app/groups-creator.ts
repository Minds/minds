import { Component, View, CORE_DIRECTIVES, FORM_DIRECTIVES} from 'angular2/angular2';
import { Router, RouterLink } from "angular2/router";

import { Client, Upload } from 'src/services/api';
import { MindsTitle } from 'src/services/ux/title';
import { SessionFactory } from 'src/services/session';
import { Material } from 'src/directives/material';
import { MindsBanner } from 'src/components/banner';

@Component({
  selector: 'minds-groups-create',
  viewBindings: [ Client, Upload ],
  bindings: [ MindsTitle ]
})
@View({
  templateUrl: 'templates/plugins/groups/create.html',
  directives: [ CORE_DIRECTIVES, Material, RouterLink, FORM_DIRECTIVES, MindsBanner ]
})

export class GroupsCreator {

  session = SessionFactory.build();
  banner;
  group : any = {
    name: '',
    description: '',
    membership: 2
  };

  constructor(public client: Client, public upload: Upload, public router: Router, public title: MindsTitle){
    this.title.setTitle("Create Group");
  }

  addBanner(banner : any){
    this.banner = banner.file;
    this.group.banner_position = banner.top;
  }

  membershipChange(value){
    console.log(value);
    this.group.membership = value;
  }

  save(){
    var self = this;
    this.upload.post('api/v1/groups/group', [this.banner], this.group)
      .then((response : any) => {
        self.router.navigate(['/Groups-Profile', {guid: response.guid, filter: ''}]);
      })
      .catch((e)=>{

      });
  }

}
