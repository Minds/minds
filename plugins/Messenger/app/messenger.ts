import { Component, Injector } from '@angular/core';

import { SocketsService } from '../../services/sockets';
import { Storage } from '../../services/storage';
import { Client } from '../../services/api';
import { SessionFactory } from '../../services/session';

import { MessengerConversationDockpanesService } from './conversation-dockpanes/conversation-dockpanes';
import { MessengerEncryptionService } from './encryption/service';
import { MessengerSounds } from './sounds/service';


@Component({
  moduleId: module.id,
  selector: 'minds-messenger',
  templateUrl: 'messenger.html'
})

export class Messenger {

  session = SessionFactory.build();
  encryption = this.injector.get(MessengerEncryptionService);
  sounds = new MessengerSounds();

  dockpanes = this.injector.get(MessengerConversationDockpanesService);

  minds: Minds = window.Minds;
  storage: Storage = new Storage();

  constructor(public client: Client, public sockets: SocketsService, private injector: Injector){
  }

}
export { MessengerConversation } from './conversation/conversation';
