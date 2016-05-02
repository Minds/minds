import { Injector, Inject, provide } from 'angular2/core';

export class MessengerConversationDockpanesService{

  conversations : Array<any> = [];

  open(conversation){
    conversation.open = true;
    for(let i = 0; i < this.conversations.length; i++){
      if(this.conversations[i].guid == conversation.guid){
        this.conversations[i] = conversation;
        return;
      }
    }
    this.conversations.unshift(conversation);
  }

  close(conversation){
    for(let i = 0; i < this.conversations.length; i++){
      if(this.conversations[i].guid == conversation.guid){
        this.conversations.splice(i, 1);
      }
    }
  }

}

/**
 * @todo ideally we want this inside the bootstrap
 */
var injector = Injector.resolveAndCreate([
	provide(MessengerConversationDockpanesService, {
    useFactory: () => new MessengerConversationDockpanesService()
  })
]);

export class MessengerConversationDockpanesFactory {
	static build(){
		return injector.get(MessengerConversationDockpanesService);
	}
}
