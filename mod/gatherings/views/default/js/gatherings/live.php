<?php if(0){?><script><?php } ?>
elgg.provide('minds.live');

minds.live.init = function() {
	
	var user = elgg.get_logged_in_user_entity();

	if(user){
		
		//load active chats on page loads
		ls = window.localStorage;
		var activeChats = JSON.parse(ls.getItem('activeChats'));
		if(activeChats){
			$.each(activeChats, function(){
				minds.live.openChatWindow(this.id, this.name, '', true);
			});
		}

		//store a list of the logged in users
		var availableUsers = [];

		var guid = new String(user.guid);
		
	
		/*
		 * Send a message
		 */
		$(document).on('keydown', '.minds-live-chat-userlist li input', function(e){ 
			input = $(this);
			parent = input.parents('li');
			//tell the user that we are typing..
			portal.find().send("typing", { 
				to_guid: parent.attr('id') 
			}); 
			if(e.which == 13){
				portal.find().send("message", { to_guid: parent.attr('id'), message: $(this).val(), from_name:user.name }); 
				$(this).val('');
			}
			//	minds.live.sendChat();
		});
		
		$(document).on('click', '.video', function(e){
			//set up the one-one call. we need a conference. 
		//	var ctrl = minds.live.startP2PGathering(elgg.get_logged_in_user_guid());
			parent = $(this).parents('li');
			portal.find().send("video", { 
				to_guid: parent.attr('id'),
				status: 'call',
			//	caller_salt: minds.live.streamSalt(),
				//reciever_salt: minds.live.streamSalt() //set both salts now and just expect the user to arrive..
			}); 
		});
	
		
		/**
		 * The connection to the socket server
		 */
		portal.open("http://localhost:8080/", { sharing:true }).on({
			open: function() {
				//subscribe the user to the site chat
				portal.find().send("connect", { guid: user.guid, name: user.name, username: user.username});
			},
			close: function(reason) {
				//remove the user from the site chat..
				portal.find().send("close", { guid: user.guid });
			},
			connect: function(){
				console.log('you are connected. hurrah!');
				
				//are we on a gathering page?
				if($(document).find('.gathering').length > 0){
					//minds.live.startGathering($(document).find('gathering').attr('data-guid'));
					
					//DONT JOIN THE GATHERING UNLESS YOUR CAMERA IS SHOWING
					$(document).find('.gathering').append('<video id="cam-'+ elgg.get_logged_in_user_guid()+'" autoplay muted></video>');
					
					id = 'cam-'+elgg.get_logged_in_user_guid();
					wrapRTC.openWebcam({
						element: document.getElementById(id),
						onSupportFailure: function (msg) {
							console.log('err',msg);
						},
						onError: function (code) {
							console.log('code',code);
							switch (code) {
								case 1:
						      //  _this._error(call_key);
						        break;
						    default:
						    }
						},
						setStream: function (stream) {
							minds.live.rtcLocalStream = stream;	
							console.log('trying to join');
							portal.find().send('gathering', {
								guid: $(document).find('.gathering').attr('data-guid'),
								status: 'join'
							});
						},
						audioMonitor: function(db){
							//minds.live.audioMonitor(id, db);
							percent = 100 + ( db * 2.083 );
							
							if(percent > 25){
								if(!minds.live.speaking){
									minds.live.speaking = true;
									portal.find().send('gathering', {
										guid: $(document).find('.gathering').attr('data-guid'),
										status: 'talking',
										volume: percent
									});
								}
							} else {
								if(minds.live.speaking){
									minds.live.speaking = false;
									portal.find().send('gathering', {
										guid: $(document).find('.gathering').attr('data-guid'),
										status: 'not-talking'
									});
								}
							}
						}
					});
										
				}
			},
			message: function(data) {
				console.log(data);
				data.message = minds.live.linkify(data.message);
				
				// The user is sending the message
				if(data.from_guid == elgg.get_logged_in_user_guid()){
					box = $('.minds-live-chat-userlist').find('li.box#' + data.to_guid);
					var from = "You: ";
					minds.live.saveCacheChat(data.to_guid, from + data.message, box.find('h3').text());
				} else {
					//play sound
					document.getElementById('sound').play();
					box = $('.minds-live-chat-userlist').find('li.box#' + data.from_guid);
					if(box.length == 0){
						minds.live.openChatWindow(data.from_guid, data.from_name, data.message);
						return true;	
					}
					box.addClass('active');
					var from = box.find('h3').text() + ": ";
					minds.live.saveCacheChat(data.from_guid, '<span class="user_name">'+from+'</span>'+ data.message, data.from_name);
				}
				
				box.find('.messages').append(
						'<span class="message"><span class="user_name">'+from+'</span>' + data.message + '</span>'
					)
					.animate({ scrollTop: box.find('.messages')[0].scrollHeight},1000);
					
				// return to the sender that we have recieved the message
				if(data.from_guid != elgg.get_logged_in_user_guid())
					portal.find().send("recieved", { to_guid: data.from_guid });

			},
			typing: function(data){
				$('.minds-live-chat-userlist').find('li.box#' + data.from_guid).find('.rt-stats')
					.html('typing...')
					.delay(1000)
					.queue(function(n){ $(this).html(''); n(); });
			},
			recieved: function (data){
				$('.minds-live-chat-userlist').find('li.box#' + data.from_guid).find('.rt-stats')
					.html('recieved')
					.delay(2000)
					.queue(function(n){ $(this).html(''); n(); });
			},
			video: function(data){
				
				var status = data.status;
				var isCaller = elgg.get_logged_in_user_guid() == data.from_guid;

				
				if(elgg.get_logged_in_user_guid() == data.from_guid){ 
					var box = $('.minds-live-chat-userlist').find('li.box#' + data.to_guid);
				} else {
					var box = $('.minds-live-chat-userlist').find('li.box#' + data.from_guid);
				}
				
				$(document).on('click', '#hangup', function(){
					portal.find().send("video", { 
						to_guid: data.from_guid,
						status: 'hangup'
					});
					portal.find().send("video", { 
						to_guid: data.to_guid,
						status: 'hangup'
					});
				});
				
				switch(status){
					
					/*case 'call' : 
						console.log('calling', data);
						if(isCaller){ //user is the caller
							box.find('.call').prepend('<button id="hangup">Hangup</button>');
							
							//start the webcam stream for the caller. 
							//minds.live.videoController.p2pVideo(data.caller_salt, data.reciever_salt, 'flash-p2p-'+ data.to_guid);
							
							
							box.css('bottom', '375px');
						} else {
							
							box.find('.call').prepend('<button id="answer">Answer</button><button id="hangup">Reject</button>');
						}
						
						callerSalt = data.salt;
	
					break;*/
					/*case 'answer' :
					
						
						isCaller =  elgg.get_logged_in_user_guid() == data.to_guid; //in this case the reciever is the sender
					
						console.log('answered');
						box.find('.call button').remove();
						box.find('.call').prepend('<button id="hangup">Hangup</button>');
						
						if(!isCaller){
							//load the callers cam and send your own
							//minds.live.videoController.p2pVideo(data.reciever_salt, data.caller_salt, 'flash-p2p-'+ data.to_guid);
							
							box.css('bottom', '375px');
						}
						
					break;*/
					case 'call':
					
						if(data.from_guid != elgg.get_logged_in_user_guid()) return;

						tone = document.getElementById("tone");
						tone.play();
						
						box.find('.call').prepend('<button id="hangup">Hangup</button>');
						box.find('video').css('display','block');
						
						wrapRTC.openWebcam({
							element: document.getElementById('local-'+data.from_guid),
							onSupportFailure: function (msg) {
								console.log('err',msg);
							},
							onError: function (code) {
								console.log('code',code);
								switch (code) {
									case 1:
							      //  _this._error(call_key);
							        break;
							    default:
							    }
							},
							setStream: function (stream) {
								console.log(stream); 
								wrapRTC.callPeer(stream, {
									element: document.getElementById('remote'+data.from_guid),
									onError: function (error) {
										console.log('error', error);
										//_this._error(call_key, error)
									},
									setPC: function (pc) {
										minds.live.pc = pc;
									},
						            signalOut: function (obj) {
						            	if(!obj) return;
						            	console.log(obj);
						            	switch(obj.type){
						            		//an offerer msg has been created. Send to our recipients
						            		//@todo send this to multiples
						            		case 'offer':
						            			portal.find().send('video', { 
						            				to_guid: data.to_guid, 
						            				msg: obj, 
						            				status: 'offer'
						            			});
						            			break;
						            		case 'candidate':
						            			portal.find().send('video', { 
						            				to_guid: data.to_guid, 
						            				msg: obj, 
						            				status: 'signal'
						            			});
						            			break;
						            	}
						            	
						            },
						            connected: function () {
						            	console.log('connected');
						               // _this.onCallStatusUpdate("connected")
						            },
						            disconnected: function () {
						            	console.log('disconnected');
						                //_this._stop(false)
						            }
								})
							}
						});
					
   					break;
					case 'hangup':

						wrapRTC.stopConnection(minds.live.pc);
						
						//wrapRTC.stop(document.getElementById('local'), )
						box.find('video').css('display','none');
						box.find('button').remove();
						
						box.find('.call').append('<div id="flash-p2p-'+ data.to_guid + '"></div><div id="flash-p2p-'+ data.from_guid + '"></div>');
						
						box.css('bottom', '200px');
					break;
					
					/**
					 * Used in webrtc setup. A reciever, aka peer, will recieve this and then output an answer callback to the caller, aka offerer
					 */
					case 'offer':
					
						if(elgg.get_logged_in_user_guid() == data.from_guid) return; //dont send offers to yourself
						
						ringer = document.getElementById("ringer");
						ringer.play();
						
						/**
						 * Put the answer reject button to the user
						 */
						box.find('.call').prepend('<button id="answer">Answer</button><button id="hangup">Reject</button>');
						
					
						/**
						 * Event listener for when the user accepts the offer, aka answers the call
						 */
						$(document).on('click', '#answer', function(){
														
							isCaller =  elgg.get_logged_in_user_guid() == data.to_guid; //in this case the reciever is the sender
							box.find('video').css('display','block');
							
							console.log('answered');
							box.find('.call button').remove();
							box.find('.call').prepend('<button id="hangup">Hangup</button>');
							
							document.getElementById("ringer").pause();
							
							//if(!isCaller){
								//load the callers cam and send your own
								//minds.live.videoController.p2pVideo(data.reciever_salt, data.caller_salt, 'flash-p2p-'+ data.to_guid);
								
								
							//}
							
							wrapRTC.openWebcam({
								element: document.getElementById('local'),
								//constraints: {"video": {"mandatory": { chromeMediaSource: "screen"}}},
								onSupportFailure: function (msg) {
								console.log('err',msg);
								},
								onError: function (code) {
									console.log('code',code);
									switch (code) {
										case 1:
								      //  _this._error(call_key);
								        break;
								    default:
								    }
								},
								setStream: function (stream) {
						
									wrapRTC.answer( data.msg, stream, {
										element: document.getElementById('remote'),
										setPC: function(pc){
											minds.live.pc = pc;
										},				
										onError: function (e){
											console.log('error',e);	
										},	
										signalOut: function (obj) {
											if(!obj) return;
											portal.find().send('video', {
												to_guid:data.from_guid, 
												msg:obj, 
												status:'signal'
											});
									            	
									   },
									   connected: function () {
											console.log('connected');
			                           },
			                            disconnected: function () {
											console.log('disconnected');
			                            }
										
									});
								}
							});	
						});
					
						break;
						
					/**
					 * Web rtc callbacks or signals are picked up here. These could be candidate requests or answers
					 */
					case 'signal':
						var msg = data.msg;
						console.log('signaled', msg);
						switch(msg.type){
							
							case 'candidate':
									wrapRTC.candidate(minds.live.pc, msg);
								
								//wrapRTC.candidate(self.apc, msg);
								break;
							case 'answer':
								//wrapRTC.setRemoteDescription(self.apc, msg);
								if(minds.live.pc)
									wrapRTC.setRemoteDescription(minds.live.pc, msg);
									
								box.find('.local').addClass('active');
								document.getElementById("ringer").pause();
								document.getElementById("tone").pause();
								
								box.css('bottom', '375px');
								break;
						}
			
						break;
				
				}

			},
			gathering: function(data){
				
				if(!minds.live.peer_connections)
					minds.live.peer_connections = [];
				
				/**
				 * A gathering is a chat of more than one person
				 */
				switch(data.status){
					case "join":
						/**
						 * The other users in the gathering recieve this and then send out their streams
						 */
						if(minds.live.rtcLocalStream){
						
							//some has joined. create a new peer connection and send your stream.
							var id = "cam-"+data.from_guid;
							if(!document.getElementById(id)){
								$('.gathering').prepend('<video autoplay id="'+ id + '"></video>'); //an ugly mash of jquery and core? why?
							}
							
							wrapRTC.callPeer(minds.live.rtcLocalStream, {
								element: document.getElementById(id),
								onError: function (error) {
									console.log('error', error);
									//_this._error(call_key, error)
								},
								setPC: function (pc) {
									minds.live.peer_connections.push(pc);
								},
								signalOut: function (obj) {
									if(!obj) return;
									portal.find().send('gathering', {
										guid: data.guid,
										status: 'signal',
										msg: obj
									});
								},
								setStream: function(stream){
									console.log(stream);
								}
							});
						}

						break;
					case "signal":
						/**
						 * Webrtc needs to send messages to each to users in order to connect
						 */
						if(!data.msg) return;
						switch(data.msg.type){
							case "candidate":
								for (var i=0; i<minds.live.peer_connections.length; i++){
									wrapRTC.candidate(minds.live.peer_connections[i], data.msg);
								}
								break;
							case "answer":
								if(elgg.get_logged_in_user_guid() == data.from_guid) return;
								console.log('you answered', minds.live.peer_connections);
								for (var i=0; i<minds.live.peer_connections.length; i++){
									wrapRTC.setRemoteDescription(minds.live.peer_connections[i], data.msg);
								}
								break;
							case "offer":
							
								if(elgg.get_logged_in_user_guid() == data.from_guid) return;
								
								var id = "cam-"+data.from_guid;
								if(!document.getElementById(id)){
									$('.gathering').prepend('<video id="'+ id +'" autoplay></video>'); 
								}
								
								//answer the offer..
								wrapRTC.answer(data.msg, null,{
									element: document.getElementById(id),
									onError: function (error) {
										console.log('error', error);
										//_this._error(call_key, error)
									},
									setPC: function (pc) {
										minds.live.peer_connections.push(pc);
									},
									signalOut: function (obj) {
										portal.find().send('gathering', {
											guid: data.guid,
											status: 'signal',
											msg: obj
										});
									},
									setStream: function(stream){
										console.log(stream);
									}
								})
								
							break;
						}
						break;
					
					case 'talking':
						$('#cam-'+data.from_guid).css({ 'box-shadow': '0 0 16px #4690D6', 'border':'1px solid #4690D6'});
						break;
					case 'not-talking':
						$('#cam-'+data.from_guid).css({ 'box-shadow': '0 0 16px #888', 'border':'1px solid #888'});
						break;
				}
			},
			error: function(error){
				console.log(error);
				if(error.code == 1){
					err_msg = "The user could not be reached, your message has not been sent";
				}
				box = $('.minds-live-chat-userlist').find('li.box#' + error.to_guid);
				box.find('.messages').append('<span class="message"><br/><i>' + err_msg + '</span>')
					.animate({ scrollTop: box.find('.messages')[0].scrollHeight},1000);
			},
			waiting: function(delay, attempts) {
				console.log("The socket will try to reconnect after " + delay + " ms");
				console.log("The total number of reconnection attempts is " + attempts);
			},
			heartbeat: function() {
				console.log("The server's heart beats");
			},
			users: function(data){
				var users = data.users; //format GUID=>LAST_ACTION
				var guids = Object.keys(users);
				
				var user_list = $('.minds-live-chat-userlist .userlist ul');
				user_list.html('');
				for(var i=0; i < guids.length; i++){
					console.log(guids);
					var guid = guids[i];
					if(guid != elgg.get_logged_in_user_guid()){
						var user = users[guid];
						user_list.append('<li class="user" id="'+ guid + '"> <h3>'+user.name+'</h3></li>');
					}
				}
			}
		});
	
		/**
		 * Li click hooks
		 */
		$(document).on('click','.minds-live-chat-userlist li h3', function (e) {
			
			//update the user list.
			portal.find().send("users");
	
			toggles = $(this).parent();
			userlist = $(this).parents('userlist');
		
			if(userlist && toggles.hasClass('user')){
				
				box = $('.minds-live-chat-userlist').find('li.box#' + toggles.attr('id'));
				if(box.length == 0){
					var guid = toggles.attr('id');
					minds.live.startChat(guid);
				} else {
					box.addClass('toggled');
				}
			} else {
				if(toggles.hasClass('toggled')){
					toggles.removeClass('toggled');
				} else {
					toggles.addClass('toggled');
					$(this).parent().find('input').focus();
					//$(this).parent().find('messages').animate({ scrollTop:  $(this).parent().find('messages')[0].scrollHeight},1000);
				}
			}
			toggles.removeClass('active');	
		});

		$(document).on('click', '.minds-live-chat-userlist li .del', function (e) {
			minds.live.removeChat($(this).parent().attr('id'));
		});


		//foreach chat window we have, give it an offset 
		minds.live.adjustOffset();		
	}
}


