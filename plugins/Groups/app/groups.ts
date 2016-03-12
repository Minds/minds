import { Component, View, Inject } from 'angular2/core';
import { CORE_DIRECTIVES } from 'angular2/common';
import { RouterLink, Router, RouteParams } from "angular2/router";

import { GroupsService } from './groups-service';

import { MindsTitle } from '../../services/ux/title';
import { SessionFactory } from '../../services/session';
import { Material } from '../../directives/material';
import { InfiniteScroll } from '../../directives/infinite-scroll';
import { MindsGroupListResponse } from '../../interfaces/responses';
import { GroupsCreator } from './create/create';
import { GroupsJoinButton } from './groups-join-button';

@Component({
  selector: 'minds-groups',

  bindings: [ MindsTitle, GroupsService ]
})
@View({
  templateUrl: 'src/plugins/Groups/groups.html',
  directives: [ CORE_DIRECTIVES, Material, RouterLink, InfiniteScroll, GroupsJoinButton ]
})

export class Groups {

  minds;

  moreData : boolean = true;
  inProgress : boolean = false;
  groups : Array<any> = [];
  session = SessionFactory.build();
  _filter : string = "featured";

  constructor(public service: GroupsService, public router: Router, public params: RouteParams, public title: MindsTitle){
      this._filter = params.params['filter'];
      this.minds = window.Minds;
      this.load();

      this.title.setTitle("Groups");
  }

  load(refresh: boolean = false) {
    this.service.infiniteList(this, {
      endpoint: this._filter,
      refresh,
      collection: 'groups',
      query: {
        limit: 12
      },
      shift: true
    });
  }
}

export { GroupsProfile } from './profile/profile';
export { GroupsCreator } from './create/create';
