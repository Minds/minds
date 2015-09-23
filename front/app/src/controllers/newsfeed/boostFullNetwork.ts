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
  selector: 'minds-boost-full-network',
  viewBindings: [ Client ],
  properties: ['object']
})
@View({
  templateUrl: 'templates/newsfeed/boostFullNetwork.html',
  directives: [ FORM_DIRECTIVES, NgFor, NgIf, NgClass, Material, RouterLink]
})

export class BoostFullNetwork{

  activity : any;
  errorMessage : string = "";
  data = {
    destination: '',
    points: null,
    impressions: null
  };
  searching: boolean = false;
  results = [];
  inProgress : boolean = false;
  notEnoughPoints : boolean = false;
  rate : MindsBoostRateResponse;

  constructor(public client: Client){
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

    if (this.data.impressions === 0 || Math.round(this.data.impressions) === 0) {
      this.errorMessage = 'Sorry, you must have at least 1 impression.';
      this.inProgress = false;
      return false;
    }

    if (this.checkBalance()) {

      var endpoint = 'api/v1/boost/newsfeed/' + self.activity.guid + '/' + self.activity.owner_guid;
      //commence the boost
      this.client.post(endpoint, {
        impressions: this.data.impressions,
        destination: this.data.destination
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
  }

  handleErrorMessage(message : string){
    this.errorMessage = message;
    this.inProgress = false;
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

  calculateImpressions(){
    this.data.impressions = Math.round(this.data.points * this.rate.rate);
  }

}
