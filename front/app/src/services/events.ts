import {EventEmitter, Injector, bind} from 'angular2/angular2';

interface Event {
  emitter: EventEmitter;
}

export class LoggedIn implements Event{
	emitter = new EventEmitter();

	listen(callback : Function){
		console.log(this.emitter);
		this.emitter.observer({next: (data) => {
				callback(data);
			}
		});
	}

	emit(data : any = ""){
		this.emitter.next(data);
	}
}

var injector = Injector.resolveAndCreate([
	bind(LoggedIn).toFactory(() => {
		return new LoggedIn();
	})
]);

/**
 * Not sure if this is genious or stupid!
 */
export class Factory{

	static build(className){
		console.log(className)
		return injector.get(className);
	}
}
