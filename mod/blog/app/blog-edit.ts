import { Component, View, NgFor, NgIf, NgClass, Inject, FORM_DIRECTIVES} from 'angular2/angular2';
import { Router, RouteParams, ROUTER_DIRECTIVES } from "angular2/router";

import { Client } from 'src/services/api';
import { SessionFactory } from 'src/services/session';
import { Material } from 'src/directives/material';

@Component({
  selector: 'minds-blog-edit',
  viewBindings: [ Client ]
})
@View({
  templateUrl: 'templates/plugins/blog/edit.html',
  directives: [ NgFor, NgIf, NgClass, Material, ROUTER_DIRECTIVES]
})

export class BlogEdit {

  guid : string;

  constructor(public client: Client,
    @Inject(Router) public router: Router,
    @Inject(RouteParams) public params: RouteParams
    ){
      if(params.params['guid'])
        this.guid = params.params['guid'];
      this.minds = window.Minds;
  }

}
