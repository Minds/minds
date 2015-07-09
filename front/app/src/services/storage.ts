import {Injectable} from 'angular2/angular2';

export class Storage {	
	get(key : string){
		return window.localStorage.getItem(key);
	}
	set(key : string, value : Object){
		return window.localStorage.setItem(key, value);
	}
	destroy(key : string){
		return window.localStorage.removeItem(key);
	}
}