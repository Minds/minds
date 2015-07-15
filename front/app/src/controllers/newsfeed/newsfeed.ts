import { Component, View, NgFor, NgIf, formDirectives} from 'angular2/angular2';
import { Client } from 'src/services/api';
import { Material } from 'src/directives/material';

@Component({
  selector: 'minds-newsfeed',
  viewInjector: [ Client ]
})
@View({
  templateUrl: 'templates/newsfeed/list.html',
  directives: [ NgFor, NgIf, Material, formDirectives ]
})

export class Newsfeed {

	newsfeed : Array<Object> = [];
	offset : string = "";

  postMeta = {
    title: "",
    description: "",
    thumbnail: "",
    url: ""
  }

	constructor(public client: Client){
		this.load();
	}

	/**
	 * Load newsfeed
	 */
	load(){
		var self = this;
		this.client.get('api/v1/newsfeed', {limit:12}, {cache: true})
				.then(function(data : MindsActivityObject){
					if(!data.activity){
						return false;
					}
					self.newsfeed = data.activity;
					self.offset = data['load-next'];
				})
				.catch(function(e){
					console.log(e);
				});
	}

	/**
	 * Post to the newsfeed
	 */
	post(post){
    if(!post.message)
      return false;

    console.log(this.postMeta);

    if(this.postMeta.title)
      Object.assign(post, this.postMeta);

		var self = this;
		this.client.post('api/v1/newsfeed', post)
				.then(function(data){
					self.load();
				})
				.catch(function(e){
					console.log(e);
				});
	}

  /**
   * Get rich embed data
   */
  timeout;
  getPostPreview(message){
    var self = this;

    var match = message.value.match(/(\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/ig);
		if (!match) return;
    var url;

		if ( match instanceof Array) {
			url = match[0];
		} else {
			url = match;
		}

		if (!url.length) return;

		url = url.replace("http://", '');
		url = url.replace("https://", '');
    console.log('found url was ' + url)

    if(this.timeout)
      clearTimeout(this.timeout);

    this.timeout = setTimeout(()=>{
      this.client.get('api/v1/newsfeed/preview', {url: url})
        .then((data : any) => {
          console.log(data);
          self.postMeta.title = data.meta.title;
          self.postMeta.url = data.meta.canonical;
          self.postMeta.description = data.meta.description;
          for (var link of data.links) {
              if (link.rel.indexOf('thumbnail') > -1) {
                  self.postMeta.thumbnail = link.href;
              }
          }
        });
    }, 600);
  }

	/**
	 * A temporary hack, because pipes don't seem to work
	 */
	toDate(timestamp){
		return new Date(timestamp*1000);
	}
}
