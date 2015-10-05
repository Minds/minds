import { Component, View, Directive, CORE_DIRECTIVES } from 'angular2/angular2';
import { GraphSVG } from './svg';
import { GraphPoints } from './points';

@Component({
  selector: 'minds-graph-impressions',
  inputs: [ '_impressions: impressions', 'y: height', 'x: width' ]
})
@View({
  template: `
    <svg fill="currentColor" [viewBox]="'0 0 ' + x + ' ' + y" style="stroke:#757575; opacity:0.8" xmlns="http://www.w3.org/2000/svg" >
      <!-- X Y, X Y (from top to bottom) -->
      <polyline [points]="points"
        style="fill:none;stroke-width:4"
      />

    </svg>
  `,
  directives: [ CORE_DIRECTIVES, GraphSVG, GraphPoints ]
})

export class GraphImpressions {

  impressions : Array<number>;
  points : string = "0 200, 500 0";

  y : number = 200;
  x : number = 500;
  y_padding : number = 20;

  constructor() {
    //this.calculate();
  }

  set _impressions(value : any){
    this.impressions = value;
    this.calculate();
  }

  getBounds(){
    var max = 0;
    var min = this.impressions[0];
    for(var stat of this.impressions){
      if(stat > max)
        max = stat;
      if(stat < min)
        min = stat;
    }
    //return max - min;
    return max;
  }

  calculate(){

    var y_bounds = this.getBounds();
    var y_divi = (y_bounds + this.y_padding) / this.y;

    var x_count : number = this.impressions.length;
    var x_diff : number = this.x / x_count;
    var x_ticker : number = 0;

    this.points = x_ticker + " " + this.y;
    for(var stat of this.impressions){
      x_ticker = x_ticker + x_diff;
      var y_stat = this.y - (stat / y_divi);
      this.points += ", " + x_ticker + " " + y_stat;
    }
    console.log(this.points);
  }



}
