import { Component, View, CORE_DIRECTIVES, ElementRef } from 'angular2/angular2';
import { Router, RouteParams, RouterLink } from "angular2/router";
import { SocketsService } from '../../../services/sockets';
import { Client } from '../../../services/api';
import { SessionFactory } from '../../../services/session';
import { Storage } from '../../../services/storage';
import { Material } from '../../../directives/material';
import { AutoGrow } from '../../../directives/autogrow';
import { InfiniteScroll } from '../../../directives/infinite-scroll';
import { MindsUserConversationResponse } from '../interfaces/responses';
import { MindsMessageResponse } from '../interfaces/responses';

@Component({
  selector: 'minds-messenger-conversation',
  viewBindings: [ Client ],
  properties: [ '_conversation: conversation' ]
})
@View({
  templateUrl: 'src/plugins/gatherings/conversation/conversation.html',
  directives: [ CORE_DIRECTIVES, Material, InfiniteScroll, RouterLink, AutoGrow ]
})

export class MessengerConversation {

  minds: Minds;
  session = SessionFactory.build();
  storage = new Storage;
  guid : string;

  messages : Array<any> = [];
  limit : number = 12;
  offset: string = "";
  previous: string;
  hasMoreData: boolean = true;
  inProgress: boolean = false;
  isTyping : boolean = false;
  ready : boolean = false;
  newChat: boolean;
  listener;


  isSubscribed : boolean = true;
  isSubscriber : boolean = true;
  user : any;

  enabled : boolean = true;

  element : any;

  timeout: any;

  isSending : boolean = false;

  constructor(public client: Client, public router: Router, public params: RouteParams, public _element: ElementRef, public sockets: SocketsService){
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
    this.listen();
  }

  /**
  * Load more posts
  */
  load() {
    var self = this;
    this.inProgress = true;

    this.client.get('api/v1/conversations/' + this.guid,
      {
        limit: this.limit,
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
    this.isSending = true;
    this.client.post('api/v1/conversations/' + this.guid,
      {
        message: message.value,
        encrypt: true
      })
      .then((data : MindsMessageResponse) =>{
        this.isSending = false;

        this.messages.push(data.message);
        this.previous = data.message.guid;

        this.scrollToBottom();

        this.sockets.emit('sendMessage', this.guid, {
          type: 'message',
          guid: data.message.guid
        });

        message.value = null;
      })
      .catch(function(error) {
        alert('sorry, your message could not be sent');
        message.value = null;
        this.isSending = false;
      });
  }

  listen_typing_timeout : number;
  listen(){
    this.listener = this.sockets.subscribe('messageReceived', (from_guid, message) => {

      //New message
      if(message.type == 'message' && this.guid == from_guid){
        this.client.get('api/v1/conversations/' + this.guid, {
            limit: this.limit, start: this.previous, decrypt: true, password: encodeURIComponent(this.storage.get('private-key'))
          })
          .then((data : MindsUserConversationResponse) =>{
            this.messages = this.messages.concat(data.messages);
            this.scrollToBottom();

            this.offset = data['load-previous'];
            this.previous = data['load-next'];
          });
      }

      //Is typing
      if(message.type == 'typing' && this.guid == from_guid){
        this.isTyping = true;
        if(this.listen_typing_timeout)
          clearTimeout(this.listen_typing_timeout);
        this.listen_typing_timeout = setTimeout(() => {
          this.isTyping = false;
        },600);
      }

    });
  }

  scrollToBottom(){
    setTimeout(() => {
      this.element.getElementsByClassName('minds-messenger-messenger')[0].scrollTop = this.element.getElementsByClassName('minds-messenger-messenger')[0].scrollHeight;
    }, 300); //wait until the render?
  }

  keyup(e) {
    this.typing();
    if(e.which === 13) {
      this.send(e.target);
    }
  }

  typing_timeout : number;
  typing(){
    if(this.typing_timeout)
      clearTimeout(this.typing_timeout);
    this.typing_timeout = setTimeout(() => {
      this.sockets.emit('sendMessage', this.guid, { type: 'typing' });
    }, 100);
  }

  delete(message, index){
    var self = this;
    this.client.delete('api/v1/conversations/' + this.guid + '/' + message.guid)
      .then((response : any) => {
        delete self.messages[index];
      });
  }

  onDestroy(){
    if(this.listener)
      this.listener.unsubscribe();
  }

}
