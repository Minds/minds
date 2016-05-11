
export class MessengerSounds{

  private sounds = {
    new:  new Audio(window.Minds.cdn_url + 'src/plugins/Messenger/sounds/newmsg.mp3'),
    send: new Audio(window.Minds.cdn_url + 'src/plugins/Messenger/sounds/sndmsg.mp3'),
  }

  play(sound : string){
    this.sounds[sound].play();
  }

}
