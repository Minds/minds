import { Component, View, CORE_DIRECTIVES, Inject} from 'angular2/angular2';
import { Router, RouteParams, ROUTER_DIRECTIVES } from "angular2/router";

import { Client } from 'src/services/api';
import { SessionFactory } from 'src/services/session';
import { Material } from 'src/directives/material';
import { MindsTitle } from 'src/services/ux/title';
import { MindsBanner } from 'src/components/banner';
import { Comments } from 'src/controllers/comments/comments';
import { BUTTON_COMPONENTS } from 'src/components/buttons';

import { MindsBlogResponse } from 'src/interfaces/responses';
import { MindsBlogEntity } from 'src/interfaces/entities';

@Component({
  selector: 'minds-blog-view',
  viewBindings: [ Client ],
  bindings:[ MindsTitle ]
})
@View({
  templateUrl: 'templates/plugins/blog/view.html',
  directives: [ CORE_DIRECTIVES, ROUTER_DIRECTIVES, BUTTON_COMPONENTS, Material, Comments, MindsBanner ]
})

export class BlogView {

  minds;
  guid : string;
  blog : MindsBlogEntity = {
    guid: '',
    title: '',
    description: '',
    ownerObj: {}
  };
  session = SessionFactory.build();

  constructor(public client: Client,
    @Inject(Router) public router: Router,
    @Inject(RouteParams) public params: RouteParams,
    public title: MindsTitle){
      if(params.params['guid'])
        this.guid = params.params['guid'];
      this.minds = window.Minds;
      this.load();
  }

  load(refresh : boolean = false){
    console.log('grabbing ' + this.guid);
    var self = this;
    this.client.get('api/v1/blog/' + this.guid, {})
      .then((response : MindsBlogResponse) => {
        if(response.blog)
          self.blog = response.blog;
          self.title.setTitle(self.blog.title);
      })
      .catch((e) => {

      });
  }

  delete(){
    var self = this;
    this.client.delete('api/v1/blog/' + this.guid)
      .then((response : any) => {
        self.router.navigate(['/Blog', {filter: 'owner'}]);
      })
      .catch((e) => {
      });
  }

}
