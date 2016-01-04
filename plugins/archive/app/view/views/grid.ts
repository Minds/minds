import { Component, View } from 'angular2/core';
import { CORE_DIRECTIVES } from 'angular2/common';
import { Router, RouteParams, ROUTER_DIRECTIVES } from "angular2/router";

import { Client } from '../../../../services/api';
import { SessionFactory } from '../../../../services/session';
import { Material } from '../../../../directives/material';
import { InfiniteScroll } from '../../../../directives/infinite-scroll';


@Component({
  selector: 'minds-archive-grid',
  viewBindings: [ Client ],
  properties: ['_object: object']
})
@View({
  template: `
    <a *ngFor="#item of items" [routerLink]="['/Archive-View', {guid: item.guid}]">
      <img src="/archive/thumbnail/{{item.guid}}/large" />
      	<span class="material-icons" [hidden]="item.subtype !='video'">play_circle_outline</span>
    </a>
    <infinite-scroll
        distance="25%"
        (load)="load()"
        *ngIf="moreData"
        style="width:100%">
        <div class="mdl-spinner mdl-js-spinner is-active" [mdl] [hidden]="!inProgress"></div>
    </infinite-scroll>
  `,
  directives: [ CORE_DIRECTIVES, ROUTER_DIRECTIVES, Material, InfiniteScroll ]
})

export class ArchiveGrid {

  object : any = {};
  session = SessionFactory.build();

  items : Array<any> = [];
  inProgress : boolean = false;
  moreData : boolean = true;
  offset : string = "";

  constructor(public client: Client){
  }

  set _object(value : any){
    this.object = value;
    this.load();
  }

  load(){
    var self = this;
    if(this.inProgress)
      return;
    this.inProgress = true;
    this.client.get('api/v1/archive/albums/' + this.object.guid, { offset: this.offset })
      .then((response : any) => {
        if(!response.entities || response.entities.length == 0){
          self.inProgress = false
          self.moreData = false;
          return false;
        }

        self.items = self.items.concat(response.entities);
        self.offset = response['load-next'];
        self.inProgress = false;
      })
      .catch((e)=>{

      });
  }

}
