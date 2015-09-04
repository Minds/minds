import { Component, View, NgFor, NgIf, NgClass, Inject, FORM_DIRECTIVES} from 'angular2/angular2';
import { RouterLink, Router, RouteParams } from "angular2/router";

import { Client } from 'src/services/api';
import { SessionFactory } from 'src/services/session';
import { Material } from 'src/directives/material';

@Component({
  selector: 'minds-blog',
  viewBindings: [ Client ]
})
@View({
  templateUrl: 'templates/plugins/blog/list.html',
  directives: [ NgFor, NgIf, NgClass, Material, RouterLink ]
})

export class Blog {

  offset : string = "";
  moreDate : boolean = true;
  inProgress : boolean = false;
  blogs : Array<any> = [];
  session = SessionFactory.build();
  _filter : string = "featured";

  constructor(public client: Client,
    @Inject(Router) public router: Router,
    @Inject(RouteParams) public params: RouteParams
    ){
      this._filter = params.params['filter'];
      this.minds = window.Minds;
      this.load();
  }

  load(refresh : boolean = false){

    var self = this;
    this.inProgress = true;
    this.client.get('api/v1/blog/' + this._filter, { limit: 12, offset: this.offset})
      .then((response) => {
        console.log(response);
        return;
        if(!response.groups){
          self.moreData = false;
          self.inProgress = false;
          return false;
        }

        if(refresh){
          self.groups = response.groups;
        } else {
          if(self.offset)
            response.groups.shift();
          for(let group of response.groups)
            self.groups.push(group);
        }

        self.offset = response['load-next'];
        self.inProgress = false;
      })
      .catch((e)=>{

      });
  }
}

export { BlogView } from './blog-view';
export { BlogEdit } from './blog-edit';
