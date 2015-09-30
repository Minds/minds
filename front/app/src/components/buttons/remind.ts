import { Component, View, CORE_DIRECTIVES } from 'angular2/angular2';
import { Client } from "src/services/api";


@Component({
  selector: 'minds-button-remind',
  properties: ['_object: object']
})
@View({
  template: `
    <a class="mdl-color-text--blue-grey-500" (click)="remind()" [ng-class]="{'selected': object.reminded }">
      <i class="material-icons">repeat</i>
      <counter *ng-if="object['reminds:count'] > 0">{{object['reminds:count']}}</counter>
    </a>
  `,
  directives: [CORE_DIRECTIVES]
})

export class RemindButton {

  object;

  constructor(public client : Client) {
  }

  set _object(value : any){
    this.object = value;
  }

  remind(){
    var self = this;
    this.client.post('api/v1/subscribe/' + this.object.guid, {})
      .then((response : any) => {
          this.object.subscribed = true;
      })
      .catch((e) => {
        this.object.subscribed = false;
      });
  }

}
