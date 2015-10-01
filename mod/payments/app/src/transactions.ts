import { Component, View, CORE_DIRECTIVES, FORM_DIRECTIVES} from 'angular2/angular2';
import { RouterLink } from "angular2/router";
import { Client } from 'src/services/api';
import { SessionFactory } from 'src/services/session';
import { MDL_DIRECTIVES } from 'src/directives/material';
import { InfiniteScroll } from 'src/directives/infinite-scroll';

@Component({
  selector: 'minds-wallet-transactions',
  viewBindings: [ Client ]
})
@View({
  templateUrl: 'templates/plugins/payments/transactions.html',
  directives: [ CORE_DIRECTIVES, MDL_DIRECTIVES, FORM_DIRECTIVES, InfiniteScroll ]
})

export class WalletTransactions {

  session = SessionFactory.build();

  transactions : Array<any> = [];
  offset: string = "";
  inProgress : boolean = false;
  moreData : boolean = true;

	constructor(public client: Client){
    this.load();
	}

  load(refresh : boolean = false){
    var self = this;
    this.inProgress = true;
    this.client.get('api/v1/wallet/transactions', { limit: 12, offset: this.offset})
      .then((response : any) => {

        if(!response.transactions){
          self.moreData = false;
          self.inProgress = false;
          return false;
        }

        if(refresh){
          self.transactions = response.transactions
        } else {
          if(self.offset)
            response.transactions.shift();
          for(let transaction of response.transactions)
            self.transactions.push(transaction);
        }

        self.offset = response['load-next'];
        self.inProgress = false;
      })
      .catch((e)=>{

      });
  }

}
