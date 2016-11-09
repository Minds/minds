import { Component } from '@angular/core';
import { Router, ActivatedRoute } from "@angular/router";

import { Subscription } from 'rxjs/Rx';

import { Client } from '../../../services/api';
import { SessionFactory } from '../../../services/session';

import { AttachmentService } from '../../../services/attachment';

@Component({
  moduleId: module.id,
  selector: 'minds-archive-view',
  providers: [ AttachmentService ],
  templateUrl: 'view.html'
})

export class ArchiveView {

  minds;
  guid : string;
  entity : any = {};
  session = SessionFactory.build();
  inProgress : boolean = true;
  error : string = "";
  deleteToggle: boolean = false;

  constructor(public client: Client,public router: Router, public route: ActivatedRoute, public attachment: AttachmentService){
  }

  paramsSubscription: Subscription;
  ngOnInit() {
    this.minds = window.Minds;

    this.paramsSubscription = this.route.params.subscribe(params => {
      if (params['guid']) {
        this.guid = params['guid'];
        this.load();
      }
    });
  }

  ngOnDestroy() {
    this.paramsSubscription.unsubscribe();
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
        this.router.navigate(['/discovery/owner']);
      })
      .catch((e) => {
      });
  }

}
