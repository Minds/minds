import { Component, View, CORE_DIRECTIVES} from 'angular2/angular2';
import { Router, RouteParams, ROUTER_DIRECTIVES } from "angular2/router";

import { Client } from '../../../services/api';
import { SessionFactory } from '../../../services/session';
import { Material } from '../../../directives/material';

import { Comments } from '../../../controllers/comments/comments';
import { BUTTON_COMPONENTS } from '../../../components/buttons';

import { ArchiveTheatre } from './views/theatre';
import { ArchiveGrid } from './views/grid';

@Component({
  selector: 'minds-archive-view',
  viewBindings: [ Client ]
})
@View({
  templateUrl: 'src/plugins/archive/view/view.html',
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
    this.inProgress = true;
    this.client.get('api/v1/entities/entity/' + this.guid, { children: false })
      .then((response : any) => {
        this.inProgress = false;
        if(response.entity.type != 'object'){
          return;
        }
        if(response.entity)
          this.entity = response.entity;

      })
      .catch((e) => {

      });
  }

  delete(){
    if(confirm("Are you sure?")){
      this.client.delete('api/v1/archive/' + this.guid)
        .then((response : any) => {
          this.router.navigate(['/Discovery', {filter: 'owner', type: null}]);
        })
        .catch((e) => {
        });
    }
  }

}
