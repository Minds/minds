import { Component, ElementRef, ChangeDetectorRef, EventEmitter } from '@angular/core';
import { Router, RouteParams, RouterLink } from "@angular/router-deprecated";

import { Client } from '../../../services/api';
import { SessionFactory } from '../../../services/session';
import { Storage } from '../../../services/storage';
import { AutoGrow } from '../../../directives/autogrow';
import { Emoji } from '../../../directives/emoji';
import { MindsEmoji } from '../../../components/emoji/emoji';
import { InfiniteScroll } from '../../../directives/infinite-scroll';
import { Material } from '../../../directives/material';

import { MessengerConversationDockpanesFactory } from '../conversation-dockpanes/service';


@Component({
  selector: 'minds-messenger-channel-button',
  templateUrl: 'src/plugins/Messenger/channel-button/channel-button.html',
  directives: [ InfiniteScroll, RouterLink, Material ],
  inputs: [ 'user' ]
})

export class MessengerChannelButton {

  minds: Minds = window.Minds;
  session = SessionFactory.build();

  user : any;

  dockpanes = MessengerConversationDockpanesFactory.build();

  constructor(public client : Client){
  }

  chat(){
    let conversation = this.buildConversation();
    console.log(conversation);
    this.dockpanes.open(conversation);
  }

  private buildConversation(){
    return {
      guid: this.permutate(), //TODO: permutate!
      participants: [ this.user ],
      open: true
    };
  }

  private permutate(){
    let participants = [ this.user.guid, this.session.getLoggedInUser().guid ];
    participants.sort((a, b) => { return a[1] - b[1]; });
    return participants.join(':');
  }

}
