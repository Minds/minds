import { Component } from 'angular2/core';
import { CORE_DIRECTIVES, FORM_DIRECTIVES } from 'angular2/common';
import { ROUTER_DIRECTIVES, Router, RouteParams, RouterLink } from "angular2/router";

import { SocketsService } from '../../services/sockets';
import { MessengerConversation } from "./conversation/conversation";
import { Storage } from '../../services/storage';
import { Client } from '../../services/api';
import { SessionFactory } from '../../services/session';
import { BUTTON_COMPONENTS } from '../../components/buttons';
import { Material } from '../../directives/material';
import { InfiniteScroll } from '../../directives/infinite-scroll';

import { MessengerConversationDockpanes, MessengerConversationDockpanesFactory } from './conversation-dockpanes/conversation-dockpanes';
import { MessengerEncryptionFactory } from './encryption/service';

@Component({
  selector: 'minds-messenger',
  templateUrl: 'src/plugins/Messenger/messenger.html',
  directives: [ BUTTON_COMPONENTS, Material, RouterLink, InfiniteScroll, MessengerConversationDockpanes ]
})

export class Messenger {

  session = SessionFactory.build();
  encryption = MessengerEncryptionFactory.build(); //ideally we want this loaded from bootstrap func.

  dockpanes = MessengerConversationDockpanesFactory.build();
  conversations : Array<Conversation> = [];
  offset : string =  "";

  setup : boolean = false;
  hasMoreData : boolean =  true;
  inProgress : boolean = false;
  cb : Date = new Date();

  minds: Minds = window.Minds;
  storage: Storage = new Storage();
  listener;

  userListToggle : boolean = false;

  constructor(public client: Client, public sockets: SocketsService){
  }

  ngOnInit(){
    if(this.session.isLoggedIn()){
      this.checkSetup();
      this.load(true);
      this.listen();
    }
  }

  checkSetup(){
    var self = this;
    var key = this.storage.get('private-key');
    if (key)
      this.setup = true;
  }

  load(refresh : boolean = false) {

    if(this.inProgress)
      return false;
    this.inProgress = true;

    this.client.get('api/v1/conversations', {
        limit: 12,
        offset: this.offset,
        cb: this.cb
      })
      .then((response : any) => {
        if (!response.conversations) {
          this.hasMoreData = false;
          this.inProgress = false;
          return false;
        }

        if(refresh){
          this.conversations = response.conversations;
        } else {
          this.conversations = this.conversations.concat(response.conversations);
        }

        this.offset = response['load-next'];
        this.inProgress = false;
      })
      .catch((error) => {
        console.log("got error" + error);
        this.inProgress = true;
      });
  }

  search_timeout;
  search(q : string){
    if(this.search_timeout)
      clearTimeout(this.search_timeout);

    if(!q.value){
      return this.load(true);
    }

    this.search_timeout = setTimeout(() => {
      this.inProgress = true;
      this.client.get('api/v1/conversations/search', {
          q: q.value,
          limit: 24
        })
        .then((response : any) => {
          if (!response.conversations) {
            this.hasMoreData = false;
            this.inProgress = false;
            return false;
          }

          this.conversations = response.conversations;

          this.offset = response['load-next'];
          this.inProgress = false;
        })
        .catch((error) => {
          console.log("got error" + error);
          this.inProgress = true;
        });
    }, 100);
  }

  openConversation(conversation){
    conversation.open = true;
    this.dockpanes.open(conversation);
  }

  listen(){
    this.sockets.join(`messenger:${window.Minds.user.guid}`);

    this.listener = this.sockets.subscribe('touchConversation', (guid) => {
      let existing = false;
      for(var i in this.conversations) {
        if(this.conversations[i].guid == guid) {
          this.conversations[i].unread = 1;
          existing = true;
        }
      }

      if (!existing) {
        this.client.get(`api/v1/conversations/${guid}`, {
            password: this.encryption.getEncryptionPassword()
          })
          .then((response) => {
            this.openConversation(response);
          });
      }
    });

    //reset the global counter
    for(var i in window.Minds.navigation.sidebar){
      if(window.Minds.navigation.sidebar[i].name == "Messenger"){
        window.Minds.navigation.sidebar[i].extras.counter = 0;
      }
    }
  }

  logout(){
    this.storage.destroy('private-key');
    this.setup = false;
  }

  ngOnDestroy(){
    if(this.listener)
      this.listener.unsubscribe();
  }

}
export { MessengerConversation } from './conversation/conversation';
