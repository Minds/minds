import { NgModule } from '@angular/core';
import { CommonModule as NgCommonModule } from '@angular/common';
import { RouterModule, Routes } from "@angular/router";
import { FormsModule, ReactiveFormsModule } from "@angular/forms";

import { CommonModule } from "../../common/common.module";
import { LegacyModule } from "../../modules/legacy/legacy.module";
import { ChannelModule } from "../../modules/channel/channel.module";
import { ModalsModule } from "../../modules/modals/modals.module";

import { Groups, GroupsProfile, GroupsCreator } from './groups';
import { GroupsJoinButton } from './groups-join-button';
import { GroupsProfileMembersInvite } from './profile/members/invite/invite';
import { GroupsCard } from './card/card';
import { GroupsCardUserActionsButton } from './profile/card-user-actions-button';
import { GroupsSettingsButton } from './profile/groups-settings-button';
import { GroupsProfileMembers } from './profile/members/members';
import { GroupsProfileRequests } from './profile/requests/requests';
import { GroupsProfileFeed } from './profile/feed/feed';

const routes: Routes = [
  { path: 'groups/profile/:guid/:filter', component: GroupsProfile },
  { path: 'groups/profile/:guid', component: GroupsProfile },
  { path: 'groups/create', component: GroupsCreator },
  { path: 'groups/:filter', component: Groups },
]

@NgModule({
  imports: [
    NgCommonModule,
    RouterModule.forChild(routes),
    FormsModule,
    ReactiveFormsModule,
    CommonModule,
    LegacyModule,
    ChannelModule,
    ModalsModule
  ],
  declarations: [
    Groups,
    GroupsProfile,
    GroupsCreator,
    GroupsJoinButton,
    GroupsProfileMembersInvite,
    GroupsCard,
    GroupsCardUserActionsButton,
    GroupsProfileMembers,
    GroupsProfileFeed,
    GroupsProfileRequests,
    GroupsSettingsButton,
  ],
  exports: [
    Groups,
    GroupsProfile,
    GroupsCreator,
    GroupsJoinButton,
    GroupsProfileMembersInvite,
    GroupsCard,
    GroupsCardUserActionsButton,
    GroupsProfileMembers,
    GroupsProfileFeed,
    GroupsProfileRequests,
    GroupsSettingsButton,
  ],
  entryComponents: [
    GroupsCard
  ]
})
export class GroupsModule {
}
