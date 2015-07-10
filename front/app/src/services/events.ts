import {EventEmitter, Injector, bind} from 'angular2/angular2';

interface Event {
  emitter: EventEmitter;
}

class LoggedIn implements Event{ 
	emitter = new EventEmitter();
	
	listen(callback : Function){
		console.log(this.emitter);
		this.emitter.observer({next: (data) => {
				callback(data);
			}
		});
	}
	
	emit(data : Any = ""){
		this.emitter.next(data);
	}
}

/**
 * Not sure if this is genious or stupid!
 */
export class Factory{
	
	build(className : String, callback: Function){
		
	}
}