import { Component } from '@angular/core';
import { CORE_DIRECTIVES } from '@angular/common';
import { Router } from '@angular/router-deprecated';

import { MindsVideo } from '../../../../components/video';
import { Client } from '../../../../services/api';
import { SessionFactory } from '../../../../services/session';
import { Material } from '../../../../directives/material';


@Component({
  selector: 'minds-archive-theatre',

  inputs: ['_object: object'],
  template: `
    <i class="material-icons left"
      (click)="prev()"
      [hidden]="object.container_guid == object.owner_guid || !object.album_children_guids || object.album_children_guids.length <= 1">
        keyboard_arrow_left
    </i>
    <div class="minds-archive-stage" [ngClass]="{minds-image-landscape: orientation == 'landscape', minds-image-portrait: orientation == 'portrait'}" *ngIf="object.subtype == 'image'">
      <img src="/archive/thumbnail/{{object.guid}}/xlarge"/>
    </div>
    <div class="minds-archive-stage" *ngIf="object.subtype == 'video'">
      <minds-video
      [poster]="object.thumbnail_src"
	    [autoplay]="true"
	    [muted]="false"
	    [src]="[{ 'uri': object.src['720.mp4'] }, { 'uri': object.src['360.mp4'] }]"
        [log]="object.guid"
        [playCount]="false">
      </minds-video>
    </div>
    <i class="material-icons right"
      (click)="next()"
      [hidden]="object.container_guid == object.owner_guid || !object.album_children_guids || object.album_children_guids.length <= 1">
        keyboard_arrow_right
    </i>
    <ng-content></ng-content>
  `,
  directives: [ CORE_DIRECTIVES, MindsVideo, Material ]
})

export class ArchiveTheatre {

  object: any = {};
  orientation: string;
  session = SessionFactory.build();

  constructor(public client: Client, public router: Router){
  }

  set _object(value : any){
    if(!value.guid)
      return;
    this.object = value;
    if(this.object.subtype == 'image'){
      /*Check image's aspect ratio for landscape or l*/
      if(this.object.naturalWidth >= this.object.naturalHeight){
        this.orientation = 'landscape';
      } else {
        this.orientation = 'portrait';
      }
    }
  }

  prev(){
    var pos = this.object['album_children_guids'].indexOf(this.object.guid) -1;
    //go from the top if less than 0
    if(pos <= 0)
      pos = this.object['album_children_guids'].length -1;
    this.router.navigate(['/Archive-View', {guid: this.object['album_children_guids'][pos] }]);
  }

  next(){
    var pos = this.object['album_children_guids'].indexOf(this.object.guid);
    //bump up if less than 0
    if(pos <= 0)
      pos = 1;
    //bump one up if we are in the same position as ourself
    if(this.object['album_children_guids'][pos] == this.object.guid)
      pos++;
    //reset back to 0 if we are are the end
    if(pos >= this.object['album_children_guids'].length)
      pos = 0;
    this.router.navigate(['/Archive-View', {guid: this.object['album_children_guids'][pos] }]);
  }

}
