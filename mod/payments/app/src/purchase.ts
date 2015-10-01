import { Component, View, CORE_DIRECTIVES, FORM_DIRECTIVES} from 'angular2/angular2';
import { RouterLink } from "angular2/router";
import { Client } from 'src/services/api';
import { SessionFactory } from 'src/services/session';
import { MDL_DIRECTIVES } from 'src/directives/material';
import { InfiniteScroll } from 'src/directives/infinite-scroll';

@Component({
  selector: 'minds-wallet-purchase',
  viewBindings: [ Client ]
})
@View({
  templateUrl: 'templates/plugins/payments/purchase.html',
  directives: [ CORE_DIRECTIVES, MDL_DIRECTIVES, FORM_DIRECTIVES, InfiniteScroll ]
})

export class WalletPurchase {

  session = SessionFactory.build();
  card : any = {
    number: "",
    type: "",
    name: "",
    expiry: "",
    cvc: ""
  }

	constructor(public client: Client){

	}

  validate(){

  }

  purchase(){

  }

}
