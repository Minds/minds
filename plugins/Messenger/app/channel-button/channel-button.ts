import { Component, ElementRef, ChangeDetectorRef, EventEmitter, Injector } from '@angular/core';

import { Client } from '../../../services/api';
import { SessionFactory } from '../../../services/session';
import { Storage } from '../../../services/storage';

import { MessengerConversationDockpanesService } from '../conversation-dockpanes/service';

@Component({
  moduleId: module.id,
  selector: 'minds-messenger-channel-button',
  templateUrl: 'channel-button.html',
  inputs: [ 'user' ]
})

export class MessengerChannelButton {

  minds: Minds = window.Minds;
  session = SessionFactory.build();

  user : any;

  dockpanes = this.injector.get(MessengerConversationDockpanesService);

  constructor(public client : Client, private injector: Injector){
  }

  chat(){
    let conversation = this.buildConversation();
    console.log(conversation);
    this.dockpanes.open(conversation);
  }

  private buildConversation(){
    return {
      guid: this.permutate(),
      participants: [ this.user ],
      open: true
    };
  }

  private permutate(){
    let participants = [ this.user.guid, this.session.getLoggedInUser().guid ];
    participants.sort((a, b) => a < b ? -1 : 1);
    return participants.join(':');
  }

}
