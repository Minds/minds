import { Component, View, CORE_DIRECTIVES, FORM_DIRECTIVES} from 'angular2/angular2';
import { ROUTER_DIRECTIVES, Router, RouteParams, RouterLink } from "angular2/router";

import { MessengerConversation } from "./messenger-conversation";
import { MessengerSetup } from "./messenger-setup";
import { Storage } from 'src/services/storage';
import { Client } from 'src/services/api';
import { SessionFactory } from 'src/services/session';
import { Material } from 'src/directives/material';
import { InfiniteScroll } from 'src/directives/infinite-scroll';
import { Conversation } from './interfaces/entities';
import { MindsConversationResponse } from './interfaces/responses';
import { MindsGatheringsSearchResponse } from './interfaces/responses';


@Component({
  selector: 'minds-gatherings',
  viewBindings: [ Client ]
})
@View({
  templateUrl: 'templates/plugins/gatherings/gatherings.html',
  directives: [ ROUTER_DIRECTIVES, CORE_DIRECTIVES, Material, RouterLink, MessengerConversation, MessengerSetup, InfiniteScroll ]
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
  minds: Minds;
  storage: Storage = new Storage();


  constructor(public client: Client, public router: Router, public params: RouteParams){
    this.minds = window.Minds;
    if (params.params && params.params['guid']){
      this.conversation = params.params['guid'];
    }
    this.checkSetup();
    this.load(true);
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

  doSearch(query: string) {
    var self = this;
    if (!query){
      this.load(true);
      return true;
    }
    console.log("searching " + query);
    this.client.get('api/v1/search', {q: query,type: 'user',view: 'json'})
      .then((success : MindsGatheringsSearchResponse) =>{
        self.conversations = success.user[0];
      })
      .catch((error)=>{
        console.log(error);
      });
  };


  doneTyping($event) {
    if($event.which === 13) {
      this.doSearch($event.target.value)
      $event.target.value = null;
    }
  };

}
export { MessengerConversation } from './messenger-conversation';
export { MessengerSetup } from './messenger-setup';
