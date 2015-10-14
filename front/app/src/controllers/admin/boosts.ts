import { Component, View, CORE_DIRECTIVES, FORM_DIRECTIVES} from 'angular2/angular2';
import { Router, RouteParams, Location, ROUTER_DIRECTIVES } from 'angular2/router';
import { Client, Upload } from 'src/services/api';
import { CARDS } from 'src/controllers/cards/cards';
import { MINDS_GRAPHS } from 'src/components/graphs';
import { Material } from 'src/directives/material';

@Component({
  selector: 'minds-admin-boosts',
  viewBindings: [ Client ],
  host : {
    '(keyup)': 'onKeypress($event)'
  }
})
@View({
  templateUrl: 'templates/admin/boosts.html',
  directives: [ CORE_DIRECTIVES, Material, FORM_DIRECTIVES, ROUTER_DIRECTIVES, MINDS_GRAPHS, CARDS ]
})

export class AdminBoosts {

  entities : Array<any> = [];
  count : number = 0;

  inProgress : boolean = false;
  moreData : boolean = true;
  offset : string = "";

  constructor(public client: Client, public params : RouteParams){
    this.load();
  }

  load(){
    if(this.inProgress)
      return;
    this.inProgress = true;
    var self = this;
    this.client.get('api/v1/admin/boosts', { limit: 24, offset: this.offset })
      .then((response : any) => {
        self.entities = self.entities.concat(response.entities);
        self.count = response.count;

        self.offset = response['load-next'];
        self.inProgress = false;
      })
      .catch((e) => {
        self.inProgress = false;
      });
  }

  accept(){
    
  }

  reject(){

  }

  onKeypress(e){
    console.log(e);
  }

}
