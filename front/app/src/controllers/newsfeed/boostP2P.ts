import { Component, View, NgFor, NgIf, NgClass, Observable, Inject, FORM_DIRECTIVES} from 'angular2/angular2';
import { RouterLink } from "angular2/router";
import { Client } from 'src/services/api';
import { Material } from 'src/directives/material';
import { Activity } from './activity';
import { MindsWalletResponse } from 'src/interfaces/responses';
import { MindsUserSearchResponse } from 'src/interfaces/responses';
import { MindsBoostResponse } from 'src/interfaces/responses';
import { MindsBoostRateResponse } from 'src/interfaces/responses';

@Component({
  selector: 'minds-boost-p2p',
  viewBindings: [ Client ],
  properties: ['object']
})
@View({
  templateUrl: 'templates/newsfeed/boostP2P.html',
  directives: [ FORM_DIRECTIVES, NgFor, NgIf, NgClass, Material, RouterLink]
})

export class BoostP2P{

  activity : any;
  errorMessage : string = "";
  data = {
    destination: '',
    points: null,
    impressions: null
  };
  searching: boolean = false;
  results : Array<any> = [];
  minds : Minds;
  inProgress : boolean = false;
  notEnoughPoints : boolean = false;
  rate : MindsBoostRateResponse;

  constructor(public client: Client){
    this.minds = window.Minds;
    this.minds.cdn_url = "https://d3ae0shxev0cb7.cloudfront.net";
    this.client.get('api/v1/boost/rates', {
      cb: Date.now()
    }).then((success : MindsBoostRateResponse) => {
      this.rate = success;
    });
  }

  set object(value: any) {
    this.activity = value;
  }

  boost() {
    var self =  this;
    this.inProgress = true;
    this.notEnoughPoints = false;
    this.errorMessage = "";

    if (this.data.points % 1 !== 0) {
      this.data.points = Math.round(this.data.points);
      this.errorMessage = 'Sorry, you must enter a whole point.';
      this.inProgress = false;
      return false;
    }

    if (this.data.points === 0 || !this.data.points) {
      this.data.points = 1;
      this.errorMessage ='Sorry, you must enter a whole point.';
      this.inProgress = false;
      return false;
    }

    if (this.data.destination === '' && (this.data.impressions === 0 || Math.round(this.data.impressions) === 0)) {
      this.errorMessage = 'Sorry, you must have at least 1 impression.';
      this.inProgress = false;
      return false;
    }

    if (this.checkBalance() && this.data.destination){
      var endpoint = 'api/v1/boost/channel/' + this.activity.guid + '/' + this.activity.owner_guid;
      //commence the boost
      this.client.post(endpoint, {
        impressions: this.data.impressions,
        destination: this.data.destination.charAt(0) == '@' ? this.data.destination.substr(1) : this.data.destination
      }).then((success : MindsBoostResponse) => {
        self.inProgress = false;
        if (success.status == 'success') {
          return true;
        } else {
          return false;
        }

      }).catch((fail) => {
        self.handleErrorMessage('Sorry, something went wrong.');
        return false;
      });
    }
    else {
      this.inProgress = false;
    }
  }

  checkBalance(){
    if (this.rate.balance < this.data.points) {
      this.handleErrorMessage('Ooops! You don\'t have enough points');
      this.notEnoughPoints = true;
      return false;
    }

    //over the cap?
    if (this.data.points > this.rate.cap) {
      this.handleErrorMessage('Ooops! Sorry, there is a limit on how many points can be spent.');
      return false;
    }

    //under the min?
    if (this.data.points < this.rate.min) {
        this.handleErrorMessage('Ooops! Sorry, you need to enter at least ' + this.rate.min + ' points');
      return false;
    }
    //check if the user has enough points
    if (this.rate.balance >= this.data.points){
      return true;
    }
  }

  handleErrorMessage(message : string){
    this.errorMessage = message;
    this.inProgress = false;
  }

  //for Channel Boost
  changeDestination($event) {
    this.searching = true;
    if ($event.target.value.charAt(0) != '@') {
      this.data.destination = '@' + $event.target.value;
    }
    else this.data.destination =  $event.target.value;

    if ($event.which === 13) {
      this.searching = false;
    }

    var query = this.data.destination;
    if (query.charAt(0) == '@') {
      query = query.substr(1);
    }
    console.log(query);

    this.client.get('api/v1/search', {
      q: query,
      type: 'user',
      view: 'json',
      limit: 5
    }).then((success : MindsUserSearchResponse)=> {
      if (success.user){
        this.results = success.user[0];
      }
    })
    .catch((error)=>{
      console.log(error);
    });

    if (!this.data.destination) {
      this.searching = false;
    }
  };

  selectDestination(user) {
    this.searching = false;
    this.data.destination = '@' + user.username;
  };
}
