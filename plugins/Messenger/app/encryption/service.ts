import { Injector, provide } from 'angular2/core';
import { Client } from '../../../services/api';
import { MINDS_PROVIDERS } from '../../../services/providers';
import { HTTP_PROVIDERS } from 'angular2/http';


export class MessengerEncryptionService{

  private on : boolean = false;
  private setup : boolean = false;

  constructor(public client : Client){
    console.log(client);
  }

  isOn() : boolean {
    return this.on;
  }

  unlock(password : string){
    this.client.post('api/v1/keys/unlock', {password: password})
      //.then
  }

  isSetup() : boolean {
    //TODO: this won't work on nativescript, so move away from window var.
    if(!this.setup){
      this.setup = window.Minds.user.chat;
    }
    return this.setup;
  }

  doSetup(password : string) : boolean {
    this.client.post('api/v1/keys/setup', {password: password})
  }

}

/**
 * @todo ideally we want this inside the bootstrap
 */
var injector = Injector.resolveAndCreate([
	provide(MessengerEncryptionService, {
    useFactory: (client) => new MessengerEncryptionService(client),
    deps: [ Client ]
  }),
  MINDS_PROVIDERS, HTTP_PROVIDERS
]);

export class MessengerEncryptionFactory {
	static build(){
		return injector.get(MessengerEncryptionService);
	}
}
