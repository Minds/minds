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

export interface MindsMessage{
	access_id: Number,
	category: boolean,
	container_guid: string,
	featured: boolean,
	featured_id: boolean,
	friendly_ts: boolean,
	guid: string,
	message: string,
	owner_guid: string
	subtype: string,
	time_created: Number,
	time_updated: Number,
	ownerObj: MindsMessageOwner
}

export interface MindsMessageOwner{
	access_id: string,
	container_guid: string,
	featured_id: boolean,
	guid: string,
	icontime: boolean,
	language: string,
	legacy_guid: boolean,
	name: string,
	owner_guid: string
	site_guid: boolean,
	subtype: boolean
	time_created: string,
	time_updated: boolean
	type: string,
	username: string
}
