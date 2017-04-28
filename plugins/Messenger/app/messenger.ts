import { Component, Injector, ViewChild } from '@angular/core';

import { SocketsService } from '../../services/sockets';
import { Storage } from '../../services/storage';
import { Client } from '../../services/api';
import { SessionFactory } from '../../services/session';

import { MessengerConversationDockpanesService } from './conversation-dockpanes/conversation-dockpanes';
import { MessengerEncryptionService } from './encryption/service';
import { MessengerSounds } from './sounds/service';

import { MessengerUserlist } from './userlist/userlist';
import { MessengerSetupChat } from './setup-chat/setup-chat';


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

  @ViewChild('userList') userList: MessengerUserlist;
  @ViewChild('setupChat') setupChat: MessengerSetupChat;

  constructor(public client: Client, public sockets: SocketsService, private injector: Injector){
  }

  ngAfterViewInit() {
    // @todo: get rid of this ugly global window hack
    (<any>window).openMessengerWindow = () => {
      this.open();
    };
  }

  ngOnDestroy() {
    (<any>window).openMessengerWindow = function () { };
  }

  open(guid: any = null /* for future use */) {
    if (this.userList) {
      this.userList.openPane();
    } else if (this.setupChat) {
      this.setupChat.openPane();
    }
  }

}
export { MessengerConversation } from './conversation/conversation';
