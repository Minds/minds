import { Directive, ElementRef } from 'angular2/core';
import { ScrollService } from '../../../services/ux/scroll';


@Directive({
  selector: '[minds-messenger-scroll]',
  inputs: ['emitter']
})

export class MessengerScrollDirective{

  constructor(public scroll : ScrollService, public _element: ElementRef){

  }

  ngOnInit(){
    this.emitter.subscribe({
      next: () => {
        setTimeout(() => {
          this._element.nativeElement.scrollTop = this._element.nativeElement.scrollHeight;
        });
      }
    })
  }

}
