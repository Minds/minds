import { Component, View, CORE_DIRECTIVES } from 'angular2/angular2';
import { Client } from "src/services/api";


@Component({
  selector: 'minds-button-comment',
  properties: ['_object: object']
})
@View({
  template: `
    <a class="mdl-color-text--blue-grey-800" [ng-class]="{'selected': object['comments:count'] > 0 }">
      <i class="material-icons">chat_bubble</i>
      <counter *ng-if="object['comments:count'] > 0">{{object['comments:count']}}</counter>
    </a>
  `,
  directives: [CORE_DIRECTIVES]
})

export class CommentButton {

  object;

  constructor(public client : Client) {
  }

  set _object(value : any){
    this.object = value;
  }

}
