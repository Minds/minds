import { Component, View, CORE_DIRECTIVES } from 'angular2/angular2';
import { Client } from "src/services/api";

@Component({
  selector: 'subscribe-button',
  properties: ['user']
})
@View({
  template: '<button class="minds-subscribe-button" *ng-if="!_user.subscribed" (click)="subscribe()">Subscribe</button> \
    <button class="minds-subscribe-button subscribed" *ng-if="_user.subscribed" (click)="unSubscribe()">Subscribed</button>',
  directives: [ CORE_DIRECTIVES ]
})

export class SubscribeButton{
  _user : any = {
    subscribed: false
  };
  _inprogress : boolean = false;
  _content : any;
  _listener : Function;

  constructor(public client : Client) {
  }

  set user(value : any){
    this._user = value;
  }

  subscribe(){
    var self = this;
    this._user.subscribed = true;
    this.client.post('api/v1/subscribe/' + this._user.guid, {})
      .then((response : any) => {
          this._user.subscribed = true;
      })
      .catch((e) => {
        this._user.subscribed = false;
      });
  }

  unSubscribe(){
    var self = this;
    this._user.subscribed = false;
    this.client.delete('api/v1/subscribe/' + this._user.guid, {})
      .then((response : any) => {
          this._user.subscribed = false;
      })
      .catch((e) => {
        this._user.subscribed = true;
      });
  }

  onDestroy(){
  }

}
