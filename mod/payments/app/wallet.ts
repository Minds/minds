import { Component, View, NgFor, NgIf, NgClass, Observable, FORM_DIRECTIVES} from 'angular2/angular2';
import { RouterLink } from "angular2/router";
import { Client } from 'src/services/api';
import { SessionFactory } from 'src/services/session';
import { Material } from 'src/directives/material';
import { InfiniteScroll } from 'src/directives/infinite-scroll';

@Component({
  selector: 'minds-wallet',
  viewBindings: [ Client ]
})
@View({
  templateUrl: 'templates/plugins/payments/wallet.html',
  directives: [ NgFor, NgIf, NgClass, Material, FORM_DIRECTIVES, InfiniteScroll ]
})

export class Wallet {

  session = SessionFactory.build();
  points : Number = 0;
  transactions : Array<any> = [];
  offset: string = "";
  inProgress : boolean = false;
  moreData : boolean = true;

	constructor(public client: Client){
    this.getBalance();
    this.loadTransactions();
	}

  getBalance(){
    var self = this;
    this.client.get('api/v1/wallet/count', {})
      .then((response) => {
        self.points = response.count
        });
  }

  loadTransactions(refresh : boolean = false){
    var self = this;
    this.inProgress = true;
    this.client.get('api/v1/wallet/transactions', { limit: 12, offset: this.offset})
      .then((response) => {

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
