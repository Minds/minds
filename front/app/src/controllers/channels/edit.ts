import { Component, View, NgFor, NgIf, Inject, FORM_DIRECTIVES} from 'angular2/angular2';
import { Router, RouteParams } from 'angular2/router';
import { Client, Upload } from 'src/services/api';
import { Storage } from 'src/services/storage';
import { Material } from 'src/directives/material';
import { SessionFactory } from '../../services/session';
import { UserCard } from 'src/controllers/cards/cards';
import { MindsUser } from 'src/interfaces/entities';

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
  user : MindsUser;
  imagefile;
  imageSrc = "/icon/{{user.guid}}/large";
  inProgress : boolean = false;
  storage : Storage;
  filekey = {
				quality: 50,
				destinationType: 'Camera.DestinationType.FILE_URI',
				sourceType: 0,
				correctOrientation: true
			}

  constructor(public client: Client,
    public upload: Upload,
    @Inject(Router) public router: Router){
    this.storage = new Storage();
  }

  set channel(value: any) {
    this.user = value;
  }

  changeAvatar(file){
    this.imagefile = file ? file.files[0] : null;
  }

  uploadAvatar(){
    var self = this;
    var reader  = new FileReader();
    reader.onloadend = () => {
      this.imageSrc = reader.result;
    }
    reader.readAsDataURL(this.imagefile);
    this.upload.post('api/v1/channel/avatar', [this.imagefile], {filekey : 'file'})
      .then((response : any) => {
        console.log(response);
        self.router.navigate('/' + self.user.username);
      });
  }
}
