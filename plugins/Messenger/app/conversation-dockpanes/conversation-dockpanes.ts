import { Component, View, ElementRef } from 'angular2/core';
import { CORE_DIRECTIVES } from 'angular2/common';
import { Router, RouteParams, RouterLink } from "angular2/router";

import { SocketsService } from '../../../services/sockets';
import { Client } from '../../../services/api';
import { Storage } from '../../../services/storage';
import { AutoGrow } from '../../../directives/autogrow';
import { InfiniteScroll } from '../../../directives/infinite-scroll';
import { MessengerConversation } from '../conversation/conversation';


@Component({
  selector: 'minds-messenger-conversation-dockpanes',
  inputs: [ 'conversations' ],
  templateUrl: 'src/plugins/messenger/conversation-dockpanes/conversation-dockpanes.html',
  directives: [ InfiniteScroll, RouterLink, AutoGrow, MessengerConversation ]
})

export class MessengerConversationDockpanes {

}
