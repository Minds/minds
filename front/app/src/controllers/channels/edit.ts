import { Component, View, NgIf, Inject} from 'angular2/angular2';
import { Router, RouteParams } from 'angular2/router';
import { Client, Upload } from 'src/services/api';
import { Material } from 'src/directives/material';
import { SessionFactory } from '../../services/session';
import { MindsUser } from 'src/interfaces/entities';

@Component({
  selector: 'minds-channel-edit',
  viewBindings: [ Client, Upload ],
  properties: ['channel']
})
@View({
  templateUrl: 'templates/channels/edit.html',
  directives: [ NgIf, Material]
})

export class ChannelEdit {
  session = SessionFactory.build();
  user : MindsUser;
  cb = Date.now();
  imagefile;

  constructor(public client: Client,
    public upload: Upload,
    @Inject(Router) public router: Router){
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
    reader.readAsDataURL(this.imagefile);

    this.upload.post('api/v1/channel/avatar', [this.imagefile], {filekey : 'file'})
      .then((response : any) => {
        self.imagefile = null;
        self.user.icontime = Date.now();
        window.Minds.user.icontime = Date.now();
      });
  }
}
