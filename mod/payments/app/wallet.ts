import { Component, View, CORE_DIRECTIVES, FORM_DIRECTIVES} from 'angular2/angular2';
import { ROUTER_DIRECTIVES, RouteParams } from "angular2/router";
import { Client } from 'src/services/api';
import { SessionFactory } from 'src/services/session';
import { MDL_DIRECTIVES } from 'src/directives/material';
import { InfiniteScroll } from 'src/directives/infinite-scroll';

import { WalletService } from './src/wallet-service';
import { WalletTransactions } from './src/transactions';
import { WalletPurchase } from './src/purchase';

@Component({
  selector: 'minds-wallet',
  viewBindings: [ Client, WalletService ]
})
@View({
  templateUrl: 'templates/plugins/payments/wallet.html',
  directives: [ CORE_DIRECTIVES, ROUTER_DIRECTIVES, MDL_DIRECTIVES, FORM_DIRECTIVES, InfiniteScroll,
    WalletTransactions, WalletPurchase ]
})

export class Wallet {

  session = SessionFactory.build();

  filter : string = "transactions";
  points : Number = 0;
  transactions : Array<any> = [];
  offset: string = "";
  inProgress : boolean = false;
  moreData : boolean = true;

	constructor(public client: Client, public wallet: WalletService, public params: RouteParams){
    if(params.params['filter'])
      this.filter = params.params['filter'];
	}

}
