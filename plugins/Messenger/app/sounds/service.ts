import { Storage } from '../../../services/storage';

export class MessengerSounds{

  private storage = new Storage();

  private sounds = {
    new:  new Audio(window.Minds.cdn_url + 'src/plugins/Messenger/sounds/newmsg.mp3'),
    send: new Audio(window.Minds.cdn_url + 'src/plugins/Messenger/sounds/sndmsg.mp3'),
  }

  play(sound : string){
    if(this.canPlay())
      this.sounds[sound].play();
  }

  canPlay(){
    if(this.storage.get('muted'))
      return false;
    return true;
  }

  mute(){
    this.storage.set('muted', true);
  }

  unmute(){
    this.storage.destroy('muted');
  }

}