minds.live.startChat = function(guid){
	minds.live.openChatWindow(guid, 'a test chat', '');
}

minds.live.videoController = {
	
	sendVideo: function(salt, id){
		this.flash(true, salt, id);
	},
	recieveVideo: function(salt, id){
		this.flash(false, salt, id);
	},
	
	p2pVideo: function(upstream_salt, downstream_salt, id){
		this.flash(upstream_salt, downstream_salt, id);
	},
	flash: function(upstream_salt, downstream_salt, id){
		var flashvars = {
			//csMediaServerURI: encodeURIComponent("rtmp://video.babelroom.com:1936/oflaDemo"),
			upstream_salt: upstream_salt,
			downstream_salt: downstream_salt
		};
		var params = {};
		var attributes = {styleclass: 'flash_obj'};
		swfobject.embedSWF(elgg.get_site_url() + "mod/gatherings/resources/flash/p2p.swf", id, "100%", "100%", "8.0.0", "expressInstall.swf", flashvars, params, attributes);
	}
}

/**
 * Retrieve the gathering info. Creds, ids etc
 */
minds.live.getGatheringInfo = function(guid){
	return $.parseJSON($.ajax(
		{
			async: false,
			url: elgg.get_site_url() + 'gatherings/join/'+guid,
			type: "GET",
			dataType: "json"
		}
	).responseText); 
}

