// Type definitions for Minds
interface Minds{
 LoggedIn : boolean;
 user: Object;
 navigation: Array<any>;
 cdn_url: string;
}

 interface Window {
	Minds : Minds;
	componentHandler : any;
}
declare var window:Window;
