import { Component, Inject } from '@angular/core';
import { ActivatedRoute } from "@angular/router";

import { Subscription } from 'rxjs/Rx';

import { GroupsService } from '../groups-service';

import { MindsTitle } from '../../../services/ux/title';
import { SessionFactory } from '../../../services/session';

@Component({
  moduleId: module.id,
  selector: 'minds-groups-profile',
  providers: [ MindsTitle, GroupsService ],
  templateUrl: 'profile.html'
})

export class GroupsProfile {

  guid;
  filter = "activity";
  group;
  postMeta : any = {
    message: '',
    container_guid: 0
  };
  editing : boolean = false;
  editDone: boolean = false;
  session = SessionFactory.build();
  minds = window.Minds;

  activity : Array<any> = [];
  offset : string = "";
  inProgress : boolean = false;
  moreData : boolean = true;

	constructor(public service: GroupsService, public route: ActivatedRoute, public title: MindsTitle){
  }

  paramsSubscription: Subscription;
  ngOnInit() {
    this.paramsSubscription = this.route.params.subscribe(params => {
      if (params['filter']) {
        this.filter = params['filter'];
      }

      if (params['guid']) {
        let changed = params['guid'] !== this.guid;

        this.guid = params['guid'];
        this.postMeta.container_guid = this.guid;

        if (changed) {
          this.group = void 0;

          this.load();
        }
      }
    });
  }

  ngOnDestroy() {
    this.paramsSubscription.unsubscribe();
  }

  load(){
    this.service.load(this.guid)
    .then((group) => {
      this.group = group;
      this.title.setTitle(this.group.name);
    });
  }

  save(){
    this.service.save({
      guid: this.group.guid,
      name: this.group.name,
      briefdescription: this.group.briefdescription,
      tags: this.group.tags,
      membership: this.group.membership
    });

    this.editing = false;
    this.editDone = true;
  }

  toggleEdit(){
    this.editing = !this.editing;

    if (this.editing) {
      this.editDone = false;
    }
  }

  add_banner(file : any){
    this.service.upload({
      guid: this.group.guid,
      banner_position: file.top
    }, { banner: file.file });

    this.group.banner = true;
  }

  upload_avatar(file : any){
    this.service.upload({
      guid: this.group.guid
    }, { avatar: file });
  }

  change_membership(membership: any) {
    this.load();
  }

}
