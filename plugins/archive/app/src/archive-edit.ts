import { Component, View, CORE_DIRECTIVES, Inject, FORM_DIRECTIVES } from 'angular2/angular2';
import { Router, RouteParams, ROUTER_DIRECTIVES } from "angular2/router";

import { Client, Upload } from '../../../services/api';
import { SessionFactory } from '../../../services/session';
import { LICENSES, ACCESS } from '../../../services/list-options';

import { Material } from '../../../directives/material';
import { AutoGrow } from '../../../directives/autogrow';
import { MDL_DIRECTIVES } from '../../../directives/material';
import { Comments } from '../../../controllers/comments/comments';
import { BUTTON_COMPONENTS } from '../../../components/buttons';
import { MindsTinymce } from '../../../components/editors/tinymce';
import { ArchiveTheatre } from './views/theatre';
import { ArchiveGrid } from './views/grid';
import { ThumbnailSelector } from './components/thumbnail-selector';

@Component({
  selector: 'minds-archive-edit',
  viewBindings: [ Client, Upload ]
})
@View({
  templateUrl: 'templates/plugins/archive/edit.html',
  directives: [ MDL_DIRECTIVES, FORM_DIRECTIVES, CORE_DIRECTIVES, ROUTER_DIRECTIVES, BUTTON_COMPONENTS, AutoGrow, MindsTinymce, Material, Comments, ArchiveTheatre, ArchiveGrid, ThumbnailSelector  ]
})

export class ArchiveEdit {

  minds;
  session = SessionFactory.build();
  guid : string;
  entity : any  = {
    title: "",
    description: "",
    subtype: "",
    license: "all-rights-reserved"
  };
  inProgress : boolean;
  error : string;

  licenses = LICENSES;
  access = ACCESS;

  constructor(public client: Client, public upload: Upload, public router: Router, public params: RouteParams){
      if(params.params['guid'])
        this.guid = params.params['guid'];
      this.minds = window.Minds;
      this.load();
  }

  load(){
    var self = this;
    this.inProgress = true;
    this.client.get('api/v1/entities/entity/' + this.guid, { children: false })
      .then((response : any) => {
        self.inProgress = false;
        console.log(response);
        if(response.entity){
          if (!response.entity.description)
            response.entity.description = "";

          self.entity = response.entity;
        }
      })
      .catch((e) => {

      });
  }

  save(){
    var self = this;
    this.client.post('api/v1/archive/' + this.guid, this.entity)
      .then((response : any) => {
        console.log(response);
        self.router.navigate(['/Archive-View', {guid: self.guid}]);
      })
      .catch((e) => {
        this.error ="There was an error while trying to update";
      });
  }

  setThumbnail(file){
    console.log(file);
    this.entity.file = file[0];
    this.entity.thumbnail = file[1];
  }

}
