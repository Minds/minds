import { Component, View, CORE_DIRECTIVES, FORM_DIRECTIVES} from 'angular2/angular2';
import { Router, RouteParams, Location, ROUTER_DIRECTIVES } from 'angular2/router';
import { Client, Upload } from 'src/services/api';
import { CARDS } from 'src/controllers/cards/cards';
import { MINDS_GRAPHS } from 'src/components/graphs';
import { Material } from 'src/directives/material';

@Component({
  selector: 'minds-admin-boosts',
  viewBindings: [ Client ],
  host: {
    '(keydown)': 'onKeyDown($event)'
  }
})
@View({
  templateUrl: 'templates/admin/boosts.html',
  directives: [ CORE_DIRECTIVES, Material, FORM_DIRECTIVES, ROUTER_DIRECTIVES, MINDS_GRAPHS, CARDS ]
})

export class AdminBoosts {

  entities : Array<any> = [];
  type : string = "newsfeed";
  count : number = 0;
  newsfeed_count : number = 0;
  suggested_count : number = 0;

  inProgress : boolean = false;
  moreData : boolean = true;
  offset : string = "";

  constructor(public client: Client, public params : RouteParams){
    if(params.params['type'])
      this.type = params.params['type'];
    else
      this.type = "newsfeed";
    this.load();
    this.domHack();
  }

  load(){
    if(this.inProgress)
      return;
    this.inProgress = true;
    var self = this;
    this.client.get('api/v1/admin/boosts/' + this.type, { limit: 24, offset: this.offset })
      .then((response : any) => {
        if(!response.entities){
          self.inProgress = false;
          self.moreData = false;
          return;
        }

        self.entities = self.entities.concat(response.entities);
        self.count = response.count;
        self.newsfeed_count = response.newsfeed_count;
        self.suggested_count = response.suggested_count;

        self.offset = response['load-next'];
        self.inProgress = false;
      })
      .catch((e) => {
        self.inProgress = false;
      });
  }

  domHack(){
    var self = this;
    document.addEventListener('keydown', self.onKeypress);
  }

  accept(entity : any = null){
    if(!entity)
      entity = this.entities[0];

    this.client.post('api/v1/admin/boosts/' + entity.boost_id  + '/accept', {
        guid: entity.guid,
        impressions: entity.boost_impressions,
        type: this.type
      })
      .then((response : any) => {

      })
      .catch((e) => {

      });
    this.pop(entity);
  }

  reject(entity : any = null){
    if(!entity)
      entity = this.entities[0];

    this.client.post('api/v1/admin/boosts/' + entity.boost_id  + '/reject', {
        guid: entity.guid,
        impressions: entity.boost_impressions,
        type: this.type
      })
      .then((response : any) => {

      })
      .catch((e) => {

      });
    this.pop(entity);
  }

  /**
   * Remove an entity from the list
   */
  pop(entity){
    for(var i in this.entities){
      if(entity == this.entities[i])
        this.entities.splice(i,1);
    }
    if(this.type == "newsfeed")
      this.newsfeed_count--;
    else if(this.type == "suggested")
      this.suggested_count--;
    if(this.entities.length < 5)
      this.load();
  }

  onKeyDown(e){
    e.preventDefault();
    e.stopPropagation()
    if(e.keyIdentifier == "Left")
      this.accept();
    if(e.keyIdentifier == "Right")
      this.reject();
  }

  /**
   * Hack to make host keypress listen
   */
  onKeypress(e){
    var event = new KeyboardEvent('keydown', e);
    document.getElementsByTagName('minds-admin-boosts')[0].dispatchEvent(event);
  }

  onDestroy(){
    document.removeEventListener('keydown', this.onKeypress);
  }

}
