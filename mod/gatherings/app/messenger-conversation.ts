import { Component, View, NgFor, NgIf, NgClass, Inject, Observable} from 'angular2/angular2';
import { Router, RouteParams, RouterLink } from "angular2/router";
import { Client } from 'src/services/api';
import { SessionFactory } from 'src/services/session';
import { Material } from 'src/directives/material';

@Component({
  selector: 'minds-messenger-conversation',
  viewBindings: [ Client ]
})
@View({
  templateUrl: 'templates/plugins/gatherings/messenger-conversation.html',
  directives: [ NgFor, NgIf, NgClass, Material, RouterLink]
})

export class MessengerConversation {
  activity : any;
  session = SessionFactory.build();
  guid :string;//= $stateParams.username;
	name: string;//= $stateParams.name;
  messages: [];
  next: string
  previous: string;
  hasMoreData: boolean = true;
  inProgress: boolean = false;
  poll: boolean = true;
  publickeys: {};
  timeout: {};


  constructor(public client: Client,
    @Inject(Router) public router: Router,
    @Inject(RouteParams) public params: RouteParams
  ){
    this.guid = params.params['guid'];
    this.load();
	}

  /**
	 * Load more posts
	 */
	load() {
    var self = this;
		this.inProgress = true;

		console.log('loading messages from:' + this.next);

    this.client.get('api/v1/conversations/' + this.guid, {
				limit: 6,
				offset: this.next,
				cachebreak: Date.now()
			})
      .then(function(data) {
				self.newChat = false;
				self.inProgress = false;
				//now update the public keys
				self.publickeys = data.publickeys;

				if (!self.publickeys[self.guid]) {
					alert({
						title: 'Sorry!',
						template: self.name + " has not yet configured their encrypted chat yet."
					});
					//$state.go('tab.chat');
					return true;
				}

				if (!data.messages) {
					self.hasMoreData = false;
					return false;
				} else {
					self.hasMoreData = true;
				};

				var first;
				if (self.messages.length === 0) {
					first = true;
				} else {
					first = false;
				}

        for (var _i = 0, _obj = data.messages; _i < _a.length; _i++) {
            var message = _obj[_i];
            self.messages.push(conversation);
        }

				console.log("------ MESSAGES ARE LOADED ------");

				self.next = data['load-previous'];
				self.previous = data['load-next'];
				//self.$broadcast('scroll.refreshComplete');

				if (first) {
          //Must Scroll Bottom
          /*
					$timeout(function() {
						$ionicScrollDelegate.scrollBottom();
					}, 1000);
          */
				}

				self.poll = true;

			})
      .catch(function(error) {
				self.inProgress = false;
			});

		};

}
