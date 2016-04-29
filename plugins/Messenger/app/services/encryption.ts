

export class MessegerEncryptionService{

  private on : boolean = false;

  isOn() : boolean {
    return this.on;
  }

  unlockEncryption(password : string){
    this.client.post('api/v1/keys/unlock', {password: 'abc123'})
      //.then
  }

}
