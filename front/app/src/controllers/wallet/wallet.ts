import { Component, View, CORE_DIRECTIVES, FORM_DIRECTIVES} from 'angular2/angular2';
import { Router, RouteParams, ROUTER_DIRECTIVES } from "angular2/router";
import { Client } from 'src/services/api';
import { SessionFactory } from 'src/services/session';
import { MDL_DIRECTIVES } from 'src/directives/material';
import { InfiniteScroll } from 'src/directives/infinite-scroll';

import { WalletService } from 'src/services/wallet';
import { WalletTransactions } from './transactions';
import { WalletPurchase } from './purchase';

@Component({
  selector: 'minds-wallet',
  viewBindings: [ Client, WalletService ]
})
@View({
  templateUrl: 'templates/wallet/wallet.html',
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

	constructor(public client: Client, public wallet: WalletService, public router: Router, public params: RouteParams){
    if(!this.session.isLoggedIn()){
      router.navigate(['/Login']);
    }
    if(params.params['filter'])
      this.filter = params.params['filter'];
	}

}
