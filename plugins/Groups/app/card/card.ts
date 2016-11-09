import { Component, Inject } from '@angular/core';

import { GroupsService } from '../groups-service';

import { Client } from '../../../services/api';
import { SessionFactory } from '../../../services/session';

@Component({
  moduleId: module.id,
  selector: 'minds-card-group',
  inputs: ['group'],
  providers: [ GroupsService ],
  templateUrl: 'card.html'
})

export class GroupsCard {

  minds;
  group;

  constructor(public client : Client){
      this.minds = window.Minds;
  }

}
