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

  constructor(viewContainer: ViewContainerRef) {
    this.scroll();
  }

  set distance(value : any){
    this._distance = parseInt(value);
  }

  scroll(){
    var content : any = document.getElementsByClassName('mdl-layout__content')[0];
    var self = this;
    content.addEventListener('scroll', () => {

        var height = content.scrollHeight,
            top = content.scrollTop,
            bottom = height - top,
            distance = (bottom / height) * 100;

        if(distance <= self._distance){
          self.loadHandler.next(true);
        }
      });
  }

}
