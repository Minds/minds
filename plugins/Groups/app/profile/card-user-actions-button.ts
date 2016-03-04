import { Component, EventEmitter } from 'angular2/core';
import { CORE_DIRECTIVES } from 'angular2/common';

import { Client } from '../../../services/api';


@Component({
  selector: 'minds-groups-card-user-actions-button',
  inputs: ['group', 'user'],
  template: `
  <button *ngIf="group['is:owner']" (click)="toggleMenu($event)">
    <i class="material-icons">settings</i>
  </button>

  <ul class="minds-dropdown-menu" [hidden]="!showMenu">
    <li class="mdl-menu__item" *ngIf="group['is:owner'] && !wasKicked" (click)="removePrompt()">Remove</li>
    <li class="mdl-menu__item" *ngIf="group['is:owner'] && wasKicked && !wasReInvited" (click)="reInvite()">Re-invite</li>
    <li class="mdl-menu__item" *ngIf="group['is:owner'] && wasReInvited"><span class="minds-menu-info-item">Invited</span></li>
  </ul>
  <minds-bg-overlay (click)="toggleMenu($event)" [hidden]="!showMenu"></minds-bg-overlay>

  <minds-groups-modal-dialog [hidden]="!kickPrompt">
    <div class="minds-groups-modal-dialog-wrapper">
      <div class="mdl-card mdl-shadow--2dp">
        <div class="mdl-card__supporting-text">
          <p>Are you sure you want to remove {{ user.username }} from {{ group.name }}?</p>
          <p><input type="checkbox" #ban> Ban permanently</p>
        </div>
        <div class="minds-modal-dialog-actions">
          <button (click)="kick(ban.checked)" class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored">
            Confirm
          </button>
          <button (click)="cancelRemove()" class="mdl-button mdl-js-button mdl-button--colored">
            Cancel
          </button>
        </div>
      </div>
    </div>
  </minds-groups-modal-dialog>
  `,
  directives: [ CORE_DIRECTIVES ]
})

export class GroupsCardUserActionsButton {

  group: any = {
  };
  user: any = {
  };

  kickPrompt: boolean = false;
  kickBan: boolean = false;

  wasKicked: boolean = false;
  wasBanned: boolean = false;
  wasReInvited: boolean = false;

  showMenu: boolean = false;

  constructor(public client : Client) {
  }

  toggleMenu(e){
    e.stopPropagation();
    if(this.showMenu){
      this.showMenu = false;

      return;
    }
    this.showMenu = true;
    // TODO: [emi] Maybe refresh state?
  }

  removePrompt() {
    this.showMenu = false;

    this.kickPrompt = true;
    this.kickBan = false;
  }

  cancelRemove() {
    this.kickPrompt = false;
  }

  kick(ban:boolean = false) {

    let endpoint = 'kick';
    if (ban) {
      endpoint = 'ban';
    }

    this.kickPrompt = false;

    this.client.post(`api/v1/groups/membership/${this.group.guid}/${endpoint}`, {
      user: this.user.guid
    })
    .then((response : any) => {
      if (response.done) {
        this.wasKicked = true;
        this.wasBanned = ban;
      }
      this.kickPrompt = false;
    })
    .catch((e) => {
      this.kickPrompt = true;
    });

    this.showMenu = false;
  }

  reInvite() {

    this.client.put(`api/v1/groups/invitations/${this.group.guid}`, { invitee: this.user.username })
    .then((response : any) => {

      this.wasReInvited = response.done;

    })
    .catch((e)=>{
      this.wasReInvited = false;
    });

    this.showMenu = false;
  }

  ngOnDestroy(){
  }

}
