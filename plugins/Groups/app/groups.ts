import { Component, Inject } from '@angular/core';
import { ActivatedRoute } from "@angular/router";

import { Subscription } from 'rxjs/Rx';

import { GroupsService } from './groups-service';

import { Client } from '../../services/api';
import { MindsTitle } from '../../services/ux/title';
import { SessionFactory } from '../../services/session';
import { MindsGroupListResponse } from '../../interfaces/responses';

@Component({
  moduleId: module.id,
  selector: 'minds-groups',

  templateUrl: 'groups.html'
})

export class Groups {

  minds;

  moreData : boolean = true;
  inProgress : boolean = false;
  offset : string = "";
  groups : Array<any> = [];
  session = SessionFactory.build();
  _filter : string = "featured";

  constructor(public client : Client, public route: ActivatedRoute, public title: MindsTitle){
  }

  paramsSubscription: Subscription;
  ngOnInit() {
    this.title.setTitle("Groups");
    this.minds = window.Minds;

    this.paramsSubscription = this.route.params.subscribe(params => {
      if (params['filter']) {
        this._filter = params['filter'];

        this.inProgress = false;
        this.offset = '';
        this.moreData = true;
        this.groups = [];
        
        this.load();
      }
    });
  }

  ngOnDestroy() {
    this.paramsSubscription.unsubscribe();
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
