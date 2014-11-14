(function(vjs, vast, undefined) {
"use strict";
  var
  extend = function(obj) {
    var arg, i, k;
    for (i = 1; i < arguments.length; i++) {
      arg = arguments[i];
      for (k in arg) {
        if (arg.hasOwnProperty(k)) {
          obj[k] = arg[k];
        }
      }
    }
    return obj;
  },

  defaults = {
    skip: 5, // negative disables. Ignored for VPAID as VPAID asset controls it itself
    bitrate: 1000, //advised bitrate for VPAID ads
    viewMode: 'normal', //view mode for VPAID ads. Possible values: normal, thumbnail, fullscreen
    vpaidElement: undefined //html element used for vpaid ads
  },

  vastPlugin = function(options) {
    var player = this;
    var settings = extend({}, defaults, options || {});
    var vpaidObj, vpaidListeners = {}, vpaidIFrame = null, vpaidPlayer = null, vpaidTrackInterval = -1, vpaidSeeker;

    if (player.ads === undefined) {
        console.log("VAST requires videojs-contrib-ads");
        return;
    }

    // If we don't have a VAST url, just bail out.
    if(settings.url === undefined) {
      player.trigger('adtimeout');
      return;
    }

    //preserve support for older video.js versions
    function localize(text) {
      if (player.localize) {
        return player.localize(text);
      } else {
        return text;
      }
    }

    // videojs-ads triggers this when src changes
    player.on('contentupdate', function(){
      player.vast.getContent(settings.url);
    });

    player.on('readyforpreroll', function() {
      //in case we have something simple to show
      if (player.vast.sources) {
        player.vast.preroll();
      } else {
        player.vast.prerollVPAID();
      }
    });

    player.vast.getContent = function(url) {
      vast.client.get(url, function(response) {
        if (response) {
          for (var adIdx = 0; adIdx < response.ads.length; adIdx++) {
            var ad = response.ads[adIdx];
            player.vast.companion = undefined;
            var foundCreative = false, foundCompanion = false, foundVPAID = false;
            for (var creaIdx = 0; creaIdx < ad.creatives.length; creaIdx++) {
              var creative = ad.creatives[creaIdx];
              if (creative.type === "linear" && !foundCreative) {

                if (creative.mediaFiles.length) {

                  player.vastTracker = new vast.tracker(ad, creative);
                  var vpaidTech = player.vast.findOptimalVPAIDTech(creative.mediaFiles);
                  if (vpaidTech) {
                    foundVPAID = true;
                    player.vast.initVPAID(vpaidTech, function() {
                      player.vast.createVPAIDControls();
                      player.trigger('adsready');
                    });
                  } else {

                    player.vast.sources = player.vast.createSourceObjects(creative.mediaFiles);
                    if (!player.vast.sources.length) {
                      player.trigger('adtimeout');
                      return;
                    }

                    player.vast.initSimpleVAST(ad);
                  }

                  foundCreative = true;
                }

              } else if (creative.type === "companion" && !foundCompanion) {
                //TODO is it ever used?
                player.vast.companion = creative;

                foundCompanion = true;

              }
            }

            if (player.vastTracker) {
              //vpaid will trigger adsready in async manner when all assets are loaded
              if (!foundVPAID) {
                player.trigger("adsready");
              }
              break;
            } else {
              // Inform ad server we can't find suitable media file for this ad
              vast.util.track(ad.errorURLTemplates, {ERRORCODE: 403});
            }
          }
        }

        if (!player.vastTracker) {
          // No pre-roll, start video
          player.trigger('adtimeout');
        }
      });
    };

    player.vast.createSkipButton = function() {
      var skipButton = document.createElement("div");
      skipButton.className = "vast-skip-button";
      if (settings.skip < 0) {
        skipButton.style.display = "none";
      }
      player.vast.skipButton = skipButton;
      player.el().appendChild(skipButton);

      skipButton.onclick = function (e) {
        if ((' ' + player.vast.skipButton.className + ' ').indexOf(' enabled ') >= 0) {
          if (vpaidObj) {
            vpaidObj.skipAd();
            //will tear down after event AdSkipped is triggered
          } else {
            player.vastTracker.skip();
            player.vast.tearDown();
          }
        }
        if (Event.prototype.stopPropagation !== undefined) {
          e.stopPropagation();
        } else {
          return false;
        }
      };
    };

    player.vast.getClickThrough = function () {
      var clickthrough;
      if (player.vastTracker.clickThroughURLTemplate) {
        clickthrough = vast.util.resolveURLTemplates(
          [player.vastTracker.clickThroughURLTemplate],
          {
            CACHEBUSTER: Math.round(Math.random() * 1.0e+10),
            CONTENTPLAYHEAD: player.vastTracker.progressFormated()
          }
        )[0];
      }
      return clickthrough;
    };

    player.vast.preroll = function() {
      player.ads.startLinearAdMode();
      player.vast.showControls = player.controls();
      if (player.vast.showControls ) {
        player.controls(false);
      }
      player.autoplay(true);
      // play your linear ad content
      var adSources = player.vast.sources;
      player.src(adSources);

      var clickthrough = player.vast.getClickThrough();
      var blocker = document.createElement("a");
      blocker.className = "vast-blocker";
      blocker.href = clickthrough || "#";
      blocker.target = "_blank";
      blocker.onclick = function() {
        if (player.paused()) {
          player.play();
          return false;
        }
        var clicktrackers = player.vastTracker.clickTrackingURLTemplate;
        if (clicktrackers) {
          player.vastTracker.trackURLs([clicktrackers]);
        }
        player.trigger("adclick");
      };
      player.vast.blocker = blocker;
      player.el().insertBefore(blocker, player.controlBar.el());

      player.vast.createSkipButton();
      player.on("timeupdate", player.vast.timeupdate);
      player.one("ended", player.vast.tearDown);
    };

    player.vast.prerollVPAID = function() {
      player.ads.startLinearAdMode();
      player.vast.showControls = player.controls();
      if (player.vast.showControls ) {
        player.controls(false);
      }
      vpaidObj.startAd();
      vpaidTrackInterval = setInterval(player.vast.updateSeeker, 500);
      //player might be playing if video tags are different
      player.pause();
    };

    player.vast.tearDown = function() {
      if (player.vast.skipButton) {
        player.vast.skipButton.parentNode.removeChild(player.vast.skipButton);
        player.vast.skipButton = undefined;
      }
      if (player.vast.blocker) {
        player.vast.blocker.parentNode.removeChild(player.vast.blocker);
        player.vast.blocker = undefined;
      }
      player.off('timeupdate', player.vast.timeupdate);
      player.off('ended', player.vast.tearDown);
      if (player.vast.showControls ) {
        player.controls(true);
      }

      if (vpaidObj) {
        for (var event in vpaidListeners) {
          if (!vpaidListeners.hasOwnProperty(event)) {
            continue;
          }
          var listeners = vpaidListeners[event];
          for (var i = 0; i < listeners.length; i++) {
            vpaidObj.unsubscribe(listeners[i], event);
          }
        }
        if (vpaidIFrame) {
          vpaidIFrame.parentNode.removeChild(vpaidIFrame);
        }
        vpaidObj = null;
        vpaidIFrame = null;
        vpaidListeners = {};
        player.vast.removeVPAIDControls();
      }
      if (vpaidTrackInterval != -1) {
        clearInterval(vpaidTrackInterval);
        vpaidTrackInterval = -1;
      }
      if (vpaidPlayer) {
        vpaidPlayer.parentNode.removeChild(vpaidPlayer);
      }

      //complete in async manner. Sometimes when shutdown too soon, video does not start playback
      setTimeout(function() {
        player.ads.endLinearAdMode();
      }, 0);
    };

    player.vast.enableSkipButton = function () {
      if ((' ' + player.vast.skipButton.className + ' ').indexOf(' enabled ') === -1) {
        player.vast.skipButton.className += " enabled";
        player.vast.skipButton.innerHTML = localize("Skip");
      }
    };

    player.vast.timeupdate = function(e) {
      player.loadingSpinner.el().style.display = "none";
      var timeLeft = Math.ceil(settings.skip - player.currentTime());
      if(timeLeft > 0) {
        var translation = localize('Skip in %num%...');
        player.vast.skipButton.innerHTML = translation.replace('%num%', timeLeft);
      } else {
        player.vast.enableSkipButton();
      }
    };
    player.vast.createSourceObjects = function (media_files) {
      var sourcesByFormat = {}, i, j, tech;
      var techOrder = player.options().techOrder;
      for (i = 0, j = techOrder.length; i < j; i++) {
        var techName = techOrder[i].charAt(0).toUpperCase() + techOrder[i].slice(1);
        tech = window.videojs[techName];

        // Check if the current tech is defined before continuing
        if (!tech) {
          continue;
        }

        // Check if the browser supports this technology
        if (tech.isSupported()) {
          // Loop through each source object
          for (var a = 0, b = media_files.length; a < b; a++) {
            var media_file = media_files[a];
            var source = {type:media_file.mimeType, src:media_file.fileURL};
            // Check if source can be played with this technology
            if (tech.canPlaySource(source)) {
              if (sourcesByFormat[techOrder[i]] === undefined) {
                sourcesByFormat[techOrder[i]] = [];
              }
              sourcesByFormat[techOrder[i]].push({
                type:media_file.mimeType,
                src: media_file.fileURL,
                width: media_file.width,
                height: media_file.height
              });
            }
          }
        }
      }
      // Create sources in preferred format order
      var sources = [];
      for (j = 0; j < techOrder.length; j++) {
        tech = techOrder[j];
        if (sourcesByFormat[tech] !== undefined) {
          for (i = 0; i < sourcesByFormat[tech].length; i++) {
            sources.push(sourcesByFormat[tech][i]);
          }
        }
      }
      return sources;
    };

    //Find optimal available VPAID tech. Best match is javascript, otherwise last found will be returned
    player.vast.findOptimalVPAIDTech = function(mediaFiles) {
      var foundTech = null;
      for (var i = 0; i < mediaFiles.length; i++) {
        var mediaFile = mediaFiles[i];
        if (mediaFile.apiFramework != "VPAID") {
          continue;
        }

        if (mediaFile.mimeType == 'application/javascript') {
          //bingo!
          return mediaFile;
        } else {
          foundTech = mediaFile;
        }
      }

      return foundTech;
    };

    player.vast.loadVPAIDResource = function(mediaFile, callback) {
      if (mediaFile.mimeType != "application/javascript") {
        throw new Error("Loading not javascript vpaid ads is not supported");
      }

      vpaidIFrame = document.createElement('iframe');
      vpaidIFrame.style.display = 'none';
      vpaidIFrame.onload = function() {
        var iframeDoc = vpaidIFrame.contentDocument;
        //Credos http://stackoverflow.com/a/950146/51966
        // Adding the script tag to the head as suggested before
        var head = iframeDoc.getElementsByTagName('head')[0];
        var script = iframeDoc.createElement('script');
        script.type = 'text/javascript';
        script.src = mediaFile.fileURL;

        // Then bind the event to the callback function.
        // There are several events for cross browser compatibility.
        script.onreadystatechange = script.onload = function() {
          if (!this.readyState || this.readyState === "loaded" || this.readyState === "complete") {
            if (vpaidIFrame.contentWindow.getVPAIDAd === undefined) {
              console.log("Unable to load script or script do not have getVPAIDAd method");
              return;
            }

            callback(vpaidIFrame.contentWindow.getVPAIDAd());
          }
        };

        head.appendChild(script);
      };

      document.body.appendChild(vpaidIFrame);
    };

    player.vast.initSimpleVAST = function(ad) {
      var errorOccurred = false,
        canplayFn = function() {
          this.vastTracker.load();
        },
        timeupdateFn = function() {
          if (isNaN(this.vastTracker.assetDuration)) {
            this.vastTracker.assetDuration = this.duration();
          }
          this.vastTracker.setProgress(this.currentTime());
        },
        playFn = function() {
          this.vastTracker.setPaused(false);
        },
        pauseFn = function() {
          this.vastTracker.setPaused(true);
        },
        errorFn = function() {
          // Inform ad server we couldn't play the media file for this ad
          vast.util.track(ad.errorURLTemplates, {ERRORCODE: 405});
          errorOccurred = true;
          player.trigger('ended');
        };

      player.on('canplay', canplayFn);
      player.on('timeupdate', timeupdateFn);
      player.on('play', playFn);
      player.on('pause', pauseFn);
      player.on('error', errorFn);

      player.one('ended', function() {
        player.off('canplay', canplayFn);
        player.off('timeupdate', timeupdateFn);
        player.off('play', playFn);
        player.off('pause', pauseFn);
        player.off('error', errorFn);
        if (!errorOccurred) {
          this.vastTracker.complete();
        }
      });
    };

    player.vast.initVPAID = function(vpaidTech, cb) {
      player.vast.loadVPAIDResource(vpaidTech, function(vpaid) {
        vpaidObj = vpaid;
        if (vpaid.handshakeVersion('2.0') != '2.0') {
          throw new Error("Versions different to 2.0 are not supported");
        }

        var root = player.el();
        var pref = {
          videoSlotCanAutoPlay: true,
          slot: root
        };
        if (/iphone|ipad|android/gi.test(navigator.userAgent)) {
          pref.videoSlot = player.el().querySelector('.vjs-tech');
          if (pref.videoSlot.tagName != 'video') { //might be using non-default source, fallback to custom video slot
            pref.videoSlot = undefined;
          }
        }

        if (!pref.videoSlot) {
          vpaidPlayer = document.createElement('video');

          vpaidPlayer.className = 'vast-blocker';
          root.appendChild(vpaidPlayer);
          pref.videoSlot = vpaidPlayer;
        }

        player.on('resize', function() {
          vpaid.resizeAd(player.width(), player.height(), settings.viewMode);
        });
        player.on('fullscreenchange', function() {
          if (player.isFullScreen()) {
            vpaid.resizeAd(0, 0, 'fullscreen');
          } else {
            vpaid.resizeAd(player.width, player.width, settings.viewMode);
          }
        });

        function setTrackerDuration() {
          if (vpaidObj.getAdDuration) {
            var duration = vpaidObj.getAdDuration();
            if (duration > 0) {
              player.vastTracker.setDuration(duration);
            }
          }
        }

        player.vast.onVPAID('AdError', function() {
          player.vast.tearDown();
        });
        player.vast.oneVPAID('AdLoaded', function() {
          if (cb) {
            cb(vpaid);
          }

          setTrackerDuration();
        });
        player.vast.oneVPAID('AdStopped', function() {
          player.vast.tearDown();
        });

        player.vast.onVPAID('AdDurationChange', function() {
          setTrackerDuration();
        });
        player.vast.onVPAID('AdRemainingTimeChange', function() {
          setTrackerDuration();
        });
        player.vast.oneVPAID('AdSkipped', function() {
          player.vastTracker.skip();
          player.vast.tearDown();
        });
        player.vast.oneVPAID('AdStarted', function() {
          player.ads.startLinearAdMode();
          player.vastTracker.load();
        });
        player.vast.onVPAID('AdVolumeChange', function() {
          player.vastTracker.setMuted(vpaidObj.getAdVolume() === 0);
          player.setVolume(vpaidObj.getAdVolume());
        });
        player.vast.onVPAID('AdImpression', function() {
          //TODO
        });
        player.vast.onVPAID('AdVideoStart', function() {
          player.vastTracker.setProgress(0);
        });
        player.vast.onVPAID('AdVideoFirstQuartile', function() {
          var emulatedFirstQuartile = Math.round(25 * vpaidObj.getAdDuration()) / 100;
          player.vastTracker.setProgress(emulatedFirstQuartile);
        });
        player.vast.onVPAID('AdVideoMidpoint', function() {
          var emulatedMidpoint = Math.round(50 * vpaidObj.getAdDuration()) / 100;
          player.vastTracker.setProgress(emulatedMidpoint);
        });
        player.vast.onVPAID('AdVideoThirdQuartile', function() {
          var emulatedThirdQuartile = Math.round(75 * vpaidObj.getAdDuration()) / 100;
          player.vastTracker.setProgress(emulatedThirdQuartile);
        });
        player.vast.onVPAID('AdVideoComplete', function() {
          player.vastTracker.setProgress(vpaidObj.getAdDuration());
        });
        player.vast.onVPAID('AdClickThru', function(url, id, playerHandles) {
          player.vastTracker.click();
          if (playerHandles) {
            if (!url) {
              url = player.vast.getClickThrough();
            }

            //TODO open url
          }
        });
        player.vast.onVPAID('AdUserAcceptInvitation', function() {
          //TODO implement in vast client
          player.vastTracker.acceptInvitation();
        });
        player.vast.onVPAID('AdUserClose', function() {
          player.vastTracker.close();
        });
        player.vast.onVPAID('AdPaused', function() {
          player.vastTracker.setPaused(true);
        });
        player.vast.onVPAID('AdPlaying', function() {
          player.vastTracker.setPaused(false);
        });
        player.vast.onVPAID('AdSkippableStateChange', function() {
          if (vpaidObj.getAdSkippableState()) {
            player.vast.createSkipButton();
            player.vast.enableSkipButton();
          } else if (player.vast.skipButton) {
            player.vast.skipButton.parentNode.removeChild(player.vast.skipButton);
          }
        });
        //TODO add creativeData
        vpaid.initAd(player.width(), player.height(), settings.viewMode, settings.bitrate, {}, pref);
      });
    };

    player.vast.createVPAIDControls = function() {
      vpaidSeeker = document.createElement('div');
      vpaidSeeker.className = 'vast-ad-control';
      vpaidSeeker.innerHTML = '<span class="vast-advertisement">' + localize('Advertisement') + ' <span class="vast-ad-left"></span></span><div class="vast-progress-holder"><div class="vjs-play-progress"></div></div>';
      player.el().appendChild(vpaidSeeker, player.el().childNodes[0]);
    };

    player.vast.removeVPAIDControls = function() {
      if (vpaidSeeker) {
        vpaidSeeker.parentNode.removeChild(vpaidSeeker);
      }
    };

    player.vast.updateSeeker = function() {
      if (!vpaidObj && vpaidTrackInterval != -1) { //might be it was shutdown earlier than first seek could appear. Silently remove itself
        clearInterval(vpaidTrackInterval);
        vpaidTrackInterval = -1;
        return;
      }
      var remaining = vpaidObj.getAdRemainingTime();
      if (remaining < 0) {
        return;
      }
      var total = vpaidObj.getAdDuration();
      if (total < 0) {
        return;
      }
      var progress = vpaidSeeker.querySelector('.vjs-play-progress');
      progress.style.width = ((total - remaining) / total * 100) + '%';

      //taken from videojs-iva
      var remainingMinutes = Math.floor(remaining / 60);
      var remainingSeconds = Math.floor(remaining % 60);
      if (remainingSeconds.toString().length < 2) {
        remainingSeconds = '0' + remainingSeconds;
      }
      var remains = remainingMinutes + ':' + remainingSeconds;
      progress.innerHTML = '<span class="vjs-control-text">' + remains + '</span>';
      vpaidSeeker.querySelector('.vast-ad-left').innerHTML = remains;
    };

    player.vast.onVPAID = function(event, func) {
      if (vpaidListeners[event] === undefined) {
        vpaidListeners[event] = [];
      }
      vpaidListeners[event].push(func);
      vpaidObj.subscribe(func, event);
    };

    player.vast.offVPAID = function(event, func) {
      vpaidObj.unsubscribe(func, event);
      if (vpaidListeners[event]) {
        var listeners = vpaidListeners[event],
          index = -1;
        if (!Array.prototype.indexOf) {
          for (var i = 0; i < listeners.length; i++) {
            if (listeners[i] == func) {
              index = i;
              break;
            }
          }
        } else {
          index = listeners.indexOf(func);
        }

        if (index != -1) {
          listeners.splice(index, 1);
        }
        if (listeners.length === 0) {
          delete vpaidListeners[event];
        }
      }
    };

    player.vast.oneVPAID = function(event, func) {
      var wrapper = function() {
        player.vast.offVPAID(event, wrapper);
        func();
      };
      player.vast.onVPAID(event, wrapper);
    };

    // make an ads request immediately so we're ready when the viewer
    // hits "play"
    if (player.currentSrc()) {
      player.vast.getContent(settings.url);
    }
  };

  vjs.plugin('vast', vastPlugin);
}(window.videojs, window.DMVAST));
