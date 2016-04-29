import { Component, View, ElementRef } from 'angular2/core';
import { Router, RouteParams, RouterLink } from "angular2/router";

import { SocketsService } from '../../../services/sockets';
import { Client } from '../../../services/api';
import { SessionFactory } from '../../../services/session';
import { Storage } from '../../../services/storage';
import { AutoGrow } from '../../../directives/autogrow';
import { InfiniteScroll } from '../../../directives/infinite-scroll';


@Component({
  selector: 'minds-messenger-conversation',
  properties: [ 'conversation' ],
  templateUrl: 'src/plugins/messenger/conversation/conversation.html',
  directives: [ InfiniteScroll, RouterLink, AutoGrow ]
})

export class MessengerConversation {

  minds: Minds;
  session = SessionFactory.build();

  participants : Array<any> = [];
  open : boolean = false;

  set conversation(conversation : any){
    if(conversation.open)
      this.open = true;
    this.participants = [ conversation ];
  }

}
