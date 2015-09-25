import { Component, View, CORE_DIRECTIVES, NgStyle, Inject, FORM_DIRECTIVES} from 'angular2/angular2';
import { ROUTER_DIRECTIVES, Router, RouteParams } from "angular2/router";

import { Client } from 'src/services/api';
import { SessionFactory } from 'src/services/session';
import { Material } from 'src/directives/material';
import { InfiniteScroll } from 'src/directives/infinite-scroll';
import { MindsBlogListResponse } from 'src/interfaces/responses';

@Component({
  selector: 'minds-card-blog',
  viewBindings: [ Client ],
  properties: ['_blog : object']
})
@View({
  templateUrl: 'templates/plugins/blog/card.html',
  directives: [ CORE_DIRECTIVES, ROUTER_DIRECTIVES, NgStyle, Material, InfiniteScroll ]
})

export class BlogCard {

  minds;

  blog;
  session = SessionFactory.build();

  constructor(){
      this.minds = window.Minds;
  }

  set _blog(value : any){
    this.blog = value;
  }

}

export { BlogView } from './blog-view';
export { BlogEdit } from './blog-edit';
