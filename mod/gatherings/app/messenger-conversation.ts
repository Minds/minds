import { Component, View, NgFor, NgIf, NgClass, Inject, Observable} from 'angular2/angular2';
import { Router, RouteParams, RouterLink } from "angular2/router";
import { Client } from 'src/services/api';
import { SessionFactory } from 'src/services/session';
import { Material } from 'src/directives/material';
import { MindsUserConversationResponse } from 'src/interfaces/responses';
import { MindsMessageResponse } from 'src/interfaces/responses';

@Component({
  selector: 'minds-messenger-conversation',
  viewBindings: [ Client ]
})
@View({
  templateUrl: 'templates/plugins/gatherings/messenger-conversation.html',
  directives: [ NgFor, NgIf, NgClass, Material, RouterLink]
})

export class MessengerConversation {
  activity : any;
  session = SessionFactory.build();
  guid : string;
  name : string;
  messages : Array<any> = [];
  offset: string;
  previous: string;
  hasMoreData: boolean = true;
  inProgress: boolean = false;
  newChat: boolean;
  poll: boolean = true;
  publickeys: any;
  timeout: any;
  minds: Minds;

  constructor(public client: Client,
    @Inject(Router) public router: Router,
    @Inject(RouteParams) public params: RouteParams
  ){
    console.log("PARAMS :" + params);
    this.minds = window.Minds;
    if (params.params && params.params['guid']){
      this.guid = params.params['guid'];
      console.log("PARAMS GUID: "+ params.params['guid']);
      this.load();
    }
  }

  /**
  * Load more posts
  */
  load() {
    var self = this;
    this.inProgress = true;

    console.log('loading messages from:' + this.offset);

    this.client.get('api/v1/conversations/' + this.guid, {
      limit: 6,
      offset: this.offset,
      cachebreak: Date.now()
    })
    .then(function(data : MindsUserConversationResponse) {
      self.newChat = false;
      self.inProgress = false;
      //now update the public keys
      self.publickeys = data.publickeys;

      if (!self.publickeys[self.guid]) {
        alert({
          title: 'Sorry!',
          template: self.name + " has not yet configured their encrypted chat yet."
        });
        return true;
      }

      if (!data.messages) {
        self.hasMoreData = false;
        return false;
      } else {
        self.hasMoreData = true;
      };

      var first;
      if (self.messages.length === 0) {
        first = true;
      } else {
        first = false;
      }

      for(let message of data.messages){
        self.messages.push(message);
      }


      console.log("------ MESSAGES ARE LOADED ------");

      self.offset = data['load-previous'];
      self.previous = data['load-next'];

      if (first) {
        //Must Scroll Bottom
        /*
        $timeout(function() {
        $ionicScrollDelegate.scrollBottom();
      }, 1000);
      */
      }

      self.poll = true;

    })
    .catch(function(error) {
      self.inProgress = false;
    });
  };

  sendMessage(chat : string){
    console.log("BEFORE: " +chat);
    var pushed = false;
    var self = this;
    this.client.post('api/v1/conversations/' + this.guid, chat)
    .then(function(data : MindsMessageResponse) {
      console.log(data);
      if (!pushed) {
        self.messages.push(data.message);
        self.previous = data.message.guid;
        pushed = true;
      }
    })
    .catch(function(error) {
      alert('sorry, your message could not be sent');
      console.log(error);
    });
  }

  doneTyping($event) {
    if($event.which === 13) {
      this.sendMessage($event.target.value);
      $event.target.value = null;
    }
  };

}
