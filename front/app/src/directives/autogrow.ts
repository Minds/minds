import { Directive,  EventEmitter, ViewContainerRef, ProtoViewRef, Inject } from 'angular2/angular2';
import { Material as MaterialService } from "src/services/ui";

@Directive({
  selector: '[auto-grow]',
  inputs: ['autoGrow', 'for'],
  host: {
    '(keydown)': 'grow()'
  }
})


export class AutoGrow{

  _listener : Function;
  _element : any;

  constructor(@Inject(ViewContainerRef) viewContainer: ViewContainerRef) {

    this._element =  viewContainer.element.nativeElement;
    setTimeout(()=>{
      this.grow();
    });
  }

  grow(){
    this._element.style.overflow = 'hidden';
  //  if(!this._element.style.height)
    this._element.style.height = 'auto';
    this._element.style.height = this._element.scrollHeight + "px";
  }


}
