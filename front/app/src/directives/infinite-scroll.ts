import { Directive, View, EventEmitter, ViewContainerRef, ProtoViewRef, DomRenderer } from 'angular2/angular2';
import { Material as MaterialService } from "src/services/ui";

@Directive({
  selector: 'infinite-scroll',
  properties: ['distance', 'on'],
  events: ['loadHandler: load']
})
@View({
  template: '<loading-icon>loading more..</loading-icon>',
  directives: []
})

export class InfiniteScroll{
  viewContainer: ViewContainerRef;
  loadHandler: EventEmitter = new EventEmitter();
  _distance : any;
  _inprogress : boolean = false;
  _content : any;
  _listener : Function;

  constructor(viewContainer: ViewContainerRef) {
    this.scroll();
  }

  set distance(value : any){
    this._distance = parseInt(value);
  }

  scroll(){
    this._content = document.getElementsByClassName('mdl-layout__content')[0];
    var self = this;
    this._listener = () => {
      var height = self._content.scrollHeight,
          maxHeight = height - self._content.clientHeight,
          top = self._content.scrollTop,
          bottom = maxHeight - top,
          distance = (bottom / maxHeight) * 100;

      //console.log("Height " + height, "Max " + maxHeight, "Top " + top, "Bottom " + bottom, "Distance " + distance);

      if(distance <= self._distance){
        self.loadHandler.next(true);
      }
    };
    this._content.addEventListener('scroll', this._listener);
  }

  onDestroy(){
    this._content.removeEventListener('scroll', this._listener)
  }

}
