import { Pipe, Inject, Renderer }  from 'angular2/angular2';

@Pipe({
  name: 'tags'
})

export class TagsPipe {

  constructor() {
  }


  transform(value: string, args: any[]) {

    if(!value)
      return value;

    var el : any = document.createElement("div");
    el.innerHTML = value;
    value = el.innerText;

    //<a>tag
    var url = /(\b(https?|http|ftp):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/gim;
    value = value.replace(url, '<a href="$1" target="_blank">$1</a>');

    //#hashtag
    var hash = /(^|\s)#(\w*[a-zA-Z_]+\w*)/gim;
    value = value.replace(hash, '$1<a href="/search?q=#$2" target="_blank">#$2</a>');

    //@tag
    var at = /(^|\s)\@(\w*[a-zA-Z_]+\w*)/gim;
    value = value.replace(at, '$1<a class="tag" href="/$2" target="_blank">@$2</a>');

    return value;
  }


}
