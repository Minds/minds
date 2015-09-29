import { Component, View, NgFor, NgIf, Inject, FORM_DIRECTIVES} from 'angular2/angular2';
import { Router, RouteParams } from 'angular2/router';
import { Client, Upload } from 'src/services/api';
import { Storage } from 'src/services/storage';
import { Material } from 'src/directives/material';
import { SessionFactory } from '../../services/session';
import { UserCard } from 'src/controllers/cards/cards';

@Component({
  selector: 'minds-channel-edit',
  viewBindings: [ Client, Upload ],
  properties: ['channel']
})
@View({
  templateUrl: 'templates/channels/edit.html',
  directives: [ NgFor, NgIf, Material, UserCard, FORM_DIRECTIVES]
})

export class ChannelEdit {
  session = SessionFactory.build();
  user;
  imagefile;
  inProgress : boolean = false;
  storage : Storage;

  constructor(public client: Client,
    public upload: Upload,
    @Inject(Router) public router: Router){
    this.storage = new Storage();
  }

  set channel(value: any) {
    this.user = value;
  }

  changeAvatar(file){
    this.imagefile = file.files[0];
  }

  uploadAvatar(){
    var self = this;
    this.upload.post('api/v1/channel/avatar', [this.imagefile], { Authorization: "Bearer " + this.storage.get('access_token')}, (progress) => {
      console.log('progress update');
      console.log(progress);
      })
			.then((response : any) => {
        self.router.navigate('/' + self.user.guid);
			})
			.catch(function(e){
				console.error(e);
			});
  }
}
