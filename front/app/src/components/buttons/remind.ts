import { Component, View, CORE_DIRECTIVES } from 'angular2/angular2';
import { SessionFactory } from 'src/services/session';
import { Client } from "src/services/api";


@Component({
  selector: 'minds-button-remind',
  properties: ['_object: object']
})
@View({
  template: `
    <a class="mdl-color-text--blue-grey-500" (click)="remind()" [ng-class]="{'selected': (object.reminds > 0) }">
      <i class="material-icons">repeat</i>
      <counter *ng-if="object.reminds > 0">{{object.reminds}}</counter>
    </a>
  `,
  directives: [CORE_DIRECTIVES]
})

export class RemindButton {

  object;
  session = SessionFactory.build();

  constructor(public client : Client) {
  }

  set _object(value : any){
    this.object = value;
  }

  remind(){
    var self = this;
    this.client.post('api/v1/newsfeed/remind/' + this.object.guid, {})
      .then((response : any) => {
          console.log(response);
          console.log(this.object);
          this.object.reminds++;
      })
      .catch((e) => {
        console.log(e);
      });
  }

}
