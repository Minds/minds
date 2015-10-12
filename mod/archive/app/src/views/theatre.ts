import { Component, View, CORE_DIRECTIVES } from 'angular2/angular2';
import { MindsVideo } from 'src/components/video';
import { Client } from 'src/services/api';
import { SessionFactory } from 'src/services/session';
import { Material } from 'src/directives/material';

@Component({
  selector: 'minds-archive-theatre',
  viewBindings: [ Client ],
  properties: ['_object: object']
})
@View({
  template: `
    <div class="minds-archive-stage" *ng-if="object.subtype == 'image'">
      <img src="/archive/thumbnail/{{object.guid}}/xlarge"/>
    </div>
    <div class="minds-archive-stage" *ng-if="object.subtype == 'video'">
      <minds-video [autoplay]="true" [src]="[{ 'uri': object.src['720.mp4'] }]" height="100%">
      </minds-video>
    </div>
  `,
  directives: [ CORE_DIRECTIVES, MindsVideo, Material ]
})

export class ArchiveTheatre {

  object: any = {};
  session = SessionFactory.build();

  constructor(public client: Client){
  }

  set _object(value : any){
    this.object = value;
  }

}
