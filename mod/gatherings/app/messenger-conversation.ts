import { Component, View, NgFor, NgIf, NgClass, Inject, Observable} from 'angular2/angular2';
import { Router, RouteParams, RouterLink } from "angular2/router";
import { Client } from 'src/services/api';
import { SessionFactory } from 'src/services/session';
import { Material } from 'src/directives/material';
import { MindsUserConversationResponse } from './interfaces/responses';
import { MindsMessageResponse } from './interfaces/responses';

@Component({
  selector: 'minds-messenger-conversation',
  viewBindings: [ Client ],
  properties: [ '_conversation: conversation' ]
})
@View({
  templateUrl: 'templates/plugins/gatherings/messenger-conversation.html',
  directives: [ NgFor, NgIf, NgClass, Material, RouterLink]
})

export class MessengerConversation {

  minds: Minds;
  session = SessionFactory.build();
  guid : string;

  messages : Array<any> = [];
  offset: string;
  previous: string;
  hasMoreData: boolean = true;
  inProgress: boolean = false;

  newChat: boolean;
  poll: boolean = true;

  enabled : boolean = true;

  timeout: any;

  isSendingMessage : boolean = false;

  constructor(public client: Client, public router: Router, public params: RouteParams){
    this.minds = window.Minds;
    if (params.params && params.params['guid']){
      this.guid = params.params['guid'];
      this.load();
    }
  }

  set _conversation(value : any){
    this.guid = value;
    this.load();
  }

  /**
  * Load more posts
  */
  load() {
    var self = this;
    this.inProgress = true;

    this.client.get('api/v1/conversations/' + this.guid,
      {
        limit: 6,
        offset: this.offset,
        cachebreak: Date.now()
      })
      .then((data : MindsUserConversationResponse) =>{
        self.newChat = false;
        self.inProgress = false;

        if (!self.publickeys[self.guid]) {
          self.enabled = false;
          return true;
        }

        if (!data.messages) {
          self.hasMoreData = false;
          return false;
        }

        for(let message of data.messages){
          self.messages.push(message);
        }

        console.log("------ MESSAGES ARE LOADED ------");

        self.offset = data['load-previous'];
        self.previous = data['load-next'];

        self.poll = true;

      })
      .catch(function(error) {
        self.inProgress = false;
      });
  };

  sendMessage(message){
    this.isSendingMessage = true;
    var pushed = false;
    var self = this;
    this.client.post('api/v1/conversations/' + this.guid, message.value)
      .then((data : MindsMessageResponse) =>{
        self.isSendingMessage = false;
        if (!pushed) {
          data.message.message = message.value;
          self.messages.push(data.message);
          self.previous = data.message.guid;
          pushed = true;
        }
        message.value = null;
      })
      .catch(function(error) {
        alert('sorry, your message could not be sent');
        message.value = null;
        self.isSendingMessage = false;
        console.log(error);
      });
  }

  doneTyping($event) {
    if($event.which === 13) {
      this.sendMessage($event.target);
    }
  };

}
