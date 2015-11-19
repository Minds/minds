import { Component, View, CORE_DIRECTIVES, ElementRef, EventEmitter } from 'angular2/angular2';
import { Client } from '../../../services/api';
import { Material } from '../../../directives/material';

@Component({
  selector: 'minds-archive-thumbnail-selector',
  inputs: [ '_src: src', '_thumbnailSec: thumbnailSec' ],
  outputs: [ 'thumbnail' ],
  host: {
    //'(click)': 'onClick()',
  }
})
@View({
  template: `
  <div class="m-video-loading" [hidden]="!inProgress">
    <div class="mdl-spinner mdl-js-spinner is-active"></div>
  </div>
  <video (click)="onClick()" preload="metadata" muted crossOrigin="anonymous">
  </video>
  <div class="m-scrubber mdl-color--blue-grey-600" (click)="seek($event)">
      <div class="m-scrubber-progress mdl-color--amber-600" [ng-style]="{'left': (thumbnailSec / element.duration)*100  + '%'}"></div>
  </div>
  <span class="m-scrubber-tip">Click on this bar to change the thumbnail</span>
  `,
  directives: [ CORE_DIRECTIVES, Material ]
})

export class ThumbnailSelector{

  element : any;
  src : Array<any> = [];
  thumbnailSec : number = 0;
  thumbnail : EventEmitter = new EventEmitter();
  canvas;
  inProgress : boolean = false;

  constructor(_element : ElementRef){
    this.element = _element.nativeElement.getElementsByTagName("video")[0];
  }

  set _src(value : any){
    this.src = value[0].uri;
    this.element.src = this.src;
  }

  set _thumbnailSec(value : number){
    if(!this.canvas)
      this.inProgress = true;
    this.thumbnailSec = value;
    this.element.addEventListener('loadedmetadata', () => {
      this.element.currentTime = value;
      this.inProgress = false;
    });
  }

  seek(e){
    e.preventDefault();
    var seeker = e.target;
    var seek = e.offsetX / seeker.offsetWidth;
    var seconds = this.seekerToSeconds(seek);
    this.element.currentTime = seconds;
    this.thumbnailSec = seconds;
    this.createThumbnail();
    return false;
  }

  seekerToSeconds(seek){
    var duration = this.element.duration;
    console.log('seeking to ', duration * seek);
    return duration * seek;
  }

  createThumbnail(){
    if(!this.canvas){
      this.canvas = document.createElement('canvas');
      this.canvas.width = 1280;
      this.canvas.height = 720;
    }
    this.inProgress = true;
    this.element.addEventListener('seeked', () => {
      console.log(this.element.videoWidth, this.canvas.toDataURL("image/jpeg"));
      this.canvas.getContext('2d').drawImage(this.element, 0, 0, this.element.videoWidth, this.element.videoHeight);
      this.thumbnail.next([this.canvas.toDataURL("image/jpeg"), this.thumbnailSec]);
      this.inProgress = false;
    });
  }

}
