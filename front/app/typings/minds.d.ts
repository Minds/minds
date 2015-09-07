// Type definitions for Minds


interface Window {
	Minds : {
		LoggedIn : boolean,
		user: Object,
		navigation: Array<any>
	};
	componentHandler : any;
}
declare var window:Window;


/**
 * Activity Object
 */
interface MindsActivityObject {
	activity : Array<any>;
}


/**
 * Minds response object
 */
interface MindsResponse {}
