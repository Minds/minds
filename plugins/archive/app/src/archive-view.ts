import { Component, View, CORE_DIRECTIVES} from 'angular2/angular2';
import { Router, RouteParams, ROUTER_DIRECTIVES } from "angular2/router";

import { Client } from 'src/services/api';
import { SessionFactory } from 'src/services/session';
import { Material } from 'src/directives/material';

import { Comments } from 'src/controllers/comments/comments';
import { BUTTON_COMPONENTS } from 'src/components/buttons';

import { ArchiveTheatre } from './views/theatre';
import { ArchiveGrid } from './views/grid';

@Component({
  selector: 'minds-archive-view',
  viewBindings: [ Client ]
})
@View({
  templateUrl: 'templates/plugins/archive/view.html',
  directives: [ CORE_DIRECTIVES, ROUTER_DIRECTIVES, BUTTON_COMPONENTS, Material, Comments, ArchiveTheatre, ArchiveGrid ]
})

export class ArchiveView {

  minds;
  guid : string;
  entity : any = {};
  session = SessionFactory.build();
  inProgress : boolean = true;

  constructor(public client: Client,public router: Router, public params: RouteParams){
      if(params.params['guid'])
        this.guid = params.params['guid'];
      this.minds = window.Minds;
      this.load();
  }

  load(refresh : boolean = false){
    var self = this;
    this.inProgress = true;
    this.client.get('api/v1/entities/entity/' + this.guid, { children: false })
      .then((response : any) => {
        self.inProgress = false;
        console.log(response);
        if(response.entity)
          self.entity = response.entity;
      })
      .catch((e) => {

      });
  }

  delete(){
    var self = this;
    this.client.delete('api/v1/archive/' + this.guid)
      .then((response : any) => {
        self.router.navigate(['/Discovery', {filter: 'owner', type: null}]);
      })
      .catch((e) => {
      });
  }

}
