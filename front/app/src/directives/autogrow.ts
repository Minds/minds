import { Directive,  EventEmitter, ViewContainerRef, ProtoViewRef, Inject } from 'angular2/angular2';
import { Material as MaterialService } from "src/services/ui";

@Directive({
  selector: '[auto-grow]',
  inputs: ['autoGrow', 'for']
})


export class AutoGrow{

  _listener : Function;
  _element : any;

  constructor(@Inject(ViewContainerRef) viewContainer: ViewContainerRef) {

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
