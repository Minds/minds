import { Component, ElementRef, Injector } from '@angular/core';

import { SocketsService } from '../../../services/sockets';
import { Client } from '../../../services/api';
import { Storage } from '../../../services/storage';

import { MessengerConversationDockpanesService } from './service';

@Component({
  moduleId: module.id,
  selector: 'minds-messenger-conversation-dockpanes',
  //inputs: [ 'conversations' ],
  templateUrl: 'conversation-dockpanes.html'
})

export class MessengerConversationDockpanes {

  dockpanes = this.injector.get(MessengerConversationDockpanesService);
  conversations: Array<any> = this.dockpanes.conversations;
  
  constructor(private injector: Injector) {

  }

}

export { MessengerConversationDockpanesService } from './service';
