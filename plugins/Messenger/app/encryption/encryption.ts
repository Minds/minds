import { Component, ElementRef, EventEmitter, Injector } from '@angular/core';

import { SocketsService } from '../../../services/sockets';
import { Client } from '../../../services/api';
import { SessionFactory } from '../../../services/session';
import { Storage } from '../../../services/storage';

import { MessengerEncryptionService } from './service';

@Component({
  moduleId: module.id,
  selector: 'minds-messenger-encryption',
  host: {
    'class': 'm-messenger-encryption'
  },
  outputs: [ 'on' ],
  templateUrl: 'encryption.html'
})

export class MessengerEncryption {

  minds: Minds;
  session = SessionFactory.build();
  on : EventEmitter<any> = new EventEmitter(true);

  encryption = this.injector.get(MessengerEncryptionService);
  inProgress : boolean = false;
  error : string = "";

  constructor(public client : Client, private injector: Injector){

  }

  unlock(password){
    this.inProgress = true;
    this.error = "";
    this.encryption.unlock(password.value)
      .then(() => {
        this.on.next(true);
        this.inProgress = false;
      })
      .catch(() => {
        this.error = "Wrong password. Please try again."
        this.inProgress = false;
      });
    password.value = '';
  }

  setup(password, password2){
    if(password.value != password2.value){
      this.error = "Your passwords must match";
      return;
    }
    this.inProgress = true;
    this.error = "";
    this.encryption.doSetup(password.value)
      .then(() => {
        this.on.next(true);
        this.inProgress = false;
      })
      .catch(() => {
        this.error = "Sorry, there was a problem.";
        this.inProgress = false;
      });
    password.value = '';
    password2.value = '';
  }

  rekey(password, password2){
    if(password.value != password2.value){
      this.error = "Your passwords must match";
      return;
    }
    this.error = "";
    this.inProgress = true;
    this.encryption.rekey(password.value)
      .then(() => {
        this.on.next(true);
        this.inProgress = false;
      })
      .catch(() => {
        this.error = "Sorry, there was a problem";
        this.inProgress = false;
      });
    password.value = '';
    password2.value = '';
  }

}
