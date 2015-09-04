import { Component, View, NgFor, NgIf, NgClass, Inject, FORM_DIRECTIVES} from 'angular2/angular2';
import { Router, ROUTER_DIRECTIVES } from "angular2/router";

import { Client } from 'src/services/api';
import { SessionFactory } from 'src/services/session';
import { Material } from 'src/directives/material';

@Component({
  selector: 'minds-blog-view',
  viewBindings: [ Client ]
})
@View({
  templateUrl: 'templates/plugins/blog/view.html',
  directives: [ NgFor, NgIf, NgClass, Material, ROUTER_DIRECTIVES]
})

export class BlogView {
  activity : any;
  session = SessionFactory.build();
  setup : boolean = false;

	constructor(public client: Client){
	}

}
