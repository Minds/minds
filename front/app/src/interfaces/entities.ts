/**
 * Activity Object
 */
export interface MindsActivityObject {
	activity : Array<any>;
}

export interface Minds{
  LoggedIn : boolean;
  user: Object;
  navigation: Array<any>;
}

export interface Window {
	Minds : Minds;
	componentHandler : any;
}

export interface Conversation {
  guid: string;
  type: string;
  subtype: boolean;
  time_created: string;
  time_updated: string;
  container_guid: string;
  owner_guid: string;
  site_guid: string;
  access_id: string;
  name: string;
  username: string;
  language: string;
  icontime: string;
  legacy_guid: string;
  featured_id: string;
  website: string;
  briefdescription: string;
  dob: string;
  gender: string;
  city: string;
  subscribed: boolean;
  subscriber: boolean;
  subscribers_count: Number;
  subscriptions_count: Number;
  unread: Number;
  last_msg: Number;
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
