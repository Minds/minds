import { Component, View, EventEmitter, CORE_DIRECTIVES} from 'angular2/angular2';
import { Client } from "src/services/api";
import { RouterLink } from 'angular2/router';
import { Material } from 'src/directives/material';

@Component({
  selector: 'minds-banner',
  inputs: ['_object: object', '_src: src', '_top: top', 'overlay', '_editMode: editMode', '_done: done'],
  outputs: ['added']
})
@View({
  template: `
  <div class="minds-banner" *ng-if="!editing">
    <img src="{{src}}" [ng-style]="{'top': top}"/>
    <div class="minds-banner-overlay"></div>
  </div>
  <div *ng-if="editing" class="minds-banner minds-banner-editing">
    <img src="{{src}}" (dragstart)="dragstart($event)" (dragover)="drag($event)" (dragend)="dragend($event)"/>
    <div class="overlay" [hidden]="file">
      <i class="material-icons">camera</i>
      <span>Click here to add a new banner</span>
    </div>
    <div class="save-bar" [hidden]="!file">
      <div class="mdl-layout-spacer"></div>
      <p>Drag the banner vertically to change it's position</p>
      <minds-button-edit class="cancel-button" (click)="cancel()">
        <button>Cancel</button>
      </minds-button-edit>
      <minds-button-edit (click)="done()">
        <button>Save</button>
      </minds-button-edit>
    </div>
    <input type="file" (change)="add($event)" [hidden]="file" />
  </div>
  `,
  directives: [ CORE_DIRECTIVES, RouterLink, Material ]
})

export class MindsBanner{

  minds : Minds = window.Minds;
  object;
  editing : boolean = false;
  src : string = "";
  index : number = 0;

  file : any;
  startY : number = 0;
  offsetY : any = 0;
  top : number = 0;
  dragging : boolean = false;
  dragTimeout;
  added : EventEmitter = new EventEmitter();

	constructor(){
	}

  set _object(value : any){
    if(!value)
      return;
    this.object = value;
    this.src = "/fs/v1/banners/" + this.object.guid + '/' + this.top;
  }

  set _src(value : any){
    this.src = value;
  }

  set _top(value : number){
    this.top = value;
  }

  set _editMode(value : boolean){
    this.editing = value;
  }

  add(e){
    if(!this.editing)
      return;

    var element : any = e.target ? e.target : e.srcElement;
    this.file = element ? element.files[0] : null;

    /**
     * Set a live preview
     */
    var reader  = new FileReader();
    reader.onloadend = () => {
      this.src = reader.result;
    }
    reader.readAsDataURL(this.file);

    element.value = "";
  }

  cancel(){
    this.file = null;
  }

  /**
   * An upstream done event, which triggers the export process. Usually called from carousels
   */
  set _done(value : boolean){
    if(value)
      this.done();
  }

  done(){
    this.added.next({
      index: this.index,
      file: this.file,
      top: this.top
    });
    this.editing = false;
  }

  dragstart(e){
    this.startY = e.target.style.top ? parseInt(e.target.style.top) : 0;
    this.offsetY = e.clientY;
    this.dragging = true;
  }

  drag(e){
    e.preventDefault();
    if(!this.dragging)
      return false;

    var target = e.target;
    var top = this.startY + e.clientY - this.offsetY;
    target.style.top = top + 'px';

    this.top = top;
    return false;
  }

  dragend(e){
    this.dragging = false;
    console.log('stopped dragging');
  }

}
