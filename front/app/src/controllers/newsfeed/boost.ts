import { Component, View, NgFor, NgIf, NgClass, Observable, Inject} from 'angular2/angular2';
import { Router, RouteParams, RouterLink } from "angular2/router";
import { Client } from 'src/services/api';
import { Material } from 'src/directives/material';
import { Activity } from './activity';

@Component({
  selector: 'minds-boost',
  viewBindings: [ Client ],
  properties: ['object']
})
@View({
  templateUrl: 'templates/newsfeed/boost.html',
  directives: [ NgFor, NgIf, NgClass, Material, RouterLink]
})

export class Boost{

  guid : string;
  owner_guid : string;
  data = {
    destination: '',
    points: null,
    impressions: 0,
    rate: 1,
    step: 1
  };
  searching: boolean = false;
  results = [];
  minds : Minds;

  constructor(public client: Client,
    @Inject(Router) public router: Router,
    @Inject(RouteParams) public params: RouteParams
    ){
    this.guid = params.params['guid'];
    this.owner_guid = params.params['owner_guid'];
    this.minds = window.Minds;
    this.minds.cdn_url = "https://d3ae0shxev0cb7.cloudfront.net";
    this.client.get('api/v1/boost/rates', {
      cb: Date.now()
      }).then((success) => {
      this.data.rate = success.rate;
    });
  }

  boost() {
    if (this.data.points % 1 !== 0) {
      this.data.points = Math.round(this.data.points);
      alert('Sorry, you must enter a whole point.');

      return false;
    }

    if (this.data.points === 0 || !this.data.points) {
      this.data.points = 1;
      alert('Sorry, you must enter a whole point.');
      return false;
    }

    if (this.data.destination === '' && (this.data.impressions === 0 || Math.round(this.data.impressions) === 0)) {
      alert('Sorry, you must have at least 1 impression.');

      return false;
    }

    //validate our points
    this.client.get('api/v1/wallet/count', {
      cb: Date.now()
    }).then((success)=> {
      //lets deal with the failures first..
      //not enough points?
      if (success.count < this.data.points) {
        alert('Ooops! You don\;t have enough points')
        return false;
      }

      //over the cap?
      if (this.data.points > success.cap) {
        alert('Ooops! Sorry, there is a limit on how many points can be spent.');
        return false;
      }

      //under the min?
      if (this.data.points < success.min) {
        alert('Ooops! Sorry, you need to enter at least ' + success.min + ' points');
        return false;
      }

      //check if the user has enough points
      if (success.count >= this.data.points) {

        var endpoint = 'api/v1/boost/newsfeed/' + this.guid + '/' + this.owner_guid;
        if (this.data.destination) {
          endpoint = 'api/v1/boost/channel/' + this.guid + '/' + this.owner_guid;
        }
        //commence the boost
        this.client.post(endpoint, {
          impressions: this.data.impressions,
          destination: this.data.destination.charAt(0) == '@' ? this.data.destination.substr(1) : this.data.destination
        }).then((success) => {
          if (success.status == 'success') {
            return true;
          } else {
            return false;
          }
        }).catch((fail) => {
          alert('Sorry, something went wrong.')
          return false;
        });
      }

      }).catch((error) => {
        return false;
      });
  }


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
    }).then((success)=> {
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
    this.nextStep();
  };

  nextStep() {
    this.data.step = 2;
  };

  purchase() {

  };
}
