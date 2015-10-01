import { Inject, Injector, bind } from 'angular2/angular2';
import { Client } from 'src/services/api';

export class WalletService {

  points : number = 0;

  constructor(@Inject(Client) public client : Client){
    this.getBalance();
  }

  /**
   * Increment the wallet
   */
  increment(points : number = 1){
    window.Minds.wallet.balance = window.Minds.wallet.balance + points;
    this.sync();
  }

  /**
   * Decrement the wallet
   */
  decrement(points : number = 1){
    window.Minds.wallet.balance = window.Minds.wallet.balance - points;
    this.sync();
  }

  /**
   * Return the balance
   */
   getBalance(){
     var self = this;
     if(!window.Minds.wallet){
       window.Minds.wallet = { balance: '...' };
       this.client.get('api/v1/wallet/count', {})
         .then((response : any) => {
           window.Minds.wallet.balance = response.count;
           self.sync();
         });
        return;
     }
     this.points = window.Minds.wallet.balance;
   }

  /**
   * Sync points to the topbar Counter
   */
  sync(){
    for(var i in window.Minds.navigation.topbar){
      if(window.Minds.navigation.topbar[i].name == 'Wallet'){
        window.Minds.navigation.topbar[i].extras.counter = window.Minds.wallet.balance;
      }
    }
  }

}
