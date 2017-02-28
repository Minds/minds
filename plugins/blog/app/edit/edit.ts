import { Component, Inject } from '@angular/core';
import { Router, ActivatedRoute } from "@angular/router";

import { Subscription } from 'rxjs/Rx';

import { MindsTitle } from '../../../services/ux/title';
import { LICENSES, ACCESS } from '../../../services/list-options';
import { Client, Upload } from '../../../services/api';
import { SessionFactory } from '../../../services/session';


@Component({
  moduleId: module.id,
  selector: 'minds-blog-edit',
  host: {
    'class': 'm-blog'
  },
  templateUrl: 'edit.html'
})

export class BlogEdit {

  minds = window.Minds;
  session = SessionFactory.build();

  guid : string;
  blog : any = {
    guid: 'new',
    title: '',
    description: '',
    access_id: 2,
    license: 'attribution-sharealike-cc',
    fileKey: 'header',
    mature: 0,
    monetized: 0,
  };
  banner : any;
  banner_top : number = 0;
  banner_prompt : boolean = false;
  editing : boolean = true;
  canSave : boolean = true;
  error: string = '';

  licenses = LICENSES;
  access = ACCESS;

  constructor(public client: Client, public upload: Upload, public router: Router, public route: ActivatedRoute, public title: MindsTitle){
  }

  paramsSubscription: Subscription;
  ngOnInit() {
    if(!this.session.isLoggedIn()){
      this.router.navigate(['/login']);
      return;
    }

    this.title.setTitle("New Blog");

    this.paramsSubscription = this.route.params.subscribe(params => {
      if (params['guid']) {
        this.guid = params['guid'];

        this.blog = {
          guid: 'new',
          title: '',
          description: '',
          access_id: 2,
          license: 'all-rights-reserved',
          fileKey: 'header',
          mature: 0,
          monetized: 0,
        };

        this.banner = void 0;
        this.banner_top = 0;
        this.banner_prompt = false;
        this.editing = true;
        this.canSave = true;

        if (this.guid != 'new') {
          this.load();
        }
      }
    });
  }

  ngOnDestroy() {
    this.paramsSubscription.unsubscribe();
  }

  load(){
    var self = this;
    this.client.get('api/v1/blog/' + this.guid, {})
      .then((response : any) => {
        if(response.blog){
          self.blog = response.blog;
          self.guid = response.blog.guid;
          self.title.setTitle(self.blog.title);
        }
      })
      .catch((e) => {

      });
  }

  save(){
    if(!this.canSave)
      return;
    this.canSave = false;
    this.check_for_banner().then(() =>{
      this.upload.post('api/v1/blog/' + this.guid, [this.banner], this.blog)
        .then((response : any) => {
          this.router.navigate(['/blog/view', response.guid]);
          this.canSave = true;
        })
        .catch((e) => {
          this.canSave = true;
        });
    })
    .catch(() => {
      this.client.post('api/v1/blog/' + this.guid, this.blog)
        .then((response : any) => {
          this.router.navigate(['/blog/view', response.guid]);
          this.canSave = true;
        })
        .catch((e) => {
          this.canSave = true;
        });
    });
  }

  add_banner(banner : any){
    var self = this;
    this.banner = banner.file;
    this.blog.header_top = banner.top;
  }

  //this is a nasty hack because people don't want to click save on a banner ;@
  check_for_banner(){
    if(!this.banner)
      this.banner_prompt = true;

    return new Promise((resolve, reject) => {
      if(this.banner)
        return resolve(true);
      setTimeout(() => {
        if(this.banner)
          return resolve(true);
        else
          return reject(false);
      },100);
    });
  }

  toggleMonetized() {
    if (this.blog.mature) {
      return;
    }

    this.blog.monetized = this.blog.monetized ? 0 : 1;
  }

  checkMonetized() {
    if (this.blog.mature) {
      this.blog.monetized = 0;
    }
  }
}
