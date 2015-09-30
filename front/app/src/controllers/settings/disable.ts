import { Component, View, CORE_DIRECTIVES, FORM_DIRECTIVES} from 'angular2/angular2';
import { RouterLink } from "angular2/router";
import { Client } from 'src/services/api';
import { Material } from 'src/directives/material';

@Component({
  selector: 'minds-settings-disable-channel',
  viewBindings: [ Client ],
  properties: ['object']
})
@View({
  templateUrl: 'templates/settings/disable.html',
  directives: [ CORE_DIRECTIVES, Material, RouterLink, FORM_DIRECTIVES]
})

export class SettingsDisableChannel{

  minds : Minds;
  user : any;
  settings : string;

  constructor(public client: Client){
    this.minds = window.Minds;
  }

  disable(){
    console.log("DISABLED");
  }

}