/**
 * The api handling function
 */

minds.live.apiInstance = function(guid, controllers) {
	var gathering = guid, 
		a = window['g'+gathering];

	if (!a) {
		
		var g = minds.live.getGatheringInfo(guid);

		/* api instance not yet created for this conference */
		a = BR.v1.api.create({
			hosts: "http://api.babelroom.com", //@todo make this configurable
			authentication: {token: g.token},
			conference_id: g.cid,
			controllers: controllers
		});
		
		a.gathering_guid = gathering;
		
		/* start the conference stream after the DOM is fully loaded */
		jQuery(document).ready(function() { a.start(); });
		console.log('finisihed loading g'+gathering);
		window['g'+gathering] = a;
	}

	return a;
}

/**
 * Start a gathering (bblr)
 */
minds.live.startGathering = function(guid){
	return minds.live.apiInstance(guid, [minds.live.gatheringController]);
}
minds.live.startP2PGathering = function(guid){
	return minds.live.apiInstance(guid, [minds.live.p2pGathering]);
}

/**
 * The gatherings controllers
 */
minds.live.gatheringController = {
	type: 'chat',
	onInit: function(){
		_this=this;
		
		$(document).on('keydown', '.chat > .messages .input input ', function(e){ 
			input = $(this);
			if(e.which == 13){
				_this.sendMessage( {text: input.val()}, 
									function(e){ 
										input.val(''); 
								});
			}
		});
		
		//this.startBroadcast();
	},
	onMessage: function(msg){ 
		var chat =  $('.gathering .chat');
		chat.append('<span class="message">' + msg.user + ': ' + msg.text + '</span>')
				.animate({ scrollTop: chat[0].scrollHeight},0);
	},
	startBroadcast: function(){
		_this = this;
		function make_key() {
			return Math.random().toString(36).substring(2)
		}
		function do_flash(broadcast, stream_salt) {
			var broadcast = true;
			   var flash_id = 'flash';
               var video_div = $('video');
               video_div.append('<div id="' + flash_id + '" style="display: none;"><h1>You need the Adobe Flash Player for this demo, download it by clicking the image below.</h1>            <p><a href="//www.adobe.com/go/getflashplayer"><img src="//www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player" /></a></p></div>');
                var flashvars = {
                   csMediaServerURI: _this._api.context.media_server_uri,
                    csStreamId: stream_salt
                }; 
                console.log(_this._api.context);
                var params = {};
                var attributes = {};
                swfobject.embedSWF(_this._api.get_host("cdn") + "/cdn/v1/c/flash/" + (broadcast ? "brBroadcast.swf" : "brViewer.swf"), flash_id, "100%", "100%", "8.0.0", "expressInstall.swf", flashvars, params, attributes);
            }
		do_flash(true, flash_key = make_key());    
		/*opts.showControl("stop", true);
		opts.showControl("start", false);*/
		videoOn = true;
		this._api.commands.videoAction("flash-" + flash_key, "")
		console.log(this._api.commands);
	},
	onClear: function(){ console.log('clearing'); },
}


