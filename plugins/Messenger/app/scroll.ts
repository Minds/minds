import { Directive, ElementRef, EventEmitter } from '@angular/core';
import {Observable} from 'rxjs/Rx';

@Directive({
  selector: '[minds-messenger-scroll]',
  inputs: [ 'emitter', 'moreData' ],
  outputs: [ 'previous', 'next' ]
})

export class MessengerScrollDirective{

  previous = new EventEmitter();
  next = new EventEmitter();
  scroll: Observable<any>;
  element;
  moreData : boolean = true;

  constructor(public _element: ElementRef){
    this.element = _element.nativeElement;
    this.scroll = Observable.fromEvent(this.element, 'scroll');
  }

  set emitter(emitter : any){
    emitter.subscribe({
      next: () => {
        setTimeout(() => {
          this._element.nativeElement.scrollTop = this._element.nativeElement.scrollHeight;
        });
      }
    })
  }

  ngOnInit(){
    this.scroll
      .debounceTime(100)
      .subscribe(() => {

        if(!this.moreData)
          return;

        if(this.element.scrollTop <= 12){
          this.previous.next(true);
        }

        if(this.element.scrollTop + this.element.clientHeight >= this.element.scrollHeight - 12){
          this.next.next(true);
        }

      });
  }

}
