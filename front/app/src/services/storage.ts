import {Injectable} from 'angular2/angular2';

export class Storage {	
	get(key){
		return window.localStorage.getItem(key);
	}
	set(key, value){
		return window.localStorage.setItem(key, value);
	}
	destroy(){
		return window.localStorage.removeItem(key);
	}
}