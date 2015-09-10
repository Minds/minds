import { Component, View, NgFor, NgIf, NgClass, Inject, Observable, FORM_DIRECTIVES} from 'angular2/angular2';
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
  directives: [ ROUTER_DIRECTIVES, NgFor, NgIf, NgClass, Material, RouterLink, MessengerConversation, MessengerSetup, InfiniteScroll ]
})

export class Gatherings {
  activity : any;
  session = SessionFactory.build();
  conversations : Array<Conversation> = [];
  offset : string =  "";
  setup : boolean = false;
  hasMoreData : boolean =  true;
  inProgress : boolean = false;
  cb : Date = new Date();
  search : any = {};
  minds: Minds;
  storage: Storage;


  constructor(public client: Client,
    @Inject(Router) public router: Router,
    @Inject(RouteParams) public params: RouteParams
  ){
    this.storage = new Storage();
    this.checkSetup();
    this.load(true);
    this.minds = window.Minds;
    this.minds.cdn_url = "https://d3ae0shxev0cb7.cloudfront.net";
  }

  showConversation (guid: string, name: string){

  }

  checkSetup(){
    var self = this;
    var key = this.storage.get('private-key');
    if (key){
      this.setup = true;
    }
  }

  load(refresh : boolean = false) {
    var self = this;
    if (this.inProgress || !this.storage.get('private-key')){
      return false;
    }
    this.inProgress = true;
    this.client.get('api/v1/conversations',
    {	limit: 12,offset: this.offset, cb: this.cb
    })
    .then((data : MindsConversationResponse) => {
      if (!data.conversations) {
        self.hasMoreData = false;
        self.inProgress = false;
        return false;
      } else {
        self.hasMoreData = true;
      };

      if(refresh){
        self.conversations = data.conversations;
      }else{
        for(let conversation of data.conversations){
            self.conversations.push(conversation);
        }
      }

      self.offset = data['load-next'];
      self.inProgress = false;
    })
    .catch((error) => {
      console.log("got error" + error);
      self.inProgress = true;
    });
  };

  doSearch(query: string) {
    var self = this;
    if (!query){
      console.log("clearing");
      this.load(true);
      return true;
    }
    console.log("searching " + query);
    this.client.get('api/v1/gatherings/search', {q: query,type: 'user',view: 'json'})
    .then((success : MindsGatheringsSearchResponse) =>{
      self.conversations = success.user[0];
    })
    .catch((error)=>{
      console.log(error);
    });
  };


  doneTyping($event) {
    console.log("typing " + $event.target.value);
    if($event.which === 13) {
      this.doSearch($event.target.value)
      $event.target.value = null;
    }
  };
}
export { MessengerConversation } from './messenger-conversation';
export { MessengerSetup } from './messenger-setup';
