import { Component, View, CORE_DIRECTIVES, FORM_DIRECTIVES, EventEmitter} from 'angular2/angular2';
import { RouterLink } from "angular2/router";
import { Client } from 'src/services/api';
import { SessionFactory } from 'src/services/session';
import { Storage } from 'src/services/storage';
import { Material } from 'src/directives/material';

import { MindsKeysResponse } from './interfaces/responses';
import { MindsChannelResponse } from 'src/interfaces/responses';

@Component({
  selector: 'minds-messenger-setup',
  viewBindings: [ Client ],
  events: [ 'done' ]
})
@View({
  templateUrl: 'templates/plugins/gatherings/messenger-setup.html',
  directives: [ CORE_DIRECTIVES, Material, FORM_DIRECTIVES ]
})

export class MessengerSetup {

  session = SessionFactory.build();
  storage: Storage = new Storage;

  configured: boolean = false;
  show : boolean = false;
  data: any = {};

  inProgress : boolean = false;
  error : string = "";
  done : EventEmitter = new EventEmitter;

  constructor(public client: Client){
    this.check();
  }

  check(){
    var self = this;
    if(this.session.getLoggedInUser().chat){
      self.configured = true;
      this.show = true;
      return true;
    }
    this.client.get('api/v1/channel/me', {})
      .then((response : MindsChannelResponse) => {
        if (response.channel.chat)
          self.configured = true;
        self.show = true;
      });
  }

  /**
   * Unlock a users chat messages
   */
  unlock(password) {
    var self = this;
    this.inProgress = true;
    this.client.get('api/v1/keys',
      {
        password: password.value.password,
        new_password: 'abc123'
      })
      .then((data : MindsKeysResponse) => {

        if (!data.key) {
          self.error = 'We couldn\'t unlock your chat. Please check your password is correct.';
          return false;
        }

        self.storage.set('private-key', data.key);
        this.inProgress = false;
        self.done.next(true);
      })
      .catch((error) =>{
        this.inProgress = false;
        console.log(error);
      });
  }

  /**
   * Setup a users chat
   */
  setup(passwords) {
    var self = this;
    this.inProgress = true;

    if (!passwords.value.password1) {
      this.error = 'You need to enter a password';
      return false;
    }

    if (passwords.value.password1 != passwords.value.password2) {
      this.error = "Your passwords must match.";
      return false;
    }

    this.client.post('api/v1/keys/setup', { password: passwords.value.password1 })
      .then((data : MindsKeysResponse) =>{

        if (!data.key){
          self.error = 'We couldn\'t unlock your chat. Please check your password is correct.';
          return false;
        }



        self.storage.set('private-key', data.key);
        self.done.next(true);
        this.inProgress = false;
      })
      .catch((error) =>{
        this.inProgress = false;
        console.log(error);
      });
  }

}
