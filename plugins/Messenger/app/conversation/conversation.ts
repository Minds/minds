import { Component, ElementRef, ChangeDetectorRef } from 'angular2/core';
import { Router, RouteParams, RouterLink } from "angular2/router";

import { Client } from '../../../services/api';
import { SessionFactory } from '../../../services/session';
import { Storage } from '../../../services/storage';
import { AutoGrow } from '../../../directives/autogrow';
import { Emoji } from '../../../directives/emoji';
import { EmojiService } from '../../../services/emoji';
import { InfiniteScroll } from '../../../directives/infinite-scroll';
import { SocketsService } from '../../../services/sockets';

import { MessengerEncryptionFactory } from '../encryption/service';
import { MessengerEncryption } from '../encryption/encryption';

import { MessengerScrollDirective } from './scroll';
import { MessengerConversationDockpanesFactory } from '../conversation-dockpanes/service';

import { MINDS_PIPES } from '../../../pipes/pipes';

@Component({
  selector: 'minds-messenger-conversation',
  properties: [ 'conversation' ],
  templateUrl: 'src/plugins/Messenger/conversation/conversation.html',
  directives: [ InfiniteScroll, RouterLink, AutoGrow, MessengerEncryption, MessengerScrollDirective, Emoji ],
  pipes: [ MINDS_PIPES ]
})

export class MessengerConversation {

  minds: Minds = window.Minds;
  session = SessionFactory.build();

  encryption = MessengerEncryptionFactory.build(); //ideally we want this loaded from bootstrap func.
  dockpanes = MessengerConversationDockpanesFactory.build();

  guid : string;
  conversation;
  participants : Array<any> = [];
  messages : Array<any> = [];
  open : boolean = false;

  message : string = "";

  constructor(public client : Client, public sockets: SocketsService, public cd: ChangeDetectorRef, public emojiService: EmojiService){

  }

  ngOnInit(){
    if(this.conversation.messages){
      this.messages = this.conversation.messages;
    } else {
      this.load();
    }
    this.listen();
  }

  ngOnDestroy(){
    if (this.conversation.socketRoomName) {
      this.sockets.leave(this.conversation.socketRoomName);
    }
  }

  load(){
    this.client.get('api/v1/conversations/' + this.conversation.guid, {
        password: this.encryption.getEncryptionPassword()
      })
      .then((response : any) => {
        if (response.messages) {
          this.messages = response.messages;
        }
      })
  }

  listen() {
    if (this.conversation.socketRoomName) {
      this.sockets.join(this.conversation.socketRoomName);

      this.listener = this.sockets.subscribe('pushConversationMessage', (guid, message) => {
        if (guid != this.conversation.guid) {
          return;
        }

        if (this.session.getLoggedInUser().guid == message.ownerObj.guid) {
          return;
        }

        this.messages.push(message);
        this.cd.markForCheck();
      });
    }
  }

  send(e){
    e.preventDefault();

    this.emojiService.close();

    this.client.post('api/v1/conversations/' + this.conversation.guid, {
        message: this.message,
        encrypt: true
      })
      .then((response : any) => {
        this.messages.push(response.message);
      });
    this.message = "";
  }
}
