import { Component, View, CORE_DIRECTIVES, ElementRef } from 'angular2/angular2';
import { Router, RouteParams, RouterLink } from "angular2/router";
import { Client } from '../../services/api';
import { SessionFactory } from '../../services/session';
import { Storage } from '../../services/storage';
import { Material } from '../../directives/material';
import { InfiniteScroll } from '../../directives/infinite-scroll';
import { MindsUserConversationResponse } from './interfaces/responses';
import { MindsMessageResponse } from './interfaces/responses';

@Component({
  selector: 'minds-messenger-conversation',
  viewBindings: [ Client ],
  properties: [ '_conversation: conversation' ]
})
@View({
  templateUrl: 'templates/plugins/gatherings/messenger-conversation.html',
  directives: [ CORE_DIRECTIVES, Material, InfiniteScroll, RouterLink ]
})

export class MessengerConversation {

  minds: Minds;
  session = SessionFactory.build();
  storage = new Storage;
  guid : string;

  messages : Array<any> = [];
  offset: string = "";
  previous: string;
  hasMoreData: boolean = true;
  inProgress: boolean = false;
  ready : boolean = false;
  newChat: boolean;
  poll: boolean = true;

  isSubscribed : boolean = true;
  isSubscriber : boolean = true;
  user : any;

  enabled : boolean = true;

  element : any;

  timeout: any;

  isSending : boolean = false;

  constructor(public client: Client, public router: Router, public params: RouteParams, public _element: ElementRef){
    this.minds = window.Minds;
    if (params.params && params.params['guid']){
      this.guid = params.params['guid'];
    }
    this.element = _element.nativeElement;
  }

  set _conversation(value : any){
    if(!value)
      return;
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
        limit: 12,
        offset: this.offset,
        cachebreak: Date.now(),
        decrypt: true,
        password: encodeURIComponent(this.storage.get('private-key'))
      })
      .then((data : MindsUserConversationResponse) =>{

        this.inProgress = false;
        this.ready = true;

        if (!data.messages) {
          this.hasMoreData = false;
          return false;
        }

        this.messages = data.messages.concat(this.messages);
        if(!this.offset)
          this.scrollToBottom();

        this.offset = data['load-previous'];
        this.previous = data['load-next'];
      })
      .catch((e) => {

        self.inProgress = false;
        self.isSubscribed = e.subscribed;
        self.isSubscriber = e.subscriber;
        self.user = e.user;

      });
  };

  /**
   * Send
   */
  send(message){
    var self = this;
    this.isSending = true;
    this.client.post('api/v1/conversations/' + this.guid,
      {
        message: message.value,
        encrypt: true
      })
      .then((data : MindsMessageResponse) =>{
        self.isSending = false;

        self.messages.push(data.message);
        self.previous = data.message.guid;

        self.scrollToBottom();

        message.value = null;
      })
      .catch(function(error) {
        alert('sorry, your message could not be sent');
        message.value = null;
        self.isSending = false;
      });
  }

  scrollToBottom(){
    setTimeout(() => {
      this.element.getElementsByClassName('minds-messenger-messenger')[0].scrollTop = this.element.getElementsByClassName('minds-messenger-messenger')[0].scrollHeight;
    }, 300); //wait until the render?
  }

  doneTyping($event) {
    if($event.which === 13) {
      this.send($event.target);
    }
  }

  delete(message, index){
    var self = this;
    this.client.delete('api/v1/conversations/' + this.guid + '/' + message.guid)
      .then((response : any) => {
        delete self.messages[index];
      });
  }

}
