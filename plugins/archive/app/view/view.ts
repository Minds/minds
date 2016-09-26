import { Component } from '@angular/core';
import { CORE_DIRECTIVES } from '@angular/common';
import { Router, RouteParams, ROUTER_DIRECTIVES } from "@angular/router-deprecated";

import { Client } from '../../../services/api';
import { SessionFactory } from '../../../services/session';
import { Material } from '../../../directives/material';
import { Hovercard } from '../../../directives/hovercard';

import { Comments } from '../../../controllers/comments/comments';
import { BUTTON_COMPONENTS } from '../../../components/buttons';
import { ConfirmModal } from '../../../components/modal/modal';

import { ArchiveTheatre } from './views/theatre';
import { ArchiveGrid } from './views/grid';

import { AttachmentService } from '../../../services/attachment';
import { SocialIcons } from '../../../components/social-icons/social-icons';

@Component({
  selector: 'minds-archive-view',
  providers: [ AttachmentService ],
  templateUrl: 'src/plugins/archive/view/view.html',
  directives: [ CORE_DIRECTIVES, ROUTER_DIRECTIVES, BUTTON_COMPONENTS, Material, Comments, ArchiveTheatre, ArchiveGrid, SocialIcons, Hovercard, ConfirmModal ]
})

export class ArchiveView {

  minds;
  guid : string;
  entity : any = {};
  session = SessionFactory.build();
  inProgress : boolean = true;
  error : string = "";
  deleteToggle: boolean = false;

  constructor(public client: Client,public router: Router, public params: RouteParams, public attachment: AttachmentService){
      if(params.params['guid'])
        this.guid = params.params['guid'];
      this.minds = window.Minds;
      this.load();
  }

  load(refresh : boolean = false){
    this.inProgress = true;
    this.client.get('api/v1/archive/' + this.guid, { children: false })
      .then((response : any) => {
        this.inProgress = false;
        if(response.entity.type != 'object'){
          return;
        }
        if(response.entity)
          this.entity = response.entity;

      })
      .catch((e) => {
          this.inProgress = false;
          this.error = "Sorry, there was problem."
      });
  }

  delete(){
    this.client.delete('api/v1/archive/' + this.guid)
      .then((response : any) => {
        this.router.navigate(['/Discovery', {filter: 'owner', type: null}]);
      })
      .catch((e) => {
      });
  }

}
