/* JR 5/2013 - parts of code borrowed heavily from other projects - see README */
(function(window) {
    var supported = null
        , version = null
        , getUserMedia = null
        , _stopUserMedia = null     /* BR: our non-standard addition */
        , attachMediaStream = null
        , sdpConstraints = {'mandatory': {
                          'OfferToReceiveAudio':true, 
                          'OfferToReceiveVideo':true }}
        ;

    /* --- */
    function init() {
        if (navigator.mozGetUserMedia) {
            supported = "firefox";
            RTCPeerConnection = function(){return mozRTCPeerConnection(/* note no arguments */);}
            RTCSessionDescription = mozRTCSessionDescription;
            RTCIceCandidate = mozRTCIceCandidate;
            getUserMedia = navigator.mozGetUserMedia.bind(navigator);
            _stopUserMedia = function(element, stream) {
                element.pause();
                element.mozSrcObject = null;
                };
            attachMediaStream = function(element, stream) {

			     
			    
			    var URL = window.URL;
                
                if (URL && URL.createObjectURL) {
			        element.src = URL.createObjectURL(stream);
			    } else {
			    	  element.mozSrcObject = stream;
			    } 
			   
                element.play();
                };
/*            reattachMediaStream = function(to, from) {
//                console.log("Reattaching media stream");
                to.mozSrcObject = from.mozSrcObject;
                to.play();
                }; */
            MediaStream.prototype.getVideoTracks = function() { return []; }
            MediaStream.prototype.getAudioTracks = function() { return []; }
            }
        else if (navigator.webkitGetUserMedia) {
            supported = "chrome";
            version = parseInt(navigator.userAgent.match(/Chrom(e|ium)\/([0-9]+)\./)[2]);
            getUserMedia = navigator.webkitGetUserMedia.bind(navigator);
            _stopUserMedia = function(element, stream) {
                element.pause();
                element.src = "";
                if (typeof(stream.stop)!=='undefined')
                    stream.stop();  /* this works for chrome Mac/25.0.1364.160 */
                };
            RTCPeerConnection = webkitRTCPeerConnection;
            attachMediaStream = function(element, stream) {
                 var URL = window.URL;
                
                if (URL && URL.createObjectURL) {
			        element.src = URL.createObjectURL(stream);
			    } else if (element.srcObject) {
			        element.srcObject = stream;
			    } else if (element.mozSrcObject) {
			        element.mozSrcObject = stream;
			    } else {
			        return false;
			    }
                };
/*            reattachMediaStream = function(to, from) {
                to.src = from.src;
                }; */

            // The representation of tracks in a stream is changed in M26.
            // Unify them for earlier Chrome versions in the coexisting period.
            if (!webkitMediaStream.prototype.getVideoTracks) {
                webkitMediaStream.prototype.getVideoTracks = function() {
                    return this.videoTracks;
                    };
                webkitMediaStream.prototype.getAudioTracks = function() {
                    return this.audioTracks;
                    };
                }

/* these methods are currently unused -- therefore commented out
            
            // New syntax of getXXXStreams method in M26.
            if (!webkitRTCPeerConnection.prototype.getLocalStreams) {
                webkitRTCPeerConnection.prototype.getLocalStreams = function() {
                    return this.localStreams;
                    };
                webkitRTCPeerConnection.prototype.getRemoteStreams = function() {
                    return this.remoteStreams;
                    };
                }
*/
/* -- not looking into opera further for the present, seems to be a lack of documentation and/or features
        else if (navigator.getUserMedia) {
            supported = "opera??";
            getUserMedia = navigator.getUserMedia.bind(navigator);
/*Opera 12: http://stackoverflow.com/questions/11642926/stop-close-webcam-which-is-opened-by-navigator-getusermedia
                element.pause();
                element.src = null; *./
*/
            }
    }

    function fireOnce(en, pc, opts) {
        var fn = opts[en];
        if (typeof(fn)==='undefined') return false;
        var fl = "_br_"+en;
        if (pc[fl]) return false;
        fn.call(opts);
        pc[fl] = true;
    }

    function createPeerConnection(opts) { 
        /*  https://groups.google.com/forum/#!topic/discuss-webrtc/b-5alYpbxXw
            http://code.google.com/p/natvpn/source/browse/trunk/stun_server_list */
        var pc_config = {"iceServers": [{"url": "stun:stun.l.google.com:19302"}]};
        var pc_constraints = {"optional": [{"DtlsSrtpKeyAgreement": true}]};
        var pc = null;
/*
    RTCPeerConection() for mozilla actually currently ignores arguments
        if (supported === "firefox") {
            pc_config = {"iceServers":[{"url":"stun:173.194.79.127"/* IP of stun.l.google.com, orig=stun:23.21.150.121*./}]};
            };
*/
        try {
            pc = new RTCPeerConnection(pc_config, pc_constraints);
//            opts.setPC && opts.setPC(pc); -- depreciate
/*
 -- this just for testing / dev ...
var fne = function(event,marker){console.log(marker,event); return true;}
pc.onsignalingstatechange = function(e){fne(e,7);}
pc.onstatechange = function(e){fne(e,8);}
pc.onopen = function(e){fne(e,9);}
pc.ondatachannel = fne;
pc.onidentityresult = fne;
pc.onnegotiationneeded = fne;
*/
    pc.oniceconnectionstatechange = function(event){
                /* apparently not fired in google chrome - 25.0.1364.160 */
                /* fires with XP / Chrome = 27.0.1453.116 m */
                /* fires with XP / Nightly (mozilla) 24.0a1 */
                if (pc.iceConnectionState==='connected') fireOnce('connected', pc, opts); 
                if (pc.iceConnectionState==='disconnected') fireOnce('disconnected', pc, opts); 
                }
            pc.onicecandidate = function(event) {
                if (!event.candidate) {
                    opts.signalOut && opts.signalOut(undefined);
                    if (/* yuck */ supported==='chrome' && version<27)
                        fireOnce('connected', pc, opts);
                    return;
                    }
                opts.signalOut && opts.signalOut({type: 'candidate',
                   label: event.candidate.sdpMLineIndex,
                   id: event.candidate.sdpMid,
                   candidate: event.candidate.candidate});
                }
            } catch (e) {
                opts.onSupportFailure && opts.onSupportFailure(e.message);
                return;
            }
            if (opts.element) { 
                pc.onaddstream = /*onRemoteStreamAdded*/ function(event) { 
                    attachMediaStream(opts.element, event.stream);
                    opts.setStream && opts.setStream(event.stream);
                    if(opts.audioMonitor)
						monitorAudio(event.stream, opts.audioMonitor); 
						
				//	console.log(event.stream);
				
				};
			}
            pc.onremovestream = function(event) { /*fne(event,222); console.log("onRemoteStreamRemoved;"); example also doesn't do anything here ... */
            };
        return pc;
    }

    function setStatus(state) {
//        __elem__.innerHTML = state; -- reference
    }

    function mergeConstraints(cons1, cons2) {
        var merged = cons1;
        for (var name in cons2.mandatory) {
            merged.mandatory[name] = cons2.mandatory[name];
            }
        merged.optional.concat(cons2.optional);
        return merged;
    }

    function setLocalAndSendMessage(how, pc, sessionDescription) {
    	//console.log('local send message',how, pc, sessionDescription);
        // Set Opus as the preferred codec in SDP if Opus is present.
        sessionDescription.sdp = preferOpus(sessionDescription.sdp);
        pc.setLocalDescription(sessionDescription);
        how.signalOut && how.signalOut(sessionDescription);
    }

    /* --- */
    function openWebcam(opts) {
    	var constraints = opts.constraints || {"audio": true, "video": {"mandatory": {}, "optional": []}};
    	console.log(constraints);
        try {
            getUserMedia(
                constraints,
                function(stream) /* success */ {
                    opts.element && attachMediaStream(opts.element, stream);
                    opts.setStream && opts.setStream(stream);
                    if(opts.audioMonitor)
						monitorAudio(stream, opts.audioMonitor); 
                    },
                function(error) /* failure */ {
                    /* called with error.code==1 if user denies permission (on chrome ) */
                    opts.onError && opts.onError(error.code);
                    });
            }
        catch (e) {
            opts.onSupportFailure && opts.onSupportFailure(e.message);
            }
    }

    /* --- */
    function callPeer(stream, opts) {
        var pc = createPeerConnection(opts);
        if (stream)
            pc.addStream(stream);
        var constraints = {"optional": [], "mandatory": {"MozDontOfferDataChannel": true}};
        // temporary measure to remove Moz* constraints in Chrome
        if (supported !== "firefox") {
            for (prop in constraints.mandatory) {
                if (prop.indexOf("Moz") != -1) {
                    delete constraints.mandatory[prop];
                    }
                }
            }
        constraints = mergeConstraints(constraints, sdpConstraints);
        pc.createOffer(function(sdp){setLocalAndSendMessage(opts, pc, sdp);}, opts.onError, constraints);
        opts.setPC && opts.setPC(pc);
    }

    function answer(msg, stream, opts) {
        var pc = createPeerConnection(opts);
        if (stream)
            pc.addStream(stream);
        var rtcsd = new RTCSessionDescription(msg);
        pc.setRemoteDescription(rtcsd);
        pc.createAnswer(function(sdp){setLocalAndSendMessage(opts, pc, sdp);}, opts.onError, sdpConstraints);
        opts.setPC && opts.setPC(pc);
    }

    function setRemoteDescription(pc, msg) {
        pc.setRemoteDescription(new RTCSessionDescription(msg));
    }

    function candidate(pc, msg) {
        var candy = new RTCIceCandidate({sdpMLineIndex:msg.label, candidate: msg.candidate});
        pc.addIceCandidate(candy);
    }

    function stop(element, stream) { _stopUserMedia(element, stream); }
    function stopConnection(pc) {
        try {
            pc.close(); /* this is throwing DOM exception 11 on chrome */
            }
        catch(e) {
            console.log && console.log('Exception in RTCPeerConnection.close()', pc, e);
            }
        pc = null;
    }

    function mediaChannelAction(stream, action) {
        var tracks, endis;
        switch(action) {
            case 'mute': tracks=stream.getAudioTracks(); endis = false; break;
            case 'unmute': tracks=stream.getAudioTracks(); endis = true; break;
            case 'video_off': tracks=stream.getVideoTracks(); endis = false; break;
            case 'video_on': tracks=stream.getVideoTracks(); endis = true; break;
            default:
                return false;
            }
        for(var i=0; i<tracks.length; i++) {
            tracks[i].enabled = endis;
            }
        return true;
    }

    /* === SDP manipulation utils === */
    // Set Opus as the default audio codec if it's present.
    function preferOpus(sdp) {
        var sdpLines = sdp.split('\r\n');
        // Search for m line.
        for (var i = 0; i < sdpLines.length; i++) {
                if (sdpLines[i].search('m=audio') !== -1) {
                    var mLineIndex = i;
                    break;
                } 
        }
        if (mLineIndex === null)
            return sdp;
        // If Opus is available, set it as the default in m line.
        for (var i = 0; i < sdpLines.length; i++) {
            if (sdpLines[i].search('opus/48000') !== -1) {                
                var opusPayload = extractSdp(sdpLines[i], /:(\d+) opus\/48000/i);
                if (opusPayload)
                    sdpLines[mLineIndex] = setDefaultCodec(sdpLines[mLineIndex], opusPayload);
                break;
            }
        }
        // Remove CN in m line and sdp.
        sdpLines = removeCN(sdpLines, mLineIndex);
        sdp = sdpLines.join('\r\n');
        return sdp;
    }

    // Set Opus in stereo if stereo is enabled.
    function addStereoToSDP(msg) {
        if (!msg.sdp)
            return;
        var sdpLines = msg.sdp.split('\r\n');
        var fmtpLineIndex = null;

        // Find opus payload.
        for (var i = 0; i < sdpLines.length; i++) {
            if (sdpLines[i].search('opus/48000') !== -1) {
                var opusPayload = extractSdp(sdpLines[i], /:(\d+) opus\/48000/i);
                break;
                }
            }

        // Find the payload in fmtp line.
        for (var i = 0; i < sdpLines.length; i++) {
            if (sdpLines[i].search('a=fmtp') !== -1) {
                var payload = extractSdp(sdpLines[i], /a=fmtp:(\d+)/ );
                if (payload === opusPayload) {
                    fmtpLineIndex = i;
                    break;
                    }
                }
            }
        // No fmtp line found.
        if (fmtpLineIndex === null)
            return ;

        // Append stereo=1 to fmtp line.
        sdpLines[fmtpLineIndex] = sdpLines[fmtpLineIndex].concat(' stereo=1');

        msg.sdp = sdpLines.join('\r\n');
    }

    function extractSdp(sdpLine, pattern) {
        var result = sdpLine.match(pattern);
        return (result && result.length == 2)? result[1]: null;
    }

    // Set the selected codec to the first in m line.
    function setDefaultCodec(mLine, payload) {
        var elements = mLine.split(' ');
        var newLine = new Array();
        var index = 0;
        for (var i = 0; i < elements.length; i++) {
            if (index === 3) // Format of media starts from the fourth.
                newLine[index++] = payload; // Put target payload to the first.
            if (elements[i] !== payload)
                newLine[index++] = elements[i];
        }
        return newLine.join(' ');
    }

    // Strip CN from sdp before CN constraints is ready.
    function removeCN(sdpLines, mLineIndex) {
        var mLineElements = sdpLines[mLineIndex].split(' ');
        // Scan from end for the convenience of removing an item.
        for (var i = sdpLines.length-1; i >= 0; i--) {
            var payload = extractSdp(sdpLines[i], /a=rtpmap:(\d+) CN\/\d+/i);
            if (payload) {
                var cnPos = mLineElements.indexOf(payload);
                if (cnPos !== -1) {
                    // Remove CN payload from m line.
                    mLineElements.splice(cnPos, 1);
                }
                // Remove CN line in sdp
                sdpLines.splice(i, 1);
            }
        }

        sdpLines[mLineIndex] = mLineElements.join(' ');
        return sdpLines;
    }
    
    //monitor the audio levels for the stream
    function monitorAudio(stream, callback){
    	
    	if (!(window.webkitAudioContext || window.AudioContext)) return;

		var src,
			fftSize = 1024,
			ac = window.AudioContext ? new AudioContext() : new webkitAudioContext(),
			analyser = ac.createAnalyser(),
			timeData = new Uint8Array(fftSize);
		
		if(typeof stream === 'string'){
			stream = document.getElementById(stream);
			src = ac.createMediaElementSource(stream);
		} else {
			src = ac.createMediaStreamSource(stream);
		}
		src.connect(analyser);
		//analyser.connect(ac.destination);
		//src.connect(ac.destination);
		
		setInterval(monitor,500);
			
		function monitor(){
			var total = i = 0
		    , percentage
		    , float
		    , rms
		    , db;
		    
		    analyser.getByteTimeDomainData(timeData);
		    
			while ( i < fftSize ) {
				float = ( timeData[i++] / 0x80 ) - 1;
				total += ( float * float );
		 	}
		 	
		 	rms = Math.sqrt(total / fftSize);
			db  = 20 * ( Math.log(rms) / Math.log(10) );
			// sanity check
			db = Math.max(-48, Math.min(db, 0));
			  
			callback(db);
			//console.log(stream, db);
		 }
    }
    
    /* === */

    /* --- */
    init();
    window.wrapRTC = {
        supported: supported,
        openWebcam: openWebcam,
        stop: stop,
        stopConnection: stopConnection,
        callPeer: callPeer,
        answer: answer,
        setRemoteDescription: setRemoteDescription,
        candidate: candidate,
        mediaChannelAction: mediaChannelAction,
        addStereoToSDP: addStereoToSDP,
        monitorAudio: monitorAudio
        }
})(window);