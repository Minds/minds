import { Directive, EventEmitter, ViewContainerRef } from 'angular2/angular2';

@Directive({
  selector: 'svg',
  properties: [ 'height', 'width', 'viewbox' ]
})

export class GraphSVG {

  element : any;

  constructor(viewContainer: ViewContainerRef){
    this.element = viewContainer.element.nativeElement;
  }

  set height(value : any){
    this.element.setAttribute('height', value);
  }

  set width(value : any){
    this.element.setAttribute('width', value);
  }

  set viewbox(value : any){
    this.element.setAttribute('viewBox', value);
  }

}
