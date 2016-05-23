import { Injector, provide } from 'angular2/core';
import { Client } from '../../../services/api';
import { Storage } from '../../../services/storage';
import { MINDS_PROVIDERS } from '../../../services/providers';
import { HTTP_PROVIDERS } from 'angular2/http';


export class MessengerEncryptionService{

  private on : boolean = false;
  private setup : boolean = false;

  public reKeying : boolean = false;

  constructor(public client : Client, public storage : Storage){
    console.log(client);
  }

  isOn() : boolean {
    //if(!this.on){
    this.on = this.storage.get('encryption-password');
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

}

/**
 * @todo ideally we want this inside the bootstrap
 */
var injector = Injector.resolveAndCreate([
	provide(MessengerEncryptionService, {
    useFactory: (client) => new MessengerEncryptionService(client, new Storage()),
    deps: [ Client ]
  }),
  MINDS_PROVIDERS, HTTP_PROVIDERS
]);

export class MessengerEncryptionFactory {
	static build(){
		return injector.get(MessengerEncryptionService);
	}
}
