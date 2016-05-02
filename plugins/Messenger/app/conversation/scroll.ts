import { Directive, ElementRef } from 'angular2/core';
import { ScrollService } from '../../../services/ux/scroll';


@Directive({
  selector: '[minds-messenger-scroll]',
  inputs: ['messages']
})

export class MessengerScrollDirective{

  constructor(public scroll : ScrollService, public _element: ElementRef){

  }

  set messages(messages : Array<any>){
    if(!messages || !messages.length)
      return;

    setTimeout(() => {
      this._element.nativeElement.scrollTop = this._element.nativeElement.scrollHeight;
    });
  }

}
