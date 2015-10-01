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
    this.points = this.points + points;
    this.sync();
  }

  /**
   * Decrement the wallet
   */
  decrement(points : number = 1){
    this.points = this.points - points;
    this.sync();
  }

  /**
   * Return the balance
   */
   getBalance(){
     var self = this;
     this.client.get('api/v1/wallet/count', {})
       .then((response : any) => {
         self.points = response.count
         self.sync();
       });
   }

  /**
   * Sync points to the topbar Counter
   */
  sync(){
    for(var i in window.Minds.navigation.topbar){
      if(window.Minds.navigation.topbar[i].name == 'Wallet'){
        window.Minds.navigation.topbar[i].extras.counter = this.points;
      }
    }

  }

}
