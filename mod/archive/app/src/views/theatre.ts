import { Component, View, CORE_DIRECTIVES } from 'angular2/angular2';

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
      <video autoplay controls width="100%">
        <source src="{{entity.src['720.mp4']}}" type="video/mp4" data-res="720p"></source>
        <source src="{{entity.src['360.mp4']}}" type="video/mp4" data-res="360p"></source>
      </video>
    </div>
  `,
  directives: [ CORE_DIRECTIVES,  Material ]
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
