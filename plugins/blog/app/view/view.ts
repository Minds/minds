import { Component, Inject, ElementRef } from '@angular/core';
import { CORE_DIRECTIVES } from '@angular/common';
import { Router, RouteParams, ROUTER_DIRECTIVES } from "@angular/router-deprecated";

import { Client } from '../../../services/api';
import { SessionFactory } from '../../../services/session';
import { Material } from '../../../directives/material';
import { Hovercard } from '../../../directives/hovercard';
import { GoogleAds } from '../../../components/ads/google-ads';
import { RevContent } from '../../../components/ads/revcontent';
import { MindsTitle } from '../../../services/ux/title';
import { MindsFatBanner } from '../../../components/banner';
import { Comments } from '../../../controllers/comments/comments';
import { BUTTON_COMPONENTS } from '../../../components/buttons';
import { ShareModal, ConfirmModal } from '../../../components/modal/modal';
import { SocialIcons } from '../../../components/social-icons/social-icons';
import { InfiniteScroll } from '../../../directives/infinite-scroll';
import { ScrollService } from '../../../services/ux/scroll';
import { AnalyticsService } from '../../../services/analytics';

import { MindsBlogResponse } from '../../../interfaces/responses';
import { MindsBlogEntity } from '../../../interfaces/entities';

import { AttachmentService } from '../../../services/attachment';

@Component({
  selector: 'm-blog-view',
  inputs: [ 'blog', '_index: index' ],
  host: {
    'class': 'm-blog'
  },
  providers:[ MindsTitle, AttachmentService ],
  templateUrl: 'src/plugins/blog/view/view.html',
  directives: [ CORE_DIRECTIVES, ROUTER_DIRECTIVES, BUTTON_COMPONENTS, Material, Comments, MindsFatBanner,
    GoogleAds, RevContent, ShareModal, SocialIcons, InfiniteScroll, Hovercard, ConfirmModal ]
})

export class BlogView {

  minds;
  guid : string;
  blog : MindsBlogEntity;
  session = SessionFactory.build();
  sharetoggle : boolean = false;
  deleteToggle: boolean = false;
  element;

  inProgress : boolean = false;
  moreData : boolean = true;
  activeBlog : number = 0;

  visible : boolean = false;
  index : number = 0;

  scroll_listener;

  constructor(public client: Client, public router: Router, _element : ElementRef,  public scroll: ScrollService, public title: MindsTitle, public attachment: AttachmentService){
      this.minds = window.Minds;
      this.element = _element.nativeElement;
  }

  ngOnInit(){
    this.isVisible();
  }

  isVisible(){
    //listens every 0.6 seconds
    this.scroll_listener = this.scroll.listen((e) => {
      var bounds = this.element.getBoundingClientRect();
      if(bounds.top < this.scroll.view.clientHeight && bounds.top + bounds.height > this.scroll.view.clientHeight){
        var url = this.minds.site_url + 'blog/view/' + this.blog.guid;
        if(!this.visible){
          window.history.pushState(null, this.blog.title, url);
          this.title.setTitle(this.blog.title);
          AnalyticsService.send('pageview', { 'page' : '/blog/view/' + this.blog.guid});
        }
        this.visible = true;
      } else {
        this.visible = false;
      }
    }, 0, 300);
  }

  set _index(value : number){
    this.index = value;
    if(this.index == 0){
      this.visible = true;
    }
  }

  delete(){
    this.client.delete('api/v1/blog/' + this.blog.guid)
      .then((response : any) => {
        this.router.navigate(['/Blog', {filter: 'owner'}]);
      });
  }

  ngOnDestroy(){
    if(this.scroll_listener)
      this.scroll.unListen(this.scroll_listener);
  }

}
