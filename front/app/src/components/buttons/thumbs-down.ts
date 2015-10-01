import { Component, View, CORE_DIRECTIVES } from 'angular2/angular2';
import { SessionFactory } from 'src/services/session';
import { Client } from "src/services/api";
import { WalletService } from 'src/services/wallet';

@Component({
  selector: 'minds-button-thumbs-down',
  viewBindings: [ Client, WalletService ],
  properties: ['_object: object']
})
@View({
  template: `
    <a class="mdl-color-text--blue-grey-500" (click)="thumb()" [ng-class]="{'selected': has() }">
      <i class="material-icons">thumb_down</i>
      <counter *ng-if="object['thumbs:down:count'] > 0">{{object['thumbs:down:count']}}</counter>
    </a>
  `,
  directives: [CORE_DIRECTIVES]
})

export class ThumbsDownButton {

  object;
  session = SessionFactory.build();

  constructor(public client : Client, public wallet : WalletService) {
  }

  set _object(value : any){
    this.object = value;
    if(!this.object['thumbs:down:user_guids'])
      this.object['thumbs:down:user_guids'] = [];
  }

  thumb(){
    var self = this;
    this.client.put('api/v1/thumbs/' + this.object.guid + '/down', {});
    if(!this.has()){
      this.object['thumbs:down:user_guids'].push(this.session.getLoggedInUser().guid);
      this.object['thumbs:down:count']++;
      self.wallet.increment();
    } else {
      for(let key in this.object['thumbs:down:user_guids']){
        if(this.object['thumbs:down:user_guids'][key] == this.session.getLoggedInUser().guid)
          delete this.object['thumbs:down:user_guids'][key];
      }
      this.object['thumbs:down:count']--;
      self.wallet.decrement();
    }
  }

  has(){
    for(var guid of this.object['thumbs:down:user_guids']){
      if(guid == this.session.getLoggedInUser().guid)
        return true;
    }
    return false;
  }

}
