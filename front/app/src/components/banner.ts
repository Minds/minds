import { Component, View, NgIf} from 'angular2/angular2';
import { Client } from "src/services/api";
import { RouterLink } from 'angular2/router';
import { Material } from 'src/directives/material';

@Component({
  selector: 'minds-banner',
  properties: ['_object: object']
})
@View({
  templateUrl: 'templates/components/banner.html',
  directives: [ NgIf, RouterLink, Material ]
})

export class MindsBanner{

  object;
  minds : Minds;

	constructor(){
    this.minds = window.Minds;
    console.log("BANNER CREATED");
	}

  set _object(value : any){
    this.object = value;
    console.log(this.object);
  }

}
