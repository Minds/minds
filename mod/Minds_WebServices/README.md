Minds Web Services
=================

##Installing  

This plugin should be installed as `mod/Minds_WebServices`.

##Examples  

This plugin provides a set of web services for clients such as Android apps and iPhone can communicate with the Minds site.   

Calling a web service: `http://www.minds.io/services/api/rest/json?method=the.method&param1=value1`  

###Kaltura
####Getting a list of videos  

Method: `GET`
Service method: `kaltura.get_list`
Parameters: `context, limit, offset, username`

Example output: ``` 
{
   "status":0,
   "result":[
      {
         "guid":11674,
         "title":"BBC ONE from files",
         "video_id":"0_hhoaxvl9",
         "thumbnail":"http:\/\/www.minds.tv\/p\/100\/sp\/10000\/thumbnail\/entry_id\/0_hhoaxvl9\/version\/0",
         "owner":{
            "guid":36,
            "name":"Mark",
            "username":"mark",
            "avatar_url":"http:\/\/mehmac.local\/elgg_1_8\/mod\/profile\/icondirect.php?lastcache=1332522479&joindate=1320085417&guid=36&size=small"
         }

	},
	...	
   ]
}
```

####Getting a single video 

Method: `GET`
Service method: `kaltura.get_video'
Parameters: `video_id`

Example output: ```
{
   "status":0,
   "result":
      {
         "guid":11674,
         "title":"BBC ONE from files",
         "video_id":"0_hhoaxvl9",
         "thumbnail":"http:\/\/www.minds.tv\/p\/100\/sp\/10000\/thumbnail\/entry_id\/0_hhoaxvl9\/version\/0",
         "owner":{
            "guid":36,
            "name":"Mark",
            "username":"mark",
            "avatar_url":"http:\/\/mehmac.local\/elgg_1_8\/mod\/profile\/icondirect.php?lastcache=1332522479&joindate=1320085417&guid=36&size=small"
         }

	}
}
```