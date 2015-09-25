import { Component, View, CORE_DIRECTIVES } from 'angular2/angular2';
import { SessionFactory } from 'src/services/session';
import { Client } from "src/services/api";


@Component({
  selector: 'minds-button-thumbs-up',
  properties: ['_object: object']
})
@View({
  template: `
    <a class="mdl-color-text--blue-grey-800" (click)="thumb()" [ng-class]="{'selected': has() }">
      <i class="material-icons">thumb_up</i>
      <counter *ng-if="object['thumbs:up:count'] > 0">{{object['thumbs:up:count']}}</counter>
    </a>
  `,
  directives: [CORE_DIRECTIVES]
})

export class ThumbsUpButton {

  object;
  session = SessionFactory.build();

  constructor(public client : Client) {
  }

  set _object(value : any){
    this.object = value;
  }

  thumb(){
    var self = this;
    this.client.put('api/v1/thumbs/' + this.object.guid + '/up', {});
    if(!this.has()){
      this.object['thumbs:up:user_guids'].push(this.session.getLoggedInUser().guid);
      this.object['thumbs:up:count']++;
    } else {
      for(let key in this.object['thumbs:up:user_guids']){
        if(this.object['thumbs:up:user_guids'][key] == this.session.getLoggedInUser().guid)
          delete this.object['thumbs:up:user_guids'][key];
      }
      this.object['thumbs:up:count']--;
    }
  }

  has(){
    for(var guid of this.object['thumbs:up:user_guids']){
      if(guid == this.session.getLoggedInUser().guid)
        return true;
    }
    return false;
  }

}
