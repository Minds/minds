import { Component, View, NgFor, NgIf, NgClass, Observable, FORM_DIRECTIVES} from 'angular2/angular2';
import { RouterLink } from "angular2/router";
import { MessengerConversation } from "./messenger-conversation";
import { MessengerSetup } from "./messenger-setup";

import { Client } from 'src/services/api';
import { SessionFactory } from 'src/services/session';
import { Material } from 'src/directives/material';

@Component({
  selector: 'minds-gatherings',
  viewBindings: [ Client ]
})
@View({
  templateUrl: 'templates/plugins/gatherings/gatherings.html',
  directives: [ NgFor, NgIf, NgClass, Material, RouterLink, MessengerConversation, MessengerSetup]
})

export class Gatherings {
  activity : any;
  session = SessionFactory.build();
  conversations : [];
  next : string =  "";
  setup : boolean = true;
  hasMoreData : boolean =  true;
  inprogress : boolean = false;
  cb : Date = new Date();
  search : {};
  baseMindsURL: string = "http://dev.minds.io";

	constructor(public client: Client){

    this.loadMore(true, true);

  }

  showConversation (guid: string, name: string){

  }

  loadMore(refresh: boolean, fakeData: boolean) {
      var self = this;
      /*
			//if (this.inprogress || !storage.get('private-key')) {*/
      if (this.inprogress){
				return false;
			}
			this.inprogress = true;
      console.log("loadMore " + refresh);
			this.client.get('api/v1/conversations', {	limit: 12,offset: this.next, cb: this.cb
			})
      .then(function(data) {
        if (!fakeData){
          if (!data.conversations) {
  					self.hasMoreData = false;
            self.inprogress = false;
  					return false;
  				} else {
  					self.hasMoreData = true;
  				};

  				if (refresh) {
  					self.conversations = data.conversations;
  				} else {
              for (var _i = 0, _a = data.conversations; _i < _a.length; _i++) {
                      var conversation = _a[_i];
                      self.conversations.push(conversation);
                }
  				}
        }
        else {
            self.getFakeConversations();
        }

				self.next = data['load-next'];
				//this.$broadcast('scroll.infiniteScrollComplete');
				//this.$broadcast('scroll.refreshComplete');
				self.inprogress = false;
			})
      .catch( function(error) {
        console.log("got error" + error);
				self.inprogress = true;
			});
		};

		doSearch(query: string) {
      var self = this;
      if (!query){
        console.log("clearing");
        this.refresh();
        return true;
      }
      console.log("searching " + query);
			this.client.get('api/v1/gatherings/search', {q: query,type: 'user',view: 'json'})
        .then(function(success) {
				  self.conversations = success.user[0];
			  })
        .catch(function(error){
          console.log(error);
      });
	   };

    showHovercard(conversation: {}){
      console.log("mouseover " + conversation);
      conversation.hover = true;
    }

    doneTyping($event) {
      console.log("typing " + $event.target.value);
      if($event.which === 13) {
        this.doSearch($event.target.value)
        $event.target.value = null;
      }
    };
    refresh() {
			this.search = {};
			this.inprogress = false;
			this.next = "";
			this.previous = "";
			this.cb = new Date();
			this.hasMoreData = true;
			this.loadMore(true);
		};

    subscribe($index) {
			this.conversations[$index].subscribed = true;
			if (!this.conversations[$index].subscriber) {
				alert({
					title: 'Subscribed',
					template: 'You can chat with ' + this.conversations[$index].name + ' when they subscribe to you too'
				});
			} else {
				window.location.href = '#/tab/gatherings/conversations/' + this.conversations[$index].guid + '/' + this.conversations[$index].name;
			}
			this.client.post('api/v1/subscribe/' + this.conversations[$index].guid, {})
        .then(function() {
			  })
        .catch(function() {
			  });
		};

