import { Component, View, NgIf} from 'angular2/angular2';
import { Client } from "src/services/api";
import { RouterLink } from 'angular2/router';
import { Material } from 'src/directives/material';

@Component({
  selector: 'minds-banner',
  properties: ['_object: object']
})
@View({
  template: `
  <div class="minds-banner" *ng-if="object">
    <img *ng-if="object.subtype == 'blog' && object.header_bg == 1" src="/api/v1/blog/header/{{object.guid}}"/>
    <img *ng-if="object.subtype != 'blog' && object.banner" src="{{minds.cdn_url}}/fs/v1/banners/{{object.guid}}"/>
  </div>
  `,
  directives: [ NgIf, RouterLink, Material ]
})

export class MindsBanner{

  object;
  minds : Minds;

	constructor(){
    this.minds = window.Minds;
	}

  set _object(value : any){
    this.object = value;
  }

}
