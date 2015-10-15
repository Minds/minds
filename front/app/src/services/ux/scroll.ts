import { EventEmitter, Observable, Injector, provide } from 'angular2/angular2';

export class Scroll{
  scroll = new EventEmitter();
  view : any;

  constructor(){
    this.view = document.getElementsByTagName('body')[0];
    this.view.scrollTop = 0;
    document.addEventListener('scroll', (position) => {
      this.scroll.next({ top: this.view.scrollTop, height: this.view.clientHeight });
    });
  }

  fire(){
    this.scroll.next({ top: this.view.scrollTop, height: this.view.clientHeight });
  }

  listen(callback : Function) : any {
    return this.scroll.observer({next: callback });
  }

  unListen(subscription : any){
    subscription.unsubscribe();
  }

}


var injector = Injector.resolveAndCreate([
	provide(Scroll, { useFactory: () => new Scroll() })
]);

export class ScrollFactory {
	static build(){
		return injector.get(Scroll);
	}
}
