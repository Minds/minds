import { Component, View, Inject, CORE_DIRECTIVES, FORM_DIRECTIVES } from 'angular2/angular2';
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
  directives: [ CORE_DIRECTIVES, FORM_DIRECTIVES, ROUTER_DIRECTIVES, Material ]
})

export class BlogEdit {

  minds;

  guid : string;
  blog : Object = {
    guid: '',
    title: '',
    description: '',
    access_id: 2
  };

  constructor(public client: Client,
    @Inject(Router) public router: Router,
    @Inject(RouteParams) public params: RouteParams
    ){
      if(params.params['guid'])
        this.guid = params.params['guid'];
      this.minds = window.Minds;
      if(this.guid != 'new')
        this.load();
  }

  load(){
    var self = this;
    this.client.get('api/v1/blog/' + this.guid, {})
      .then((response : any) => {
        if(response.blog){
          self.blog = response.blog;
          self.guid = response.blog.guid;
        }
      })
      .catch((e) => {

      });
  }

  save(){
    var self = this;
    this.client.post('api/v1/blog/' + this.guid, this.blog)
      .then((response) => {
        console.log(response);
      })
      .catch((e) => {

      });
  }

}
