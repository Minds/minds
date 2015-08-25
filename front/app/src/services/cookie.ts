/**
 * A very simple cookie service
 */
export class Cookie {

	/**
	 * Return a cookie by name
	 */
	get(key : string) : string{
		var cookies : Array<string> = document.cookie ? document.cookie.split('; ') : [];

		if(!cookies)
			return;

		for (let cookie of cookies) {
			let name : string,
				value : string;
			[name, value] = cookie.split('=');
			if(name == key)
				return value;
		}
		return;
	}

}
