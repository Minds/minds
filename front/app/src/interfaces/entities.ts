/**
* Activity Object
*/
export interface MindsActivityObject {
	activity : Array<any>;
}

export interface MindsBlogEntity {
	guid : string,
	title : string,
	description : string,
	ownerObj : any
}

export interface Message {

}

export interface MindsUser {
	guid : string,
	name : string,
	username : string
}

export interface MindsGroup {
  guid : string,
  name : string,
  banner : boolean
}
