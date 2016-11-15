import { Component, Inject } from '@angular/core';
import { ActivatedRoute } from "@angular/router";

import { Subscription } from 'rxjs/Rx';

import { MindsTitle } from '../../services/ux/title';
import { Client } from '../../services/api';
import { SessionFactory } from '../../services/session';
import { MindsBlogListResponse } from '../../interfaces/responses';


@Component({
  moduleId: module.id,
  selector: 'minds-blog',
  templateUrl: 'list.html'
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

  constructor(public client: Client, public route: ActivatedRoute, public title: MindsTitle){
  }

  paramsSubscription: Subscription;
  ngOnInit() {
    this.title.setTitle("Blogs");
    this.minds = window.Minds;

    this.paramsSubscription = this.route.params.subscribe(params => {
      this._filter = params['filter'];
    
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

      this.inProgress = false;
      this.offset = '';
      this.moreData = true;
      this.blogs = [];

      this.load();
    });
  }

  ngOnDestroy() {
    this.paramsSubscription.unsubscribe();
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
