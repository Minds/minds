import { Component, View, CORE_DIRECTIVES, NgStyle, Inject, FORM_DIRECTIVES} from 'angular2/angular2';
import { ROUTER_DIRECTIVES, Router, RouteParams } from "angular2/router";

import { MindsTitle } from '../../services/ux/title';
import { Client } from '../../services/api';
import { SessionFactory } from '../../services/session';
import { Material } from '../../directives/material';
import { InfiniteScroll } from '../../directives/infinite-scroll';
import { MindsBlogListResponse } from '../../interfaces/responses';

import { BlogCard } from './blog-card';

@Component({
  selector: 'minds-blog',
  viewBindings: [ Client ],
  bindings: [ MindsTitle ]
})
@View({
  templateUrl: 'templates/plugins/blog/list.html',
  directives: [ CORE_DIRECTIVES, ROUTER_DIRECTIVES, NgStyle, Material, InfiniteScroll, BlogCard ]
})

export class Blog {

  minds;

  offset : string = "";
  moreData : boolean = true;
  inProgress : boolean = false;
  blogs : Array<any> = [];
  session = SessionFactory.build();
  _filter : string = "featured";
  _filter2 : string = "";

  constructor(public client: Client, public router: Router, public params: RouteParams, public title: MindsTitle){
      this._filter = params.params['filter'];
      this.minds = window.Minds;
      this.load();

      this.title.setTitle("Blogs");

      switch(this._filter){
        case "trending":
          this.title.setTitle("Trending Blogs");
          break;
        case "featured":
          this.title.setTitle("Featured Blogs");
          break;
        case "owner":
          break;
        default:
          this._filter2 = this._filter;
          this._filter = "owner";
      }
  }

  load(refresh : boolean = false){
    if(this.inProgress)
      return false;
    var self = this;
    this.inProgress = true;
    this.client.get('api/v1/blog/' + this._filter + '/' + this._filter2, { limit: 12, offset: this.offset })
      .then((response : MindsBlogListResponse) => {

        if(!response.blogs){
          self.moreData = false;
          self.inProgress = false;
          return false;
        }

        if(refresh){
          self.blogs = response.blogs;
        } else {
          if(self.offset)
            response.blogs.shift();
          for(let blog of response.blogs)
            self.blogs.push(blog);
        }

        self.offset = response['load-next'];
        self.inProgress = false;
      })
      .catch((e)=>{
        self.inProgress = false;
      });
  }
}

export { BlogView } from './blog-view';
export { BlogEdit } from './blog-edit';
