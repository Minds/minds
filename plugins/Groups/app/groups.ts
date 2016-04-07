import { Component, View, Inject } from 'angular2/core';
import { CORE_DIRECTIVES } from 'angular2/common';
import { RouterLink, Router, RouteParams } from "angular2/router";

import { GroupsService } from './groups-service';

import { Client } from '../../services/api';
import { MindsTitle } from '../../services/ux/title';
import { SessionFactory } from '../../services/session';
import { Material } from '../../directives/material';
import { InfiniteScroll } from '../../directives/infinite-scroll';
import { MindsGroupListResponse } from '../../interfaces/responses';
import { GroupsCreator } from './create/create';
import { GroupsJoinButton } from './groups-join-button';
import { GroupsCard } from './card/card';

@Component({
  selector: 'minds-groups',

  bindings: [ MindsTitle, GroupsService ]
})
@View({
  templateUrl: 'src/plugins/Groups/groups.html',
  directives: [ CORE_DIRECTIVES, Material, RouterLink, InfiniteScroll, GroupsJoinButton, GroupsCard ]
})

export class Groups {

  minds;

  moreData : boolean = true;
  inProgress : boolean = false;
  groups : Array<any> = [];
  session = SessionFactory.build();
  _filter : string = "featured";

  constructor(public client : Client, public router: Router, public params: RouteParams, public title: MindsTitle){
      this._filter = params.params['filter'];
      this.minds = window.Minds;
      this.load();

      this.title.setTitle("Groups");
  }

  load(refresh: boolean = false) {
    if(this.inProgress)
      return;
    var self = this;
    this.inProgress = true;
    this.client.get('api/v1/groups/' + this._filter, { limit: 12, offset: this.offset})
      .then((response : MindsGroupListResponse) => {

        if(!response.groups || response.groups.length == 0){
          this.moreData = false;
          this.inProgress = false;
          return false;
        }

        if(refresh){
          this.groups = response.groups;
        } else {
          if(this.offset)
            response.groups.shift();
          for(let group of response.groups)
            this.groups.push(group);
        }

        this.offset = response['load-next'];
        this.inProgress = false;
      })
      .catch((e)=>{
        this.inProgress = false;
      });
  }
}

export { GroupsProfile } from './profile/profile';
export { GroupsCreator } from './create/create';
