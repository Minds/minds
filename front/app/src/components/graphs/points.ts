import { Directive, EventEmitter, ViewContainerRef } from 'angular2/angular2';

@Directive({
  selector: '[points]',
  properties: [ '_points: points' ]
})

export class GraphPoints {

  element : any;

  constructor(viewContainer: ViewContainerRef){
    this.element = viewContainer.element.nativeElement;
  }

  set _points(value : any){
    this.element.setAttribute('points', value);
  }

}
