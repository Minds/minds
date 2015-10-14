import { Component, View, EventEmitter, CORE_DIRECTIVES} from 'angular2/angular2';
import { Client } from "src/services/api";
import { RouterLink } from 'angular2/router';
import { Material } from 'src/directives/material';

import { MindsBanner } from './banner';

@Component({
  selector: 'minds-carousel',
  inputs: [ '_banners: banners', '_editMode: editMode'],
  outputs: ['done_event: done']
})
@View({
  template: `
    <i class="material-icons left" (click)="prev()" [hidden]="banners.length <= 1">keyboard_arrow_left</i>
    <minds-banner *ng-for="#banner of banners; #i = index"
      [src]="banner.src"
      [top]="banner.top_offset"
      [overlay]="true"
      [ng-class]="{'is-hidden': i != index}"
      [edit-mode]="editing"
      [done]="done"
      [(added)]="added"
      ></minds-banner>
    <i class="material-icons right" (click)="next()" [hidden]="banners.length <= 1">keyboard_arrow_right</i>
  `,
  directives: [ CORE_DIRECTIVES, MindsBanner ]
})

export class MindsCarousel{

  minds : Minds = window.Minds;
  banners : Array<any> = [];

  editing : boolean = false;
  src : string = "";
  modified : Array<any> = []; //all banners should be exported to here on the done event, and sent to parent

  done_event = new EventEmitter();
  done : boolean = false; //if set to true, tells the child component to return "added"
  rotate : boolean = true; //if set to true enabled rotation
  rotate_timeout; //the timeout for the rotator
  interval : number = 3000; //the interval for each banner to stay before rotating
  index : number = 0; //the current visible index of the carousel.

  constructor(){
    this.run();
  }

  /**
   * A list of banners are sent from the parent, if done are sent a blank one is entered
   */
  set _banners(value : any){
    if(value){
      this.banners = value;
    } else {
      this.banners.push({
        src: null
      });
    }
  }

  /**
   * If the parent set edit mode
   */
  set _editMode(value : boolean){
    if(this.editing){
      this._done();
      return;
    }

    this.editing = value;
    if(!this.editing){
      return;
    }
    this.rotate = false;
    var blank_banner = false;
    for(var i in this.banners){
      if(!this.banners[i].src)
        blank_banner=true;
    }
    if(!blank_banner){
      this.banners.push({
        src: null
      });
    }
  }

  /**
   * Fired when the child component adds a new banner
   */
  set added(value : any){
    var index = value.index;
    this.modified[index] = {
      guid: this.banners[index].guid,
      file: value.file,
      top: value.top
    }
    console.log('banner changed, recieved update', value);
  }

  /**
   * Once we retreive all the modified banners, we fire back to the parent the new list
   */
  _done(){
    this.done = true;
    //after one second?
    setTimeout(() => {
      this.done_event.next(this.modified);
      this.editing = false;
    }, 1000);
  }

  prev(){
    var max = this.banners.length -1;
    if(this.index == 0)
      this.index = max;
    else
      this.index--;
  }

  next(){
    var max = this.banners.length -1;
    if(this.index >= max)
      this.index = 0;
    else
      this.index++;
  }

  run(){
    if(this.rotate_timeout)
      clearTimeout(this.rotate_timeout);
    this.rotate_timeout = setTimeout(() => {
      if(this.rotate){
        var max = this.banners.length -1;
        if(this.index >= max)
          this.index = 0;
        else
          this.index++;
      }
      this.run();
    },this.interval);
  }

}
