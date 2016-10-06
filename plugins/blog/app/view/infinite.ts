import { Component, Inject, ApplicationRef, ChangeDetectorRef } from '@angular/core';
import { CORE_DIRECTIVES } from '@angular/common';
import { Router, RouteParams, ROUTER_DIRECTIVES } from "@angular/router-deprecated";

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
  providers:[ MindsTitle ],
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
    private applicationRef : ApplicationRef, private cd: ChangeDetectorRef){
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
        //this.cd.markForCheck();
        //this.applicationRef.tick();
        this.inProgress = false;
      })
      .catch((e) => {
        if(this.blogs.length == 0){
            this.error = "Sorry, there was a problem loading the blog";
        }
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
