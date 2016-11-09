import { Component, EventEmitter } from '@angular/core';

import { GroupsService } from '../groups-service';

@Component({
  selector: 'minds-groups-card-user-actions-button',
  inputs: ['group', 'user'],
  template: `
  <button *ngIf="group['is:owner']" (click)="toggleMenu($event)">
    <i class="material-icons">settings</i>
  </button>

  <ul class="minds-dropdown-menu" [hidden]="!showMenu">
    <li class="mdl-menu__item" *ngIf="group['is:owner'] && !user['is:owner'] && user['is:member']" (click)="removePrompt()" i18n>Remove from Group</li>
    <li class="mdl-menu__item" *ngIf="group['is:owner'] && !user['is:member'] && !wasReInvited" (click)="reInvite()" i18n>Re-invite to Group</li>
    <li class="mdl-menu__item" *ngIf="group['is:owner'] && wasReInvited"><span class="minds-menu-info-item" i18n>Invited</span></li>
    <li class="mdl-menu__item" *ngIf="group['is:owner'] && !user['is:owner'] && user['is:member']" (click)="grantOwnership()" i18n>Make Admin</li>
    <li class="mdl-menu__item" *ngIf="group['is:owner'] && user['is:owner'] && user['is:member']" (click)="revokeOwnership()" i18n>Remove as Admin</li>
  </ul>
  <minds-bg-overlay (click)="toggleMenu($event)" [hidden]="!showMenu"></minds-bg-overlay>

  <m-modal [open]="kickPrompt">
      <div class="mdl-card__supporting-text">
        <p i18n>Are you sure you want to remove {{ user.username }} from {{ group.name }}?</p>
        <p><input type="checkbox" #ban> <!-- i18n -->Ban permanently<!-- /i18n --></p>
      </div>
      <div class="minds-modal-dialog-actions">
        <button (click)="kick(ban.checked)" class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored">
          <!-- i18n -->Confirm<!-- /i18n -->
        </button>
        <button (click)="cancelRemove()" class="mdl-button mdl-js-button mdl-button--colored">
          <!-- i18n -->Cancel<!-- /i18n -->
        </button>
      </div>
  </m-modal>
  `,
  providers: [ GroupsService ]
})

export class GroupsCardUserActionsButton {

  group: any = {
  };
  user: any = {
    'is:member': false,
    'is:owner': false,
    'is:creator': false,
    'is:banned': false
  };

  kickPrompt: boolean = false;
  kickBan: boolean = false;

  wasReInvited: boolean = false;

  showMenu: boolean = false;

  constructor(public service: GroupsService) {
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

  kick(ban: boolean = false) {
    let action;

    this.kickPrompt = false;

    if (ban) {
      action = this.service.ban(this.group, this.user.guid);
    } else {
      action = this.service.kick(this.group, this.user.guid);
    }

    action.then((done: boolean) => {
      this.user['is:member'] = !done;
      this.user['is:banned'] = done && ban;

      this.kickPrompt = !done;
      this.changeCounter('members:count', -1);
    });

    this.showMenu = false;
  }

  reInvite() {
    this.service.invite(this.group, this.user.username)
    .then(() => {
      this.wasReInvited = true;
    })
    .catch(e => {
      this.wasReInvited = false;
    });

    this.showMenu = false;
  }

  grantOwnership() {
    this.user['is:owner'] = true;

    this.service.grantOwnership({ guid: this.group.guid }, this.user.guid)
    .then((isOwner: boolean) => {
      this.user['is:owner'] = isOwner;
    });

    this.showMenu = false;
  }

  revokeOwnership() {
    this.user['is:owner'] = false;

    this.service.revokeOwnership({ guid: this.group.guid }, this.user.guid)
    .then((isOwner: boolean) => {
      this.user['is:owner'] = isOwner;
    });

    this.showMenu = false;
  }

  private changeCounter(counter: string, val = 0) {
    if (typeof this.group[counter] !== 'undefined') {
      this.group[counter] = parseInt(this.group[counter], 10) + val;
    }
  }

  ngOnDestroy(){
  }

}
