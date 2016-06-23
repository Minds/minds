import { Component, Inject } from '@angular/core';
import { CORE_DIRECTIVES, FORM_DIRECTIVES } from '@angular/common';
import { Router, RouteParams, ROUTER_DIRECTIVES } from "@angular/router-deprecated";

import { Client, Upload } from '../../../services/api';
import { SessionFactory } from '../../../services/session';
import { LICENSES, ACCESS } from '../../../services/list-options';

import { Material } from '../../../directives/material';
import { AutoGrow } from '../../../directives/autogrow';
import { MDL_DIRECTIVES } from '../../../directives/material';
import { Comments } from '../../../controllers/comments/comments';
import { BUTTON_COMPONENTS } from '../../../components/buttons';
import { MindsTinymce } from '../../../components/editors/tinymce';
import { ArchiveTheatre } from '../view/views/theatre';
import { ArchiveGrid } from '../view/views/grid';
import { ThumbnailSelector } from '../components/thumbnail-selector';


@Component({
  selector: 'minds-archive-edit',
  templateUrl: 'src/plugins/archive/edit/edit.html',
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
    license: "all-rights-reserved",
    mature: false
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
    this.inProgress = true;
    this.client.get('api/v1/entities/entity/' + this.guid, { children: false })
      .then((response : any) => {
        this.inProgress = false;
        console.log(response);
        if(response.entity){
          if (!response.entity.description)
            response.entity.description = "";

          if(!response.entity.license)
            response.entity.license = "all-rights-reserved";

          response.entity.mature = response.entity.flags && response.entity.flags.mature ? 1 : 0;

          this.entity = response.entity;
        }
      })
      .catch((e) => {

      });
  }

  save(){
    this.client.post('api/v1/archive/' + this.guid, this.entity)
      .then((response : any) => {
        console.log(response);
        this.router.navigate(['/Archive-View', {guid: this.guid}]);
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
