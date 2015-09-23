import { Component, View, NgFor, NgIf, NgClass, Observable, Inject} from 'angular2/angular2';
import { RouterLink } from "angular2/router";
import { Client } from 'src/services/api';
import { Material } from 'src/directives/material';
import { Activity } from './activity';
import { MindsWalletResponse } from 'src/interfaces/responses';
import { MindsUserSearchResponse } from 'src/interfaces/responses';
import { MindsBoostResponse } from 'src/interfaces/responses';
import { MindsBoostRateResponse } from 'src/interfaces/responses';
import { BoostFullNetwork } from './boostFullNetwork';
import { BoostP2P} from './boostP2P';

@Component({
  selector: 'minds-boost',
  viewBindings: [ Client ],
  properties: ['object']
})
@View({
  templateUrl: 'templates/newsfeed/boost.html',
  directives: [ NgFor, NgIf, NgClass, Material, RouterLink, BoostFullNetwork, BoostP2P]
})

export class Boost{

  minds : Minds;
  activity : any;
  type : string;
  
  constructor(public client: Client){
    this.minds = window.Minds;
    this.minds.cdn_url = "https://d3ae0shxev0cb7.cloudfront.net";
  }

  set object(value: any) {
    this.activity = value;
  }

  showBoost(value : string){
    this.type = value;
  }

  back(){
    this.type = null;
  }

}
