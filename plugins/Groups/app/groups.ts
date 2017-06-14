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
        
        this.load(true);
      }
    });
  }

  ngOnDestroy() {
    this.paramsSubscription.unsubscribe();
  }

  load(refresh: boolean = false) {
    let endpoint, key;

    switch (this._filter) {
      case 'trending':
        endpoint = `api/v1/entities/${this._filter}/groups`;
        key = 'entities';
        break;

      default:
        endpoint = `api/v1/groups/${this._filter}`;
        key = 'groups';
        break;
    }

    if(this.inProgress)
      return;
    var self = this;
    this.inProgress = true;
    this.client.get(endpoint, { limit: 12, offset: this.offset})
      .then((response : MindsGroupListResponse) => {

        if(!response[key] || response[key].length == 0){
          this.moreData = false;
          this.inProgress = false;
          return false;
        }

        if(refresh){
          this.groups = response[key];
        } else {
          if(this.offset)
            response[key].shift();

          this.groups.push(...response[key]);
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
