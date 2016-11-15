import { ReflectiveInjector, Injectable, Inject } from '@angular/core';
import { Client } from '../../../services/api';
import { Storage } from '../../../services/storage';
import { MINDS_PROVIDERS } from '../../../services/providers';

export class MessengerEncryptionService{

  private on : boolean = false;
  private setup : boolean = false;

  public reKeying : boolean = false;

  constructor(public client : Client, public storage : Storage){
  }

  isOn() : boolean {
    //if(!this.on){
    this.on = !!this.storage.get('encryption-password');
    //}
    return this.on;
  }

  unlock(password : string){
    return new Promise((resolve, reject) => {
      this.client.post('api/v2/keys/unlock', {password: password})
        .then((response : any) => {
          this.storage.set('encryption-password', response.password);
          this.on = true;
          resolve();
        })
        .catch(() => {
          reject();
        });
    });
  }

  isSetup() : boolean {
    //TODO: this won't work on nativescript, so move away from window var.
    //if(!this.setup){
    this.setup = window.Minds.user.chat;
    //}
    return this.setup;
  }

  doSetup(password : string) : Promise<any> {
    return new Promise((resolve, reject) => {
      this.client.post('api/v2/keys/setup', {password: password, download: false})
        .then((response : any) => {
          this.storage.set('encryption-password', response.password);
          this.setup = true;
          this.on = true;
          resolve();
        })
        .catch(() => {
          reject();
        });
      });
  }

  rekey(password : string){
    return new Promise((resolve, reject) => {
      this.client.post('api/v2/keys/setup', {password: password, download: false})
        .then((response : any) => {
          this.storage.set('encryption-password', response.password);
          this.setup = true;
          this.on = true;
          this.reKeying = false;
          resolve();
        })
        .catch(() => {
          reject();
        });
      });
  }

  getEncryptionPassword() : string {
    return this.storage.get('encryption-password');
  }

  logout(){
    this.storage.destroy('encryption-password');
    this.on = false;
  }

  static _(client: Client) {
    return new MessengerEncryptionService(client, new Storage());
  }
}
