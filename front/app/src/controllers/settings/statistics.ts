import { Component, View, NgFor, NgIf, NgClass, Observable, Inject, FORM_DIRECTIVES} from 'angular2/angular2';
import { RouterLink } from "angular2/router";
import { Client } from 'src/services/api';
import { Material } from 'src/directives/material';

@Component({
  selector: 'minds-settings-statistics',
  viewBindings: [ Client ],
  properties: ['object']
})
@View({
  templateUrl: 'templates/settings/statistics.html',
  directives: [ NgFor, NgIf, NgClass, Material, RouterLink, FORM_DIRECTIVES]
})

export class SettingsStatistics{

  minds : Minds;
  user : any;
  settings : string;
  data = {
    fullname : "minds",
    email : "minds@minds.com",
    memberSince: new Date(),
    lastLogin: new Date(),
    storage : "0 GB's",
    bandwidth : "0 GB's",
    referrals : 500,
    earnings : 123123

  }
  constructor(public client: Client){
    this.minds = window.Minds;
    this.minds.cdn_url = "https://d3ae0shxev0cb7.cloudfront.net";
    this.load();
  }

  load(){

  }
}