		invite() {

			/*$ionicModal.fromTemplateUrl('templates/invite/invite.html', {
				scope: $scope,
				animation: 'slide-in-up'
			}).then(function(modal) {
				$scope.modal = modal;
				$scope.modal.show();
			})*;*/
			//need to get username first
			this.client.get('api/v1/channel/' + $rootScope.user_guid, {})
        .then(function(success) {
        /*	window.plugins.socialsharing.share("Hey! If you install the Minds app and tag me @" + success.channel.username + " we both get 100 points! \n\n",
  													'Join Minds and we both get 100 points to go viral!',
  													null,
  													$rootScope.node_url
  													);*/

	      })
        .catch(function(error) {

          });
		};
    getFakeConversations(){
      this.conversations = [{
      "guid": "100000000000000134",
      "type": "user",
      "subtype": false,
      "time_created": "1348444800",
      "time_updated": "1380486497",
      "container_guid": "0",
      "owner_guid": "100000000000000000",
      "site_guid": "1",
      "access_id": "2",
      "name": "John",
      "username": "john",
      "language": "en",
      "icontime": "1437947637",
      "legacy_guid": "134",
      "featured_id": "382261146771525632",
      "website": "",
      "briefdescription": "Information wants to be free",
      "dob": "",
      "gender": "",
      "city": "Wilton, CT",
      "subscribed": true,
      "subscriber": true,
      "subscribers_count": 2583,
      "subscriptions_count": 1628,
      "unread": 0,
      "last_msg": 1441287862
    },
    {
      "guid": "100000000000000599",
      "type": "user",
      "subtype": false,
      "time_created": "1349979579",
      "time_updated": "1378867544",
      "container_guid": "0",
      "owner_guid": "100000000000000000",
      "site_guid": "1",
      "access_id": "2",
      "name": "kram",
      "username": "markna",
      "language": "en",
      "icontime": "1435855391",
      "legacy_guid": "599",
      "featured_id": false,
      "website": "",
      "briefdescription": "test account",
      "dob": "2015-04",
      "gender": "male",
      "city": "NYC",
      "subscribed": true,
      "subscriber": true,
      "subscribers_count": 2600,
      "subscriptions_count": 36,
      "unread": 0,
      "last_msg": 1441277148
    },
    {
      "guid": "481158088242503691",
      "type": "user",
      "subtype": false,
      "time_created": "1440093027",
      "time_updated": false,
      "container_guid": "0",
      "owner_guid": "0",
      "site_guid": false,
      "access_id": "2",
      "name": "3 Feet High and Rising",
      "username": "Fredrik1991",
      "language": "en",
      "icontime": "1440201096",
      "legacy_guid": false,
      "featured_id": false,
      "website": "",
      "briefdescription": "Anti Austerity - Big on Charity",
      "dob": "",
      "gender": "",
      "city": "Stoke-on-Trent",
      "subscribed": true,
      "subscriber": true,
      "subscribers_count": 38,
      "subscriptions_count": 158,
      "unread": 0,
      "last_msg": 1441044753
    },
    {
      "guid": "100000000000000341",
      "type": "user",
      "subtype": false,
      "time_created": "1349106435",
      "time_updated": "1380469351",
      "container_guid": "0",
      "owner_guid": "100000000000000000",
      "site_guid": "1",
      "access_id": "2",
      "name": "Bill Ottman",
      "username": "ottman",
      "language": "en",
      "icontime": "1437409536",
      "legacy_guid": "341",
      "featured_id": "373789547047161856",
      "website": "",
      "briefdescription": "Co-creator, Founder, CEO",
      "dob": "",
      "gender": "",
      "city": "NYC",
      "subscribed": true,
      "subscriber": true,
      "subscribers_count": 10796,
      "subscriptions_count": 4297,
      "unread": 0,
      "last_msg": 1440707171
    },
    {
      "guid": "458569479538880512",
      "type": "user",
      "subtype": false,
      "time_created": "1434707483",
      "time_updated": false,
      "container_guid": "0",
      "owner_guid": "0",
      "site_guid": false,
      "access_id": "2",
      "name": "busssard",
      "username": "busssard",
      "language": "en",
      "icontime": "1434710156",
      "legacy_guid": false,
      "featured_id": "464134655193395213",
      "website": "http:\/\/mundivagus.wordpress.com",
      "briefdescription": "",
      "dob": "",
      "gender": "",
      "city": "",
      "subscribed": true,
      "subscriber": true,
      "subscribers_count": 730,
      "subscriptions_count": 271,
      "unread": 0,
      "last_msg": 1440666401
    },
    {
      "guid": "459039753216471040",
      "type": "user",
      "subtype": false,
      "time_created": "1434819605",
      "time_updated": false,
      "container_guid": "0",
      "owner_guid": "0",
      "site_guid": false,
      "access_id": "2",
      "name": "Paulholtphotography",
      "username": "Paulholtphotography",
      "language": "en",
      "icontime": "1435352220",
      "legacy_guid": false,
      "featured_id": false,
      "website": "",
      "briefdescription": "",
      "dob": "",
      "gender": "male",
      "city": "Bradford",
      "subscribed": true,
      "subscriber": true,
      "subscribers_count": 526,
      "subscriptions_count": 6171,
      "unread": 0,
      "last_msg": 1440377632
    }
  ];
    }

}
