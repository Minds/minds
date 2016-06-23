import { Component, ElementRef } from '@angular/core';
import { CORE_DIRECTIVES } from '@angular/common';
import { Router, RouteParams, RouterLink } from "@angular/router-deprecated";

import { SocketsService } from '../../../services/sockets';
import { Client } from '../../../services/api';
import { Storage } from '../../../services/storage';
import { AutoGrow } from '../../../directives/autogrow';
import { InfiniteScroll } from '../../../directives/infinite-scroll';
import { MessengerConversation } from '../conversation/conversation';

import { MessengerConversationDockpanesFactory } from './service';

@Component({
  selector: 'minds-messenger-conversation-dockpanes',
  //inputs: [ 'conversations' ],
  templateUrl: 'src/plugins/Messenger/conversation-dockpanes/conversation-dockpanes.html',
  directives: [ InfiniteScroll, RouterLink, AutoGrow, MessengerConversation ]
})

export class MessengerConversationDockpanes {

  dockpanes = MessengerConversationDockpanesFactory.build();
  conversations : Array<any> = this.dockpanes.conversations;

}

export { MessengerConversationDockpanesFactory } from './service';
