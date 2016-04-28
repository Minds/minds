import { Component, View } from 'angular2/core';
import { CORE_DIRECTIVES, FORM_DIRECTIVES } from 'angular2/common';
import { ROUTER_DIRECTIVES, Router, RouteParams, RouterLink } from "angular2/router";

import { SocketsService } from '../../services/sockets';
import { MessengerConversation } from "./conversation/conversation";
import { MessengerSetup } from "./setup/setup";
import { Storage } from '../../services/storage';
import { Client } from '../../services/api';
import { SessionFactory } from '../../services/session';
import { BUTTON_COMPONENTS } from '../../components/buttons';
import { Material } from '../../directives/material';
import { InfiniteScroll } from '../../directives/infinite-scroll';

@Component({
  selector: 'minds-messenger',
  templateUrl: 'src/plugins/messenger/messenger.html',
  directives: [ ROUTER_DIRECTIVES, BUTTON_COMPONENTS, Material, RouterLink, MessengerConversation, MessengerSetup, InfiniteScroll ]
})

export class Messenger {

  session = SessionFactory.build();
  conversation;
  conversations : Array<Conversation> = [];
  offset : string =  "";
  setup : boolean = false;
  hasMoreData : boolean =  true;
  inProgress : boolean = false;
  cb : Date = new Date();
  search : any = {};
  minds: Minds = window.Minds;
  storage: Storage = new Storage();
  listener;

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

        if(this.conversations.length == 0 && !this.conversation)
          this.conversation = response.conversations[0].guid;

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

  listen(){
    this.listener = this.sockets.subscribe('messageReceived', (from_guid, message) => {
      for(var i in this.conversations) {
        if(this.conversations[i].guid == from_guid && this.conversation != from_guid) {
          this.conversations[i].unread = 1;
        }
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

  doSearch(query: string) {
    this.inProgress = true;
    var self = this;
    if (!query){
      this.load(true);
      return true;
    }

    console.log("searching " + query);
    this.client.get('api/v1/search', {
      q: query,
      type: 'user',
      view: 'json',
      limit: 5
      }).then((success : MindsUserSearchResponse) =>{
        if (success.entities){
          self.conversations = success.entities;
        }
        self.inProgress = false;
      })
      .catch((error)=>{
        console.log(error);
        self.inProgress = false;
      });
  };

  timeout : any;
  doneTyping(event) {
    if(this.timeout)
      clearTimeout(this.timeout);
    if(event.keyCode === 13) {
      this.doSearch(event.target.value);
      return;
    }
    this.timeout = setTimeout(() => {
      this.doSearch(event.target.value);
    }, 300);
  };

  ngOnDestroy(){
    if(this.listener)
      this.listener.unsubscribe();
  }

}
export { MessengerConversation } from './conversation/conversation';
export { MessengerSetup } from './setup/setup';
