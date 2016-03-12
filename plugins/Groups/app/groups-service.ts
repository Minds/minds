import { Inject } from 'angular2/core';
import { Client, Upload } from '../../services/api';

export class GroupsService {

  private base: string = 'api/v1/groups/';

  private infiniteInProgress: boolean = false;
  private infiniteOffset: any;

  constructor(@Inject(Client) public clientService: Client, @Inject(Upload) public uploadService: Upload) {
  }

  // Group

  load(guid: string) {
    return this.clientService.get(`${this.base}group/${guid}`)
    .then((response: any) => {
      if (response.group) {
        return response.group;
      }

      throw 'E_LOADING';
    });
  }

  save(group: any) {
    let endpoint = `${this.base}group`;

    if (group.guid) {
      endpoint += `/${group.guid}`;
    }

    return this.clientService.post(endpoint, group)
    .then((response: any) => {
      if (response.guid) {
        return response.guid;
      }

      throw 'E_SAVING';
    });
  }

  upload(group: any, files: any) {
    let uploads = [];

    if (files.banner) {
      uploads.push(this.uploadService.post(`${this.base}group/${group.guid}/banner`, [
        files.banner
      ], {
        banner_position: group.banner_position
      }));
    }

    if (files.avatar) {
      uploads.push(this.uploadService.post(`${this.base}group/${group.guid}/avatar`, [
        files.avatar
      ]));
    }

    return Promise.all(uploads);
  }

  deleteGroup(group: any) {
    return this.clientService.delete(`${this.base}group/${group.guid}`)
    .then((response : any) => {
      return !!response.done;
    })
    .catch((e) => {
      return false;
    });
  }

  // Membership

  join(group: any, target: string = null) {
    let endpoint = `${this.base}membership/${group.guid}`;

    if (target) {
      endpoint += `/${target}`;
    }

    return this.clientService.put(endpoint)
    .then((response: any) => {
      if (response.done) {
        return true;
      }

      throw response.error ? response.error : 'Internal error';
    });
  }

  leave(group: any, target: string = null) {
    let endpoint = `${this.base}membership/${group.guid}`;

    if (target) {
      endpoint += `/${target}`;
    }

    return this.clientService.delete(endpoint)
    .then((response: any) => {
      if (response.done) {
        return true;
      }

      throw response.error ? response.error : 'Internal error';
    });
  }

  acceptRequest(group: any, target: string) {
    // Same endpoint as join
    return this.join(group, target);
  }

  rejectRequest(group: any, target: string) {
    // Same endpoint as leave
    return this.leave(group, target);
  }

  kick(group: any, user: string) {
    return this.clientService.post(`${this.base}membership/${group.guid}/kick`, { user })
    .then((response: any) => {
      return !!response.done;
    })
    .catch(e => {
      return false;
    });
  }

  ban(group: any, user: string) {
    return this.clientService.post(`${this.base}membership/${group.guid}/ban`, { user })
    .then((response: any) => {
      return !!response.done;
    })
    .catch(e => {
      return false;
    });
  }

  cancelRequest(group: any) {
    return this.clientService.post(`${this.base}membership/${group.guid}/cancel`)
    .then((response: any) => {
      return !!response.done;
    })
    .catch(e => {
      return false;
    });
  }

  // Notifications

  muteNotifications(group: any) {
    return this.clientService.post(`${this.base}notifications/${group.guid}/mute`)
    .then((response: any) => {
      return !!response['is:muted'];
    })
    .catch(e => {
      return false;
    });
  }

  unmuteNotifications(group: any) {
    return this.clientService.post(`${this.base}notifications/${group.guid}/unmute`)
    .then((response: any) => {
      return !!response['is:muted'];
    })
    .catch(e => {
      return true;
    });
  }

  // Management

  grantOwnership(group: any, user: string) {
    return this.clientService.put(`${this.base}management/${group.guid}/${user}`)
    .then((response: any) => {
      return !!response.done;
    })
    .catch(e => {
      return false;
    });
  }

  revokeOwnership(group: any, user: string) {
    return this.clientService.delete(`${this.base}management/${group.guid}/${user}`)
    .then((response: any) => {
      return !response.done;
    })
    .catch(e => {
      return true;
    });
  }

  // Invitations

  canInvite(user: string) {
    return this.clientService.post(`${this.base}invitations/check`, { user })
    .then((response: any) => {
      if (response.done) {
        return user;
      }

      throw 'E_NOT_DONE';
    });
  }

  invite(group: any, invitee: string) {
    return this.clientService.put(`${this.base}invitations/${group.guid}`, { invitee })
    .then((response: any) => {
      if (response.done) {
        return true;
      }

      throw response.error ? response.error : 'Internal error';
    })
    .catch(e => {
      throw typeof e === 'string' ? e : 'Connectivity error';
    });
  }

  acceptInvitation(group: any) {
    return this.clientService.post(`${this.base}invitations/${group.guid}/accept`)
    .then((response: any) => {
      return !!response.done;
    })
    .catch(e => {
      return false;
    });
  }

  declineInvitation(group: any) {
    return this.clientService.post(`${this.base}invitations/${group.guid}/decline`)
    .then((response: any) => {
      return !!response.done;
    })
    .catch(e => {
      return false;
    });
  }

  // Helpers

  infiniteList(host: any, opts: any = {}) {
    if (this.infiniteInProgress) {
      return;
    }

    let refresh = opts.refresh ? opts.refresh : false,
      query = opts.query ? opts.query : {},
      _collection = opts.collection ? opts.collection : 'entitites',
      target = opts.targetProperty ? opts.targetProperty : _collection,
      _moreData = opts.moreData ? opts.moreData : 'moreData',
      _inProgress = opts.inProgress ? opts.inProgress : 'inProgress',
      _offset = opts.offset ? opts.offset : 'offset',
      url = opts.url ? opts.url : `${this.base}${opts.endpoint}`;

    if (refresh && this.infiniteOffset) {
      this.infiniteOffset = null;
    } else if (this.infiniteOffset) {
      query[_offset] = this.infiniteOffset;
    }

    this.infiniteInProgress = true;
    this.clientService.get(url, query)
    .then((response: any) => {
      if (!response[_collection] || response[_collection].length == (opts.shift ? 1 : 0)) {
        host[_moreData] = false;

        host[_inProgress] = false;
        this.infiniteInProgress = false;

        this.infiniteOffset = null;
        return false;
      }

      if (opts.refresh) {
        host[target] = response[_collection];
      } else {
        if (opts.shift) {
          response[_collection].shift();
        }

        host[target] = host[target].concat(response[_collection]);
      }

      this.infiniteOffset = response['load-next'];
      host[_inProgress] = false;
      this.infiniteInProgress = false;
    }).catch(() => {
      host[_inProgress] = false;
      this.infiniteInProgress = false;
    });
  }

}
