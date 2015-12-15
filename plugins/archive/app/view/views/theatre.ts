import { Component, View, CORE_DIRECTIVES } from 'angular2/angular2';
import { MindsVideo } from '../../../../components/video';
import { Client } from '../../../../services/api';
import { SessionFactory } from '../../../../services/session';
import { Material } from '../../../../directives/material';

@Component({
  selector: 'minds-archive-theatre',
  viewBindings: [ Client ],
  inputs: ['_object: object']
})
@View({
  template: `
    <div class="minds-archive-stage" *ng-if="object.subtype == 'image'">
      <img src="/archive/thumbnail/{{object.guid}}/xlarge"/>
    </div>
    <div class="minds-archive-stage" *ng-if="object.subtype == 'video'">
      <minds-video [autoplay]="true" [muted]="false" [src]="[{ 'uri': object.src['720.mp4'] }]" >
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
    if(!value.guid)
      return;
    this.object = value;
    this.logPlay();
  }

  logPlay(){
    this.client.put('api/v1/analytics/play/' + this.object.guid);
  }

}
