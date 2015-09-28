import { Component, View, CORE_DIRECTIVES } from 'angular2/angular2';

import { Client } from 'src/services/api';
import { SessionFactory } from 'src/services/session';
import { Material } from 'src/directives/material';
import { SubscribeButton } from 'src/directives/subscribe-button';

import { Comments } from 'src/controllers/comments/comments';

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
      <video>
        <source src="api/v1/archive/{{object.guid}}"></source>
      </video>
    </div>
  `,
  directives: [ CORE_DIRECTIVES,  Material, SubscribeButton, Comments ]
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
