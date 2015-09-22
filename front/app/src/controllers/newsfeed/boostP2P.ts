import { Component, View, NgFor, NgIf, NgClass, Observable, Inject} from 'angular2/angular2';
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
  directives: [ NgFor, NgIf, NgClass, Material, RouterLink]
})

export class BoostP2P{

  activity : any;
  errorMessage : string = "";
  data = {
    destination: '',
    points: null,
    impressions: null,
    rate: 1,
    step: 1
  };
  searching: boolean = false;
  results = [];
  minds : Minds;
  inProgress : boolean = false;
  notEnoughPoints : boolean = false;

  constructor(public client: Client){
    this.client.get('api/v1/boost/rates', {
      cb: Date.now()
    }).then((success : MindsBoostRateResponse) => {
      this.data.rate = success.rate;
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

    //validate our points
    this.client.get('api/v1/wallet/count', {
      cb: Date.now()
    }).then((success : MindsWalletResponse)=> {
      //lets deal with the failures first..
      //not enough points?
      if (success.count < self.data.points) {
        self.handleErrorMessage('Ooops! You don\'t have enough points');
        this.notEnoughPoints = true;
        return false;
      }

      //over the cap?
      if (self.data.points > success.cap) {
        self.handleErrorMessage('Ooops! Sorry, there is a limit on how many points can be spent.');
        return false;
      }

      //under the min?
      if (self.data.points < success.min) {
        self.handleErrorMessage('Ooops! Sorry, you need to enter at least ' + success.min + ' points');
        return false;
      }
      //check if the user has enough points
      if (success.count >= self.data.points) {

        var endpoint = 'api/v1/boost/newsfeed/' + self.activity.guid + '/' + self.activity.owner_guid;
        //Keep for destination
        if (self.data.destination) {
          endpoint = 'api/v1/boost/channel/' + self.activity.guid + '/' + self.activity.owner_guid;
        }
        //commence the boost
        self.client.post(endpoint, {
          impressions: self.data.impressions,
          destination: self.data.destination.charAt(0) == '@' ? self.data.destination.substr(1) : self.data.destination
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
      else{
        this.inProgress = false;
      }

      }).catch((error) => {
        self.handleErrorMessage('Sorry, something went wrong.');
        return false;
      });
  }

  handleErrorMessage(message : string){
    this.errorMessage = message;
    this.inProgress = false;
  }

  updateFields($event){
    this.data.points = $event.target.value;
    this.data.impressions = $event.target.value;
  }

  //for Channel Boost
  changeDestination(e) {
    this.searching = true;
    if (this.data.destination.charAt(0) != '@' && this.data.destination.length !== 0) {
      this.data.destination = '@' + this.data.destination;
    }
    if (e.keyCode == 13) {
      this.searching = false;
    }

    var query = this.data.destination;
    if (query.charAt(0) == '@') {
      query = query.substr(1);
    }

    this.client.get('search', {
      q: query,
      type: 'user',
      view: 'json',
      limit: 5
    }).then((success : MindsUserSearchResponse)=> {
      this.results = success.user[0];
    });

    console.log('changing');

    if (!this.data.destination) {
      this.searching = false;
    }
  };

  selectDestination(user) {
    this.searching = false;
    this.data.destination = '@' + user.username;
  };
}
