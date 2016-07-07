import { Component, Inject } from '@angular/core';
import { CORE_DIRECTIVES } from '@angular/common';
import { RouterLink, Router, RouteParams } from "@angular/router-deprecated";

import { GroupsService } from '../groups-service';

import { Client } from '../../../services/api';
import { SessionFactory } from '../../../services/session';
import { Material } from '../../../directives/material';

import { GroupsJoinButton } from '../groups-join-button';

@Component({
  selector: 'minds-card-group',
  inputs: ['group'],
  providers: [ GroupsService ],
  templateUrl: 'src/plugins/Groups/card/card.html',
  directives: [ CORE_DIRECTIVES, Material, RouterLink, GroupsJoinButton ]
})

export class GroupsCard {

  minds;
  group;

  constructor(public client : Client, public params: RouteParams){
      this.minds = window.Minds;
  }

}
