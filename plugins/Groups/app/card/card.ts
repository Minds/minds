import { Component, Inject } from 'angular2/core';
import { CORE_DIRECTIVES } from 'angular2/common';
import { RouterLink, Router, RouteParams } from "angular2/router";

import { GroupsService } from '../groups-service';

import { Client } from '../../../services/api';
import { SessionFactory } from '../../../services/session';
import { Material } from '../../../directives/material';

import { GroupsJoinButton } from '../groups-join-button';

@Component({
  selector: 'minds-card-group',
  inputs: ['group'],
  bindings: [ GroupsService ],
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
