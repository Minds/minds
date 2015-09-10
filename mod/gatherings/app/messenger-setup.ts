import { Component, View, NgFor, NgIf, NgClass, Observable, FORM_DIRECTIVES} from 'angular2/angular2';
import { RouterLink } from "angular2/router";
import { Client } from 'src/services/api';
import { SessionFactory } from 'src/services/session';
import { Storage } from 'src/services/storage';
import { Material } from 'src/directives/material';
import { MindsKeysResponse } from './interfaces/responses';

@Component({
  selector: 'minds-messenger-setup',
  viewBindings: [ Client ]
})
@View({
  templateUrl: 'templates/plugins/gatherings/messenger-setup.html',
  directives: [ NgFor, NgIf, NgClass, Material, FORM_DIRECTIVES]
})

export class MessengerSetup {
  session = SessionFactory.build();
  configured: boolean = false;
  data: any = {};
  storage: Storage;

  constructor(public client: Client){
    this.storage = new Storage();
  }

  setupTest(passwords){
    console.log(passwords);
    console.log(passwords.value);
    passwords.value = {};
    return true;
  }

  unlock(password) {
    console.log("UNLOCK: "+password);
    var self = this;
    this.client.get('api/v1/keys', {
      password: password.value.password,
      new_password: 'abc123'
    })
    .then((data : MindsKeysResponse) => {
      if (data.key) {
        self.storage.set('private-key', data.key);
        //$state.go('tab.chat');
      } else {
        alert({
          title: 'Ooops..',
          template: 'We couldn\'t unlock your chat. Please check your password is correct.'
        });
      }
    })
    .catch((error) =>{
    });
  };

  setup(passwords) {
    var self = this;
    if (!passwords.value.password1) {
      alert({
        title: 'Ooops..',
        template: 'You must enter a password.'
      });
      console.log("You must enter a password.");
      return false;
    }
    if (passwords.value.password1 != passwords.value.password2) {
      alert({
        title: 'Ooops..',
        template: 'Your passwords must match.'
      });
      console.log("Your passwords must match.");
      return false;
    }

    this.client.post('api/v1/keys/setup', {
      password: passwords.value.password1
    })
    .then((data : MindsKeysResponse) =>{
      console.log("Data: " + data.key+ " Storage: "+ self.storage);
      if (data.key) {
        self.storage.set('private-key', data.key);
      } else {
        alert({
          title: 'Ooops..',
          template: 'We couldn\'t unlock your chat. Please check your password is correct.'
        });
      }
    })
    .catch((error) =>{
      console.log(error);
    });
  };
}
