import { Directive, EventEmitter, ViewContainerRef, Inject } from 'angular2/angular2';

@Directive({
  selector: '[points]',
  inputs: [ '_points: points' ]
})

export class GraphPoints {

  element : any;

  constructor(@Inject(ViewContainerRef) viewContainer: ViewContainerRef){
    this.element = viewContainer.element.nativeElement;
  }

  set _points(value : any){
    this.element.setAttribute('points', value);
  }

}
