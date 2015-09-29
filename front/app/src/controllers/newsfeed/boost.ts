import { Component, View, CORE_DIRECTIVES, EventEmitter} from 'angular2/angular2';
import { RouterLink } from "angular2/router";
import { Client } from 'src/services/api';
import { Material } from 'src/directives/material';
import { MindsWalletResponse } from 'src/interfaces/responses';
import { MindsUserSearchResponse } from 'src/interfaces/responses';
import { MindsBoostResponse } from 'src/interfaces/responses';
import { MindsBoostRateResponse } from 'src/interfaces/responses';
import { BoostFullNetwork } from './boost/full-network';
import { BoostP2P} from './boost/p2p';

@Component({
  selector: 'minds-boost',
  viewBindings: [ Client ],
  properties: ['object'],
  events: ['_done: done']
})
@View({
  templateUrl: 'templates/newsfeed/boost.html',
  directives: [ CORE_DIRECTIVES, Material, RouterLink, BoostFullNetwork, BoostP2P]
})

export class Boost{

  minds : Minds;
  activity : any;
  type : string = '';
  _done: EventEmitter = new EventEmitter();

  constructor(public client: Client){
    this.minds = window.Minds;
  }

  set object(value: any) {
    this.activity = value;
  }

  done(){
    this.type = '';
    this._done.next(true);
  }

}
