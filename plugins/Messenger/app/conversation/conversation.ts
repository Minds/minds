import { Component, ElementRef, ChangeDetectorRef, EventEmitter } from 'angular2/core';
import { Router, RouteParams, RouterLink } from "angular2/router";

import { Client } from '../../../services/api';
import { SessionFactory } from '../../../services/session';
import { Storage } from '../../../services/storage';
import { AutoGrow } from '../../../directives/autogrow';
import { Emoji } from '../../../directives/emoji';
import { MindsEmoji } from '../../../components/emoji/emoji';
import { InfiniteScroll } from '../../../directives/infinite-scroll';
import { Material } from '../../../directives/material';
import { SocketsService } from '../../../services/sockets';
import { Tooltip } from '../../../directives/tooltip';
import { MindsTooltip } from '../../../components/tooltip/tooltip';

import { MessengerEncryptionFactory } from '../encryption/service';
import { MessengerEncryption } from '../encryption/encryption';

import { MessengerScrollDirective } from './scroll';
import { MessengerConversationDockpanesFactory } from '../conversation-dockpanes/service';
import { MessengerSounds } from '../sounds/service';

import { MINDS_PIPES } from '../../../pipes/pipes';

@Component({
  selector: 'minds-messenger-conversation',
  properties: [ 'conversation' ],
  templateUrl: 'src/plugins/Messenger/conversation/conversation.html',
  directives: [ InfiniteScroll, RouterLink, Material, AutoGrow, MessengerEncryption, MessengerScrollDirective, Emoji, MindsEmoji, Tooltip, MindsTooltip ],
  pipes: [ MINDS_PIPES ]
})

export class MessengerConversation {

  minds: Minds = window.Minds;
  session = SessionFactory.build();

  encryption = MessengerEncryptionFactory.build(); //ideally we want this loaded from bootstrap func.
  dockpanes = MessengerConversationDockpanesFactory.build();
  sounds  = new MessengerSounds();

  guid : string;
  conversation;
  participants : Array<any> = [];
  messages : Array<any> = [];
  open : boolean = false;
  inProgress : boolean = false;
  live : boolean = true;

  scrollEmitter : EventEmitter<any> = new EventEmitter();

  message : string = "";
  showMessages : boolean = true; //TODO: find a better way to work out if encryption has been set
  blockingActionInProgress: boolean = false;

  constructor(public client : Client, public sockets: SocketsService, public cd: ChangeDetectorRef){

  }

  ngOnInit(){
    if(this.conversation.messages){
      this.messages = this.conversation.messages;
    } else if(this.encryption.isOn() && this.conversation.open) {
      this.load();
    } else if(!this.encryption.isOn()) {
      this.showMessages = false;
    }
    this.listen();
  }

  ngOnDestroy(){
    if (this.conversation.socketRoomName) {
      this.sockets.leave(this.conversation.socketRoomName);
    }
  }

  load(offset : string, finish : string){
    let opts = {
      password: this.encryption.getEncryptionPassword()
    };
    if(offset){
      opts.offset = offset;
    } else if(finish) {
      opts.finish = finish;
    } else {
      this.inProgress = true;
    }
    this.client.get('api/v1/conversations/' + this.conversation.guid, opts)
      .then((response : any) => {
        this.inProgress = false;
        if (response.messages && (offset || finish)) {
          this.messages = this.messages.concat(response.messages);
        } else {
          this.messages = response.messages;
        }
        if(this.conversation.open){
          this.conversation.unread = false;
          this.scrollEmitter.next(true);
        }
      })
      .catch(() => {
        this.inProgress = false;
      });
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

        this.load(null, message.guid);
        this.sounds.play('new');

      });

      this.sockets.subscribe('connect', () => {
        this.live = true;
      });
      this.sockets.subscribe('disconnect', () => {
        this.live = false;
      });

    }
  }

  send(e){
    e.preventDefault();

    this.client.post('api/v1/conversations/' + this.conversation.guid, {
        message: this.message,
        encrypt: true
      })
      .then((response : any) => {
        this.messages.push(response.message);
        this.scrollEmitter.next(true);
        this.sounds.play('send');
      });
    this.message = "";
  }

  deleteHistory() {
    if (!confirm('You cannot UNDO this action. Are you sure?')) {
      // TODO: Maybe a non-process-blocking popup?
      return;
    }

    this.messages = []; // Optimistic
    this.blockingActionInProgress = true;

    this.client.delete('api/v1/conversations/' + this.conversation.guid, {})
      .then((response: any) => {
        this.blockingActionInProgress = false;
      });
  }

  block() {
    // TODO: Block a conversation
  }
}
