/**
 * A very simple cookie service
 */
export class Cookie {	
	
	/**
	 * Return a cookie by name
	 */
	get(key : string){
		var cookies = document.cookie ? document.cookie.split('; ') : [];

		if(!cookies)
			return false;
		
		for (let cookie of cookies) {
			let name : string,
				value : string;
			[name, value] = cookie.split('=');
			if(name == key)
				return value;
		}

	}

}