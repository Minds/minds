import { Directive,  EventEmitter, ViewContainerRef, ProtoViewRef, DomRenderer } from 'angular2/angular2';
import { Material as MaterialService } from "src/services/ui";

@Directive({
  selector: '[auto-grow]',
  properties: ['autoGrow', 'for']
})


export class AutoGrow{
  viewContainer: ViewContainerRef;
  _listener : Function;
  _element : any;
//  growHandler: EventEmitter = new EventEmitter();

  constructor(viewContainer: ViewContainerRef) {
    this.viewContainer = viewContainer;
    var self = this;
    this._listener = () => {
      self.grow();
    };
    this._element =  viewContainer.element.nativeElement;
    this._element.addEventListener('keyup', this._listener);
    setTimeout(()=>{
      this.grow();
    });
  }

  grow(){
    this._element.style.overflow = 'hidden';
    this._element.style.height = 'auto';
    this._element.style.height = this._element.scrollHeight + "px";
  }


}
