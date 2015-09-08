import { Component, View, NgFor, NgIf, NgClass, Inject, Observable, FORM_DIRECTIVES} from 'angular2/angular2';
import { Router, RouteParams, RouterLink } from "angular2/router";
import { MessengerConversation } from "./messenger-conversation";
import { MessengerSetup } from "./messenger-setup";
import { Storage } from 'src/services/storage';
import { Client } from 'src/services/api';
import { SessionFactory } from 'src/services/session';
import { Material } from 'src/directives/material';
import { InfiniteScroll } from 'src/directives/infinite-scroll';

@Component({
  selector: 'minds-gatherings',
  viewBindings: [ Client ]
})
@View({
  templateUrl: 'templates/plugins/gatherings/gatherings.html',
  directives: [ NgFor, NgIf, NgClass, Material, RouterLink, MessengerConversation, MessengerSetup, InfiniteScroll ]
})

export class Gatherings {
  activity : any;
  session = SessionFactory.build();
  conversations : [];
  next : string =  "";
  setup : boolean = false;
  hasMoreData : boolean =  true;
  inprogress : boolean = false;
  cb : Date = new Date();
  search : {};
  storage: Storage;
  minds: {};

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

  load(refresh : boolean) {
    var self = this;
    if (this.inprogress || !this.storage.get('private-key')){
      return false;
    }
    this.inprogress = true;
    this.client.get('api/v1/conversations',
    {	limit: 12,offset: this.next, cb: this.cb
    })
    .then(function(data) {
      if (!data.conversations) {
        self.hasMoreData = false;
        self.inprogress = false;
        return false;
      } else {
        self.hasMoreData = true;
      };

      if(refresh){
        self.conversations = data.conversations;
      }else{
        for(let conversation of data.conversations)
        self.conversations.push(entity);
      }

      self.next = data['load-next'];
      self.inprogress = false;
    })
    .catch( function(error) {
      console.log("got error" + error);
      self.inprogress = true;
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
    .then(function(success) {
      self.conversations = success.user[0];
    })
    .catch(function(error){
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
  refresh() {
    this.search = {};
    this.inprogress = false;
    this.next = "";
    this.previous = "";
    this.cb = new Date();
    this.hasMoreData = true;
    this.load(true);
  };
}
