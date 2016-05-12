import { Injector, Inject, provide } from 'angular2/core';
import { Storage } from '../../../services/storage';

export class MessengerConversationDockpanesService{

  conversations : Array<any> = [];

  constructor(public storage : Storage){
    this.loadFromCache();
  }

  open(conversation){
    conversation.open = true;
    conversation.unread = false;
    for(let i = 0; i < this.conversations.length; i++){
      if(this.conversations[i].guid == conversation.guid){
        this.conversations[i] = conversation;
        return;
      }
    }
    this.conversations.unshift(conversation);
    this.saveToCache();
  }

  close(conversation){
    for(let i = 0; i < this.conversations.length; i++){
      if(this.conversations[i].guid == conversation.guid){
        this.conversations.splice(i, 1);
      }
    }
    this.saveToCache();
  }

  toggle(conversation){
    for(let i = 0; i < this.conversations.length; i++){
      if(this.conversations[i].guid == conversation.guid){
        this.conversations[i].open = !this.conversations[i].open;
      }
    }
    this.saveToCache();
  }

  private loadFromCache(){
    let conversations = JSON.parse(this.storage.get('messenger-dockpanes'));
    if(conversations)
      this.conversations = conversations;
  }

  private saveToCache(){
    let conversations = this.conversations;
    for(let i = 0; i < conversations.length; i++){
      delete conversations[i].messages;
    }
    this.storage.set('messenger-dockpanes', JSON.stringify(conversations));
  }

}

/**
 * @todo ideally we want this inside the bootstrap
 */
var injector = Injector.resolveAndCreate([
	provide(MessengerConversationDockpanesService, {
    useFactory: () => new MessengerConversationDockpanesService(new Storage())
  })
]);

export class MessengerConversationDockpanesFactory {
	static build(){
		return injector.get(MessengerConversationDockpanesService);
	}
}
