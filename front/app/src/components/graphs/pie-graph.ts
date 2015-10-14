import { Component, View, Directive, CORE_DIRECTIVES } from 'angular2/angular2';
import { GraphSVG } from './svg';
import { GraphPoints } from './points';

@Component({
  selector: 'minds-graph-pie',
  inputs: [ '_data: data', 'y: height', 'x: width' ]
})
@View({
  template: `
    <div [hidden]="!data"> <!-- Angular has svg problems... -->
      <svg viewBox="0,0,100,100" class="chart" style="height:100px; width:100px; margin: 0 auto; transform: rotate(90deg); background: #ddd; border-radius: 50%; display: block;">

        <circle *ng-for="#s of segments"
          r="25" cx="50" cy="50" [attr.stroke-dasharray]="s.array" [attr.stroke-dashoffset]="s.offset"
          class="pie"
          style="fill: transparent; stroke: #0074d9; stroke-width: 50px;"
          />

      </svg>
    </div>
    <div class="mdl-spinner mdl-js-spinner is-active" [hidden]="data"></div>
  `,
  directives: [ CORE_DIRECTIVES, GraphSVG, GraphPoints ]
})

export class PieGraph {

  data : Array<any>;
  segments : Array<any>;

  max : number = 156;
  radius : number = 25;
  diameter : number = 50;

  constructor() {
  }

  set _data(value : any){
    if(!value)
      return;
    this.data = value;
    this.calculate();
  }

  getBounds(){
    var max = 0;
    for(var stat of this.data){
      if(stat.total > max)
        max = stat.total;
    }
    return max;
  }

  calculate(){

    var r = 25;
    var c = Math.PI * (r * 2);

    for(var stat of this.data){

      var value = stat.total;
      console.log(value,stat);

      var offset = ( (100 - value) / 100 ) * c;

      this.segments = [
        {
          array: c,
          offset: offset
        }
      ];
    }

  }



}
