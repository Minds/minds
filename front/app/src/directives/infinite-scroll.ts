import { Directive, View, EventEmitter, ElementRef, Inject } from 'angular2/angular2';
import { Material as MaterialService } from "src/services/ui";
import { ScrollFactory } from 'src/services/ux/scroll';

@Directive({
  selector: 'infinite-scroll',
  inputs: ['distance', 'on'],
  events: ['loadHandler: load']
})
@View({
  template: '<loading-icon>loading more..</loading-icon>',
  directives: []
})

export class InfiniteScroll{

  scroll = ScrollFactory.build();

  element : any;
  loadHandler: EventEmitter = new EventEmitter();
  _distance : any;
  _inprogress : boolean = false;
  _content : any;
  _listener;

  constructor(_element: ElementRef) {
    this.element = _element.nativeElement;
    this.init();
  }

  set distance(value : any){
    this._distance = parseInt(value);
  }

  init(){
    this._listener = this.scroll.listen((view) => {
      if(this.element.offsetTop - view.height <= view.top){
        //stop listening
        //   this.scroll.unListen(this._listener);
        this.loadHandler.next(true);
      }
    });
  }

  /*scroll(){
    var self = this;
    this._listener = (e) => {
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
    document.addEventListener('scroll', this._listener);
  }*/

  onDestroy(){
    document.removeEventListener('scroll', this._listener)
  }

}
