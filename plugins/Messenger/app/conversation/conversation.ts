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

import { MessengerScrollDirective } from '../scroll';
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

  chatNotice: string = '';

  socketSubscriptions = {
    pushConversationMessage: null,
    clearConversation: null,
    connect: null,
    disconnect: null,
    block: null,
    unblock: null
  }

  blocked: boolean = false;

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
    this.unListen();
  }

  load(offset?: string, finish?: string){
    let opts: any = {
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
        } else if (response.messages) {
          this.messages = response.messages;
        } else {
          this.messages = [];
        }
        if(this.conversation.open){
          this.conversation.unread = false;
          this.scrollEmitter.next(true);
        }

        this.blocked = !!response.blocked;
      })
      .catch(() => {
        this.inProgress = false;
      });
  }

  listen() {
    if (this.conversation.socketRoomName) {

      this.sockets.join(this.conversation.socketRoomName);

      this.socketSubscriptions.pushConversationMessage = this.sockets.subscribe('pushConversationMessage', (guid, message) => {
        if (guid != this.conversation.guid) {
          return;
        }

        if (this.session.getLoggedInUser().guid == message.ownerObj.guid) {
          return;
        }

        this.load(null, message.guid);
        this.sounds.play('new');

      });

      this.socketSubscriptions.clearConversation = this.sockets.subscribe('clearConversation', (guid, actor) => {
        if (guid != this.conversation.guid) {
          return;
        }

        this.messages = [];
        this.chatNotice = `${actor.name} cleared chat history`;

        this.sounds.play('new');
      });

      this.socketSubscriptions.block = this.sockets.subscribe('block', (guid) => {
        if (!this.hasParticipant(guid)) {
          return;
        }

        this.blocked = true;
      });

      this.socketSubscriptions.unblock = this.sockets.subscribe('unblock', (guid) => {
        if (!this.hasParticipant(guid)) {
          return;
        }

        this.blocked = false;
      });

      this.socketSubscriptions.connect = this.sockets.subscribe('connect', () => {
        this.live = true;
      });

      this.socketSubscriptions.disconnect = this.sockets.subscribe('disconnect', () => {
        this.live = false;
      });

    }
  }

  unListen() {
    if (this.conversation.socketRoomName) {
      this.sockets.leave(this.conversation.socketRoomName);
    }

    if (this.socketSubscriptions.pushConversationMessage) {
      this.socketSubscriptions.pushConversationMessage.unsubscribe();
    }

    if (this.socketSubscriptions.clearConversation) {
      this.socketSubscriptions.clearConversation.unsubscribe();
    }

    if (this.socketSubscriptions.connect) {
      this.socketSubscriptions.connect.unsubscribe();
    }

    if (this.socketSubscriptions.disconnect) {
      this.socketSubscriptions.disconnect.unsubscribe();
    }

    if (this.socketSubscriptions.block) {
      this.socketSubscriptions.disconnect.unsubscribe();
    }

    if (this.socketSubscriptions.unblock) {
      this.socketSubscriptions.disconnect.unsubscribe();
    }
  }

  send(e){
    e.preventDefault();

    if (this.blocked) {
      return;
    }

    let newLength = this.messages.push({ // Optimistic
      optimisticGuess: true,
      owner: this.session.getLoggedInUser(),
      message: this.message,
      time_created: Math.floor(Date.now() / 1000)
    }), currentIndex = newLength - 1;

    this.client.post('api/v1/conversations/' + this.conversation.guid, {
        message: this.message,
        encrypt: true
      })
      .then((response : any) => {
        this.messages[currentIndex] = response.message;
        this.scrollEmitter.next(true);
      })
      .catch(e => {
        console.error('Error while reading conversation', e)
      });

    this.message = '';
    this.scrollEmitter.next(true);
  }

  deleteHistory() {
    if (!confirm('All messages will be deleted for all parties. You cannot UNDO this action. Are you sure?')) {
      // TODO: Maybe a non-process-blocking popup?
      return;
    }

    this.messages = []; // Optimistic
    this.blockingActionInProgress = true;

    this.client.delete('api/v1/conversations/' + this.conversation.guid, {})
      .then((response: any) => {
        this.blockingActionInProgress = false;
      })
      .catch(e => {
        console.error('Error when deleting history', e);
        this.blockingActionInProgress = false;
      });
  }

  block() {
    if (!this.conversation || !this.conversation.participants) {
      return;
    }

    if (!confirm('This action will block all parties site-wide. Are you sure?')) {
      // TODO: Maybe a non-process-blocking popup?
      return;
    }

    this.blockingActionInProgress = true;

    let blocks = [],
      newState = !this.blocked;

    this.conversation.participants.forEach((participant: any) => {
      if (this.blocked) {
        blocks.push(this.client.delete(`api/v1/block/${participant.guid}`, {}));
      } else {
        blocks.push(this.client.put(`api/v1/block/${participant.guid}`, {}));
      }
    });

    Promise.all(blocks)
      .then((response: any) => {
        this.blockingActionInProgress = false;
        this.blocked = newState;
      })
      .catch(e => {
        console.error('Error when toggling block on participants', e);
        this.blockingActionInProgress = false;
      });
  }

  private hasParticipant(guid: string) {
    if (!this.conversation || !this.conversation.participants) {
      return false;
    }

    let has = false;

    this.conversation.participants.forEach((participant: any) => {
      if (participant.guid == guid) {
        has = true;
      }
    });

    return has;
  }
}
