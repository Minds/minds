import { Component, View, Inject } from 'angular2/core';
import { CORE_DIRECTIVES, FORM_DIRECTIVES } from 'angular2/common';
import { Router, RouteParams, ROUTER_DIRECTIVES } from "angular2/router";

import { MindsTitle } from '../../../services/ux/title';
import { LICENSES, ACCESS } from '../../../services/list-options';
import { Client, Upload } from '../../../services/api';
import { SessionFactory } from '../../../services/session';
import { MDL_DIRECTIVES } from '../../../directives/material';
import { MindsTinymce } from '../../../components/editors/tinymce';
import { MindsBanner } from '../../../components/banner'
import { AutoGrow } from '../../../directives/autogrow';


@Component({
  selector: 'minds-blog-edit',
  
  bindings: [MindsTitle ]
})
@View({
  templateUrl: 'src/plugins/blog/edit/edit.html',
  directives: [ CORE_DIRECTIVES, FORM_DIRECTIVES, ROUTER_DIRECTIVES, MindsTinymce, MDL_DIRECTIVES, AutoGrow, MindsBanner]
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
    license: 'all-rights-reserved',
    fileKey: 'header'
  };
  banner : any;
  banner_top : number = 0;
  banner_prompt : boolean = false;
  editing : boolean = true;
  canSave : boolean = true;

  licenses = LICENSES;
  access = ACCESS;

  constructor(public client: Client, public upload: Upload, public router: Router, public params: RouteParams, public title: MindsTitle){
    if(!this.session.isLoggedIn()){
      router.navigate(['/Login']);
      return;
    }

    if(params.params['guid'])
      this.guid = params.params['guid'];
    this.title.setTitle("New Blog");

    if(this.guid != 'new')
      this.load();
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
          this.router.navigate(['/Blog-View', {guid: response.guid}]);
          this.canSave = true;
        })
        .catch((e) => {
          this.canSave = true;
        });
    })
    .catch(() => {
      this.client.post('api/v1/blog/' + this.guid, this.blog)
        .then((response : any) => {
          this.router.navigate(['/Blog-View', {guid: response.guid}]);
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

}
