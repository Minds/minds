import { Component, View, CORE_DIRECTIVES, FORM_DIRECTIVES} from 'angular2/angular2';
import { ROUTER_DIRECTIVES, Router, RouteParams, RouterLink } from "angular2/router";

import { SocketsService } from '../../services/sockets';
import { MindsTitle } from '../../services/ux/title';
import { MessengerConversation } from "./messenger-conversation";
import { MessengerSetup } from "./messenger-setup";
import { Storage } from '../../services/storage';
import { Client } from '../../services/api';
import { SessionFactory } from '../../services/session';
import { BUTTON_COMPONENTS } from '../../components/buttons';
import { Material } from '../../directives/material';
import { InfiniteScroll } from '../../directives/infinite-scroll';
import { Conversation } from './interfaces/entities';
import { MindsConversationResponse } from './interfaces/responses';
import { MindsUserSearchResponse } from '../../interfaces/responses';


@Component({
  selector: 'minds-gatherings',
  viewBindings: [ Client ],
  bindings: [ MindsTitle ]
})
@View({
  templateUrl: 'src/plugins/gatherings/templates/gatherings.html',
  directives: [ ROUTER_DIRECTIVES, CORE_DIRECTIVES, BUTTON_COMPONENTS, Material, RouterLink, MessengerConversation, MessengerSetup, InfiniteScroll ]
})

export class Gatherings {

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

  constructor(public client: Client, public router: Router, public params: RouteParams, public title: MindsTitle, public sockets: SocketsService){
    if(!this.session.isLoggedIn()){
      router.navigate(['/Login']);
    } else {
      if (params.params && params.params['guid']){
        this.conversation = params.params['guid'];
      }
      this.checkSetup();
      this.load(true);
      this.listen();
    }
    this.title.setTitle("Messenger");
  }

  checkSetup(){
    var self = this;
    var key = this.storage.get('private-key');
    if (key)
      this.setup = true;
  }

  load(refresh : boolean = false) {
    var self = this;
    if(this.inProgress)
      return false;
    this.inProgress = true;

    this.client.get('api/v1/conversations',
      {
        limit: 12,offset: this.offset, cb: this.cb
      })
      .then((data : MindsConversationResponse) => {
        if (!data.conversations) {
          self.hasMoreData = false;
          self.inProgress = false;
          return false;
        }

        if(self.conversations.length == 0 && !self.conversation)
          self.conversation = data.conversations[0].guid;

        if(refresh){
          self.conversations = data.conversations;
        } else {
          self.conversations = self.conversations.concat(data.conversations);
        }

        self.offset = data['load-next'];
        self.inProgress = false;
      })
      .catch((error) => {
        console.log("got error" + error);
        self.inProgress = true;
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

  onDestroy(){
    if(this.listener)
      this.listener.unsubscribe();
  }

}
export { MessengerConversation } from './messenger-conversation';
export { MessengerSetup } from './messenger-setup';
