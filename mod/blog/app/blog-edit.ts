import { Component, View, Inject, CORE_DIRECTIVES, FORM_DIRECTIVES } from 'angular2/angular2';
import { Router, RouteParams, ROUTER_DIRECTIVES } from "angular2/router";

import { MindsTitle } from 'src/services/ux/title';
import { Client, Upload } from 'src/services/api';
import { SessionFactory } from 'src/services/session';
import { MDL_DIRECTIVES } from 'src/directives/material';
import { MindsTinymce } from 'src/components/editors/tinymce';
import { MindsBanner } from 'src/components/banner'
import { AutoGrow } from 'src/directives/autogrow';

@Component({
  selector: 'minds-blog-edit',
  viewBindings: [ Client, Upload ],
  bindings: [ MindsTitle ]
})
@View({
  templateUrl: 'templates/plugins/blog/edit.html',
  directives: [ CORE_DIRECTIVES, FORM_DIRECTIVES, ROUTER_DIRECTIVES, MindsTinymce, MDL_DIRECTIVES, AutoGrow, MindsBanner]
})

export class BlogEdit {

  minds;

  guid : string;
  blog : any = {
    guid: 'new',
    title: '',
    description: '',
    access_id: 2,
    fileKey: 'header'
  };
  banner : any;
  banner_top : number = 0;
  editing : boolean = true;

  constructor(public client: Client, public upload: Upload, public router: Router, public params: RouteParams, public title: MindsTitle){
      if(params.params['guid'])
        this.guid = params.params['guid'];
      this.minds = window.Minds;
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
    var self = this;
    this.upload.post('api/v1/blog/' + this.guid, [this.banner], this.blog)
      .then((response : any) => {
        self.router.navigate(['/Blog-View', {guid: response.guid}]);
      })
      .catch((e) => {

      });
  }

  add_banner(banner : any){
    var self = this;
    this.banner = banner.file;
    this.blog.header_top = banner.top;
  }

}
