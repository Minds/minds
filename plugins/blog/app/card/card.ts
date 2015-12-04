import { Component, View, CORE_DIRECTIVES, NgStyle, Inject, FORM_DIRECTIVES} from 'angular2/angular2';
import { ROUTER_DIRECTIVES, Router, RouteParams } from "angular2/router";

import { Client } from '../../../services/api';
import { SessionFactory } from '../../../services/session';
import { Material } from '../../../directives/material';
import { InfiniteScroll } from '../../../directives/infinite-scroll';
import { MindsBlogListResponse } from '../../../interfaces/responses';
import { BUTTON_COMPONENTS } from '../../../components/buttons';

@Component({
  selector: 'minds-card-blog',
  viewBindings: [ Client ],
  properties: ['_blog : object']
})
@View({
  templateUrl: 'src/plugins/blog/card/card.html',
  directives: [ CORE_DIRECTIVES, ROUTER_DIRECTIVES, BUTTON_COMPONENTS, Material, InfiniteScroll ]
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
