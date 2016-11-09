import { Component, Inject, ElementRef } from '@angular/core';
import { Router } from "@angular/router";

import { Client } from '../../../services/api';
import { SessionFactory } from '../../../services/session';
import { MindsTitle } from '../../../services/ux/title';
import { ScrollService } from '../../../services/ux/scroll';
import { AnalyticsService } from '../../../services/analytics';

import { MindsBlogResponse } from '../../../interfaces/responses';
import { MindsBlogEntity } from '../../../interfaces/entities';

import { AttachmentService } from '../../../services/attachment';

@Component({
  moduleId: module.id,
  selector: 'm-blog-view',
  inputs: [ 'blog', '_index: index' ],
  host: {
    'class': 'm-blog'
  },
  providers:[ MindsTitle, AttachmentService ],
  templateUrl: 'view.html'
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
        this.router.navigate(['/blog/owner']);
      });
  }

  ngOnDestroy(){
    if(this.scroll_listener)
      this.scroll.unListen(this.scroll_listener);
  }

}
