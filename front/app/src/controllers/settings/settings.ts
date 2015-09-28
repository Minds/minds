import { Component, View, NgFor, NgIf, NgClass, Observable, Inject, FORM_DIRECTIVES} from 'angular2/angular2';
import { RouterLink } from "angular2/router";
import { Client } from 'src/services/api';
import { Material } from 'src/directives/material';
import { SettingsGeneral } from './general';
import { SettingsStatistics } from './statistics';
import { SettingsDisableChannel } from './disableChannel';
import { SettingsTwoFactor } from './twoFactor';

@Component({
  selector: 'minds-settings',
  viewBindings: [ Client ],
  properties: ['object']
})
@View({
  templateUrl: 'templates/settings/settings.html',
  directives: [ NgFor, NgIf, NgClass, Material, RouterLink, FORM_DIRECTIVES, SettingsGeneral, SettingsStatistics, SettingsDisableChannel, SettingsTwoFactor]
})

export class Settings{

  minds : Minds;
  user : any;
  settings : string;

  constructor(public client: Client){
    this.minds = window.Minds;
    this.minds.cdn_url = "https://d3ae0shxev0cb7.cloudfront.net";
    this.settings = 'general';
  }

  set object(value: any) {
    this.user = value;
  }

  showSettings(value : string){
    this.settings = value;
  }

}
