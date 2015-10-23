import { Component, View, EventEmitter, CORE_DIRECTIVES} from 'angular2/angular2';
import { Client } from "src/services/api";
import { RouterLink } from 'angular2/router';
import { Material } from 'src/directives/material';

@Component({
  selector: 'minds-avatar',
  inputs: ['_object: object', '_src: src', '_editMode: editMode'],
  outputs: ['added']
})
@View({
  template: `
  <div class="minds-avatar">
    <img src="{{src}}" class="mdl-shadow--4dp" />
    <div *ng-if="editing" class="overlay">
      <i class="material-icons">camera</i>
      <span>Change avatar</span>
      <input *ng-if="editing" type="file" #file (change)="add(file)"/>
    </div>
  </div>
  `,
  directives: [ CORE_DIRECTIVES, RouterLink, Material ]
})

export class MindsAvatar{

  minds : Minds = window.Minds;
  object;
  editing : boolean = false;
  src : string = "";
  index : number = 0;

  file : any;
  added : EventEmitter = new EventEmitter();

	constructor(){
	}

  set _object(value : any){
    if(!value)
      return;
    this.object = value;
    this.src = "/icon/"+ this.object.guid + "/large/" + this.object.icontime;
  }

  set _src(value : any){
    this.src = value;
  }

  set _editMode(value : boolean){
    this.editing = value;
    if (!this.editing && this.file)
      this.done();
  }

  add(file){
    if(!this.editing)
      return;

    this.file = file ? file.files[0] : null;

    /**
     * Set a live preview
     */
    var reader  = new FileReader();
    reader.onloadend = () => {
      this.src = reader.result;
    }
    reader.readAsDataURL(this.file);
  }
  done(){
    this.added.next(this.file);
    this.file = null;
  }

}
