import { Component, Inject, ApplicationRef } from 'angular2/core';
import { CORE_DIRECTIVES } from 'angular2/common';
import { Router, RouteParams, ROUTER_DIRECTIVES } from "angular2/router";

import { Client } from '../../../services/api';
import { SessionFactory } from '../../../services/session';
import { Material } from '../../../directives/material';
import { GoogleAds } from '../../../components/ads/google-ads';
import { RevContent } from '../../../components/ads/revcontent';
import { MindsTitle } from '../../../services/ux/title';
import { MindsFatBanner } from '../../../components/banner';
import { Comments } from '../../../controllers/comments/comments';
import { BUTTON_COMPONENTS } from '../../../components/buttons';
import { BlogView } from './view';
import { InfiniteScroll } from '../../../directives/infinite-scroll';


import { MindsBlogResponse } from '../../../interfaces/responses';
import { MindsBlogEntity } from '../../../interfaces/entities';


@Component({
  selector: 'm-blog-view-infinite',
  bindings:[ MindsTitle ],
  templateUrl: 'src/plugins/blog/view/infinite.html',
  directives: [ CORE_DIRECTIVES, ROUTER_DIRECTIVES, BUTTON_COMPONENTS, Material, BlogView, InfiniteScroll ]
})

export class BlogViewInfinite {

  minds;
  guid : string;
  blogs : Array<Object> = [];
  session = SessionFactory.build();
  sharetoggle : boolean = false;

  inProgress : boolean = false;
  moreData : boolean = true;

  error: string = '';

  constructor(public client: Client, public router: Router, public params: RouteParams, public title: MindsTitle,
    private applicationRef : ApplicationRef){
      if(params.params['guid'])
        this.guid = params.params['guid'];
      this.minds = window.Minds;
  }

  ngAfterViewInit(){
      this.load();
  }

  load(refresh : boolean = false){
    if(this.inProgress){
      return false;
    }
    this.inProgress = true;
    //console.log('grabbing ' + this.guid);
    this.client.get('api/v1/blog/' + this.guid, {})
      .then((response : MindsBlogResponse) => {
        if(response.blog){
          this.blogs = [response.blog];
          this.title.setTitle(response.blog.title);
        } else if(this.blogs.length == 0){
          this.error = "Sorry, we couldn't load the blog";
        }
        //hack: ios rerun on low memory
        this.applicationRef.run(() => {
          this.applicationRef.tick();
          setTimeout(() => {
            this.applicationRef.tick()
          }, 100);
        });
        this.inProgress = false;
      })
      .catch((e) => {
        this.inProgress = false;
      });
  }

  loadNextBlog(){
    if(this.inProgress){
      return false;
    }
    this.inProgress = true;

    this.client.get('api/v1/blog/next/' + this.guid)
      .then((response : any) => {
        if(!response.blog){
          this.inProgress = false;
          this.moreData = false;
          return false;
        }
        this.blogs.push(response.blog);
        this.guid = response.blog.guid;
        this.inProgress = false;
      })
      .catch((e) => {
        this.inProgress = false;
      });
  }

}
