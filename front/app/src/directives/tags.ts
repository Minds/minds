import { Directive, Inject, ElementRef }  from 'angular2/angular2';

@Directive({
  selector: '[tags]',
  inputs: ['tags']
})

export class TagsLinks {

  element : any;
  rendered : boolean = false;

  constructor(@Inject(ElementRef) _element : ElementRef) {
    this.element = _element.nativeElement;
    setTimeout(() => {
      this.render();
    });
  }

  render() {

    if(this.element.classList.contains('rendered') === true)
      return;

    var value = this.element.innerHTML;

    //<a>tag
    var url = /(\b(?:https?|ftp):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/i;
    value = value.replace(url, '<a href="$1" target="_blank"  class="mdl-color-text--blue-grey-600">$1</a>');

    //#hashtag
    var hash = /(^|\s)#(\w*[a-zA-Z_]+\w*)/gim;
    value = value.replace(hash, '$1<a href="/search?q=$2" target="_blank" class="mdl-color-text--blue-grey-600">#$2</a>');

    //@tag
    var at = /(^|\s)\@(\w*[a-zA-Z_]+\w*)/gim;
    value = value.replace(at, '$1<a href="/$2" target="_blank" class="mdl-color-text--blue-grey-500">@$2</a>');

    this.element.innerHTML = value;
    this.element.className += " rendered";
  }

}
