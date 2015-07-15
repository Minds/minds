/**
 * Sesions
 */
import { EventEmitter, Injector, bind } from 'angular2/angular2';

export class Session {
	loggedinEmitter = new EventEmitter();
	userEmitter = new EventEmitter();

	/**
	 * Return if loggedin, with an optional listener
	 */
	isLoggedIn(observe: any = null){

		if(observe){
			this.loggedinEmitter.observer({next: (is) => {
				if(is)
					observe(true);
				else
					observe(false);
				}
			});
		}

		if(window.Minds.LoggedIn)
			return true;

		return false
	}

	/**
	 * Get the loggedin user
	 */
	getLoggedInUser(observe: any = null){

		if(observe){
			this.userEmitter.observer({next: (user) => {
				observe(user);
			}});
		}

		if(window.Minds.user)
			return window.Minds.user;

		return false;
	}

	/**
	 * Emit login event
	 */
	login(user : any = null){
		this.loggedinEmitter.next(true);
		this.userEmitter.next(user);
	}

	/**
	 * Emit logout event
	 */
	logout(){
		this.loggedinEmitter.next(false);
		this.userEmitter.next(null);
	}

}

var injector = Injector.resolveAndCreate([
	bind(Session).toFactory(() => {
		return new Session();
	})
]);

export class SessionFactory {
	static build(){
		return injector.get(Session);
	}
}
