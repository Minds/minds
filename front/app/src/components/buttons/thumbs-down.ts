import { Component, View, CORE_DIRECTIVES } from 'angular2/angular2';
import { SessionFactory } from 'src/services/session';
import { Client } from "src/services/api";


@Component({
  selector: 'minds-button-thumbs-down',
  properties: ['_object: object']
})
@View({
  template: `
    <a class="mdl-color-text--blue-grey-800" (click)="thumb()" [ng-class]="{'selected': has() }">
      <i class="material-icons">thumb_down</i>
      <counter *ng-if="object['thumbs:down:count'] > 0">{{object['thumbs:down:count']}}</counter>
    </a>
  `,
  directives: [CORE_DIRECTIVES]
})

export class ThumbsDownButton {

  object;
  session = SessionFactory.build();

  constructor(public client : Client) {
  }

  set _object(value : any){
    this.object = value;
  }

  thumb(){
    var self = this;
    this.client.put('api/v1/thumbs/' + this.object.guid + '/down', {});
    if(!this.has()){
      this.object['thumbs:down:user_guids'].push(this.session.getLoggedInUser().guid);
      this.object['thumbs:down:count']++;
    } else {
      for(let key in this.object['thumbs:down:user_guids']){
        if(this.object['thumbs:down:user_guids'][key] == this.session.getLoggedInUser().guid)
          delete this.object['thumbs:down:user_guids'][key];
      }
      this.object['thumbs:down:count']--;
    }
  }

  has(){
    for(var guid of this.object['thumbs:down:user_guids']){
      if(guid == this.session.getLoggedInUser().guid)
        return true;
    }
    return false;
  }

}
