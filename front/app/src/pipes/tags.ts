import { Pipe, Renderer }  from 'angular2/angular2';

@Pipe({
  name: 'tags'
})

export class TagsPipe {

  constructor() {
  }


  transform(value: string, args: any[]) {

    //if(!value)
      return value;

    //<a>tag
    var url = /(\b(https?|http|ftp):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/gim;
    value = value.replace(url, '<a href="$1">$1</a>');

    //#hashtag
    var hash = /(^|\s)#(\w*[a-zA-Z_]+\w*)/gim;
    value = value.replace(hash, '$1<a href="#/tab/search">#$2</a>');

    //@tag
    var at = /(^|\s)\@(\w*[a-zA-Z_]+\w*)/gim;
    value = value.replace(at, '$1<a class="tag" href="#/tab/newsfeed/channel/$2">@$2</a>');

    return value;
  }


}
