import { Component } from '@angular/core';
import { CORE_DIRECTIVES, FORM_DIRECTIVES } from '@angular/common';
import { ROUTER_DIRECTIVES, Router, RouteParams, RouterLink } from "@angular/router-deprecated";

import { SocketsService } from '../../services/sockets';
import { MessengerConversation } from "./conversation/conversation";
import { Storage } from '../../services/storage';
import { Client } from '../../services/api';
import { SessionFactory } from '../../services/session';
import { BUTTON_COMPONENTS } from '../../components/buttons';
import { Material } from '../../directives/material';
import { InfiniteScroll } from '../../directives/infinite-scroll';

import { MessengerUserlist } from './userlist/userlist';
import { MessengerConversationDockpanes, MessengerConversationDockpanesFactory } from './conversation-dockpanes/conversation-dockpanes';
import { MessengerEncryptionFactory } from './encryption/service';
import { MessengerSounds } from './sounds/service';


@Component({
  selector: 'minds-messenger',
  templateUrl: 'src/plugins/Messenger/messenger.html',
  directives: [ BUTTON_COMPONENTS, Material, RouterLink, MessengerUserlist, MessengerConversationDockpanes ]
})

export class Messenger {

  session = SessionFactory.build();
  encryption = MessengerEncryptionFactory.build(); //ideally we want this loaded from bootstrap func.
  sounds = new MessengerSounds();

  dockpanes = MessengerConversationDockpanesFactory.build();

  minds: Minds = window.Minds;
  storage: Storage = new Storage();

  constructor(public client: Client, public sockets: SocketsService){
  }

}
export { MessengerConversation } from './conversation/conversation';
