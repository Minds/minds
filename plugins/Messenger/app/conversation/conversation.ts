import { Component, View, ElementRef } from 'angular2/core';
import { Router, RouteParams, RouterLink } from "angular2/router";

import { SocketsService } from '../../../services/sockets';
import { Client } from '../../../services/api';
import { SessionFactory } from '../../../services/session';
import { Storage } from '../../../services/storage';
import { AutoGrow } from '../../../directives/autogrow';
import { InfiniteScroll } from '../../../directives/infinite-scroll';

import { MessengerEncryptionFactory } from '../encryption/service';
import { MessengerEncryption } from '../encryption/encryption';


@Component({
  selector: 'minds-messenger-conversation',
  properties: [ 'conversation' ],
  templateUrl: 'src/plugins/messenger/conversation/conversation.html',
  directives: [ InfiniteScroll, RouterLink, AutoGrow, MessengerEncryption ]
})

export class MessengerConversation {

  minds: Minds;
  session = SessionFactory.build();

  encryption = MessengerEncryptionFactory.build(); //ideally we want this loaded from bootstrap func.

  guid : string;
  participants : Array<any> = [];
  messages : Array<any> = [];
  open : boolean = false;

  message : string = "";

  constructor(public client : Client){

  }

  ngOnInit(){
    this.load();
  }

  set conversation(conversation : any){
    this.guid = conversation.guid;
    if(conversation.open)
      this.open = true;
    this.participants = [ conversation ];
  }

  load(){
    this.client.get('api/v1/conversations/' + this.guid)
      .then((response : any) => {
        this.messages = response.messages;
      })
  }

  send(e){
    e.preventDefault();
    this.message = "";
    this.client.post('api/v1/conversations/' + this.guid, {
        message: this.message,
        encrypt: true
      })
      .then((response : any) => {

      });
  }

}
