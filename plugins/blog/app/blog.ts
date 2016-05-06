import { Component, Inject } from 'angular2/core';
import { CORE_DIRECTIVES } from 'angular2/common';
import { ROUTER_DIRECTIVES, Router, RouteParams } from "angular2/router";

import { MindsTitle } from '../../services/ux/title';
import { Client } from '../../services/api';
import { SessionFactory } from '../../services/session';
import { Material } from '../../directives/material';
import { InfiniteScroll } from '../../directives/infinite-scroll';
import { MindsBlogListResponse } from '../../interfaces/responses';

import { BlogCard } from './card/card';


@Component({
  selector: 'minds-blog',

  bindings: [MindsTitle ],
  templateUrl: 'src/plugins/blog/list.html',
  directives: [ CORE_DIRECTIVES, ROUTER_DIRECTIVES, Material, InfiniteScroll, BlogCard ]
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

      this.title.setTitle("Blogs");

      switch(this._filter){
        case "trending":
          this.title.setTitle("Trending Blogs");
          break;
        case "featured":
          this.title.setTitle("Featured Blogs");
          break;
        case "all":
          break;
        case "owner":
          break;
        default:
          this._filter2 = this._filter;
          this._filter = "owner";
      }

      this.load();
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

export { BlogView } from './view/view';
export { BlogViewInfinite } from './view/infinite';
export { BlogEdit } from './edit/edit';
