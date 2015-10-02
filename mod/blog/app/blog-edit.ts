import { Component, View, Inject, CORE_DIRECTIVES, FORM_DIRECTIVES } from 'angular2/angular2';
import { Router, RouteParams, ROUTER_DIRECTIVES } from "angular2/router";

import { Client, Upload } from 'src/services/api';
import { SessionFactory } from 'src/services/session';
import { MDL_DIRECTIVES } from 'src/directives/material';
import { MindsTinymce } from 'src/components/editors/tinymce';

@Component({
  selector: 'minds-blog-edit',
  viewBindings: [ Client, Upload ]
})
@View({
  templateUrl: 'templates/plugins/blog/edit.html',
  directives: [ CORE_DIRECTIVES, FORM_DIRECTIVES, ROUTER_DIRECTIVES, MindsTinymce, MDL_DIRECTIVES ]
})

export class BlogEdit {

  minds;

  guid : string;
  blog : Object = {
    guid: 'new',
    title: '',
    description: '',
    access_id: 2
  };
  header : any;

  constructor(public client: Client, public upload: Upload, public router: Router, public params: RouteParams){
      if(params.params['guid'])
        this.guid = params.params['guid'];
      this.minds = window.Minds;
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
        }
      })
      .catch((e) => {

      });
  }

  save(){
    var self = this;
    this.client.post('api/v1/blog/' + this.guid, this.blog)
      .then((response : any) => {
        console.log(response);
        if(self.header)
          self.uploadHeader();
        else
          self.router.navigate('/blog/view/' + response.guid);
      })
      .catch((e) => {

      });
  }

  addHeader(file){
    this.header = file.files[0];
  }

  uploadHeader() : boolean {
    if(!this.header)
      return true;
    var self = this;
    this.upload.post('api/v1/blog/' + this.guid, [this.header], { filekey: 'header'}, (progress) => {
      console.log('progress update');
      console.log(progress);
      })
			.then((response : any) => {
        self.router.navigate('/blog/view/' + response.guid);
			})
			.catch(function(e){
				console.error(e);
			});
  }

}