/**
 * Adjust the offset so we can have multiple chats open
 */
minds.live.adjustOffset = function(e){
	 $(document).find('.minds-live-chat-userlist li.box').each( function() {
                       // console.log($(this).offset().left);
                        prev = $(this).prev();
                       // console.log(prev.html());
                        if(prev){
                                $(this).offset({ left:prev.offset().left + prev.width() + 35});
                        }
                });
}


/**
 * Open a chat window
 * 
 * Gathers information about a user
 */
minds.live.openChatWindow = function(id,name,message, minimised){
	
	var name = name;
	var username = '';
	var avatar_url = '';
	
	elgg.get('services/api/rest/json?method=user.get_profile&username='+id, {
		success: function (data){
			name = data.result.core.name;
			username = data.result.core.username;
			avatar_url = data.result.avatar_url;
		
			 var newmsg = '';	
			var cache = minds.live.getCacheChat(id);
			if(cache){
				var length = cache.length;
					var newmsg = '';
					for (var i = 0; i < length; i++) {
						newmsg	+= '<span class="message">' + cache[i] + '</span>';
					}
			}
					
			if(message){
				message = '<span class="message"><span class="user_name">'+name+'</span>: ' + message + '</span>';
			}
			
			message = newmsg + message;
			if(minimised){
				var liclass = 'toggle';
			} else {
				var liclass = 'toggled';
			}
			var box = '<li class="box '+ liclass + '" id="' + id + '">' +
		       			 	//'<a href="/' + username + '">'+ 
		       			 		'<img src="' + avatar_url + '" class="avatar"/>'+
		       			 		'<h3>'+
		       			 			name + 
		       			 		'</h3>' + 
		       			 	//'</a>' + 
		       			 	'<span class="del entypo">&#10062;</span>' +
		       			 	'<span class="video entypo">&#58277;</span>' +
		       			 	
		       			 	
		       			 	
		       			 	'<div class="call">' +
		       			 		'<div id="flash-p2p-'+ id + '" style="display:none; height:100px;"></div>' + 
		       			 		
		       			 		'<video id="local" class="local" autoplay muted></video>' + 
		       			 		'<video id="remote" class="remote" autoplay></video>' + 
		       			 	//	'<div id="flash" style="display:none;"> </div>'+ 
		       			 	'</div>' + 
		       			 	
		       			 '<div class="messages">' + message +  '</div>' + 
		       			 '<div class="rt-stats"></div>' +
		        		 '<div> <input type="text" class="elgg-input" /> </div>' +
				'</li>';
			 $('.minds-live-chat-userlist > ul').append(box);	
		//	$('.minds-live-chat-userlist > ul').append(box).animate({ scrollTop: $('.box#'+id).find('.messages')[0].scrollHeight},1000);
			if($('li.box#'+id).length > 0){
				$('li.box#'+id).animate({ scrollTop: $('li.box#'+id).find('.messages')[0].scrollHeight},1000);	
			}
			minds.live.adjustOffset();
		}
	})
	
}

minds.live.getCacheChat = function(id){
	var key = 'chat.'+id;
	return JSON.parse(sessionStorage.getItem(key));
}
minds.live.saveCacheChat = function(id, message, name){
	ss = window.sessionStorage;
	var key = 'chat.'+id;
	var chatSession = JSON.parse(ss.getItem(key));
	if(!chatSession){
		chatSession = new Array();
	}
	chatSession.push(message);
	sessionStorage.setItem(key, JSON.stringify(chatSession));

	//add key to list of active chats
	ls = window.localStorage;
	var activeChats = JSON.parse(ls.getItem('activeChats'));
	if(!activeChats){
		activeChats = {};
	}
	chat = { id: id,
		 name: name	
		};
	activeChats[id] = chat;

	ls.setItem('activeChats', JSON.stringify(activeChats));
}

/**
 * Remove a chat
 */
minds.live.removeChat = function(id){
	$(document).find('.box#'+id).remove();
	minds.live.removeCacheChat(id);
	minds.live.adjustOffset();	
}
minds.live.removeCacheChat = function(id){
	//remove the chat log
	ss = window.sessionStorage;
	var key = 'chat.'+id;
	sessionStorage.removeItem(key);
	
	//remove from the active chat list
	ls = window.localStorage;
	var activeChats = JSON.parse(ls.getItem('activeChats'));
	$.each(activeChats, function(i, val) {
		console.log(id);
		if(i == id){
			delete activeChats[i];
		}
	});
	ls.setItem('activeChats', JSON.stringify(activeChats));
}

/**
 * Turn text into links
 */
minds.live.linkify = function (inputText) {
    var replacedText, replacePattern1, replacePattern2, replacePattern3;

    //URLs starting with http://, https://, or ftp://
    replacePattern1 = /(\b(https?|ftp):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/gim;
    replacedText = inputText.replace(replacePattern1, '<a href="$1" target="_blank">$1</a>');

    //URLs starting with "www." (without // before it, or it'd re-link the ones done above).
    replacePattern2 = /(^|[^\/])(www\.[\S]+(\b|$))/gim;
    replacedText = replacedText.replace(replacePattern2, '$1<a href="http://$2" target="_blank">$2</a>');

    //Change email addresses to mailto:: links.
    replacePattern3 = /(([a-zA-Z0-9\-\_\.])+@[a-zA-Z\_]+?(\.[a-zA-Z]{2,6})+)/gim;
    replacedText = replacedText.replace(replacePattern3, '<a href="mailto:$1">$1</a>');

    return replacedText;
}

minds.live.streamSalt = function(){
	return Math.random().toString(36).substring(2);
}

elgg.register_hook_handler('init', 'system', minds.live.init);

