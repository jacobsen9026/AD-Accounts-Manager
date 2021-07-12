<?php


namespace App\Controllers;


use App\Models\Database\AppDatabase;
use App\Models\Database\AuthDatabase;
use App\Models\View\Javascript;
use System\File;
use system\Header;

class Js extends FileController
{
    public function sw_js()
    {

        $serviceWorker = "
        
        /*
 Copyright 2016 Google Inc. All Rights Reserved.
 Licensed under the Apache License, Version 2.0 (the 'License');
 you may not use this file except in compliance with the License.
 You may obtain a copy of the License at
 http://www.apache.org/licenses/LICENSE-2.0
 Unless required by applicable law or agreed to in writing, software
 distributed under the License is distributed on an 'AS IS' BASIS,
 WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 See the License for the specific language governing permissions and
 limitations under the License.
 */

// Names of the two caches used in this version of the service worker.
// Change to v2, etc. when you update any of the local resources, which will
// in turn trigger the install event again.
const PRECACHE = 'precache-v1';
const RUNTIME = 'runtime';

// A list of local resources we always want to be cached.
const PRECACHE_URLS = [
  //'./', // Alias for index.html
  '/css/style2.css',
  '/css/loader.scss',
  '/css/loaders.css',
  '/css/redTheme.css',
  '/css/orangeTheme.css',
  '/css/slateTheme.css',
  '/css/mobilestyle.css'
];

// The install handler takes care of precaching the resources we always need.
self.addEventListener('install', event => {
  event.waitUntil(
    caches.open(PRECACHE)
      .then(cache => cache.addAll(PRECACHE_URLS))
      .then(self.skipWaiting())
      
  );
});

// The activate handler takes care of cleaning up old caches.
self.addEventListener('activate', event => {
console.log('Done precaching');
  const currentCaches = [PRECACHE, RUNTIME];
  event.waitUntil(
    caches.keys().then(cacheNames => {
      return cacheNames.filter(cacheName => !currentCaches.includes(cacheName));
    }).then(cachesToDelete => {
      return Promise.all(cachesToDelete.map(cacheToDelete => {
        return caches.delete(cacheToDelete);
      }));
    }).then(() => self.clients.claim())
  );
});

// The fetch handler serves responses for same-origin resources from a cache.
// If no response is found, it populates the runtime cache with the response
// from the network before returning it to the page.
self.addEventListener('fetch', event => {

  // Skip cross-origin requests, like those for Google Analytics.
  if (event.request.url.startsWith(self.location.origin)) {
  /**
    event.respondWith(
      caches.match(event.request).then(cachedResponse => {
        if (cachedResponse) {
          return cachedResponse;
        }

        return caches.open(RUNTIME).then(cache => {
          return fetch(event.request).then(response => {
            // Put a copy of the response in the runtime cache.
            return cache.put(event.request, response.clone()).then(() => {
              return response;
            });
          });
        });
      })
    );
    **/
  }
});
        ";
        Header::sendFile($serviceWorker, Header::APPLICATION_JAVASCRIPT);
        exit;
    }

    public function jquery_redirect_js()
    {
        Header::sendFile(File::getContents(ROOTPATH . DIRECTORY_SEPARATOR . 'system' . DIRECTORY_SEPARATOR . 'js' . DIRECTORY_SEPARATOR . 'jquery.redirect.js'), Header::APPLICATION_JAVASCRIPT);
    }

    public function jqueryUi_js()
    {
        Header::sendFile(File::getContents(ROOTPATH . DIRECTORY_SEPARATOR . 'system' . DIRECTORY_SEPARATOR . 'js' . DIRECTORY_SEPARATOR . 'jquery-ui.js'), Header::APPLICATION_JAVASCRIPT);
    }

    public function sessionManager_js()
    {
        $script = ' 
        if(typeof channel === "undefined"){
        console.log("Create channel");
            channel = new BroadcastChannel("' . AppDatabase::getAppAbbreviation() . '-ADAMWebsite");
            console.log(channel);
        }
        channel.postMessage("session_refreshed");

        channel.addEventListener("message", (event) => {
            switch (event.data) {
                case "session_expiring":
                    console.log("Session expiring in another tab");
                    $("#sessionExpirationModal").modal("show");
                    clearTimeout(finalMinuteTimer)
                    finalMinuteTimer = setTimeout(expireSession, 70 * 1000);
                    break;
                case "session_refreshed":
                    console.log("Session refreshed from other tab");
                    $("#sessionExpirationModal").modal("hide");
                    startSessionTimer()
                    break;
                case "session_expired":
                    console.log("Session expired in another tab");
                    window.location.href = window.location.pathname;
                    break;

            }
        });


        function startSessionTimer() {
            clearTimeout(sessionTimer);
            clearTimeout(finalMinuteTimer);
            ' . Javascript::debug("Starting session expiration timer: " . (AuthDatabase::getSessionTimeout() - 60)) . '
            sessionTimer = setTimeout(sessionExpiring, ' . (AuthDatabase::getSessionTimeout() - 60) * 1000 . ')
        }

        function sessionExpiring() {
            channel.postMessage("session_expiring");
            $("#sessionExpirationModal").modal("show");
            clearTimeout(finalMinuteTimer)
            finalMinuteTimer = setTimeout(expireSession, 70 * 1000);
        }


        function resetSession() {

            $("#sessionExpirationModal").modal("hide");
            channel.postMessage("session_refreshed");
            ' . Javascript::debug("Session refreshed") . '
            startSessionTimer();
        }

        function expireSession() {
            channel.postMessage("session_expired");
            window.location.href = window.location.pathname;
        }
        ';
        Header::sendFile($script, Header::APPLICATION_JAVASCRIPT);
    }

    public function touchPunch_js()
    {
        Header::sendFile('
        //TouchPunch
        !function (a) {
            function f(a, b) {
                if (!(a.originalEvent.touches.length > 1)) {
                    a.preventDefault();
                    var c = a.originalEvent.changedTouches[0], d = document.createEvent("MouseEvents");
                    d.initMouseEvent(b, !0, !0, window, 1, c.screenX, c.screenY, c.clientX, c.clientY, !1, !1, !1, !1, 0, null),
                        a.target.dispatchEvent(d)
                }
            }

            if (a.support.touch = "ontouchend" in document, a.support.touch) {
                var e, b = a.ui.mouse.prototype, c = b._mouseInit, d = b._mouseDestroy;
                b._touchStart = function (a) {
                    var b = this;
                    !e && b._mouseCapture(a.originalEvent.changedTouches[0]) && (e = !0, b._touchMoved = !1, f(a, "mouseover"), f(a,
                        "mousemove"), f(a, "mousedown"))
                }, b._touchMove = function (a) {
                    e && (this._touchMoved = !0, f(a, "mousemove"))
                }, b._touchEnd = function (a) {
                    e && (f(a, "mouseup"), f(a, "mouseout"), this._touchMoved || f(a, "click"), e = !1)
                }, b._mouseInit = function () {
                    var b = this;
                    b.element.bind({
                        touchstart: a.proxy(b, "_touchStart"),
                        touchmove: a.proxy(b, "_touchMove"),
                        touchend: a.proxy(b, "_touchEnd")
                    }), c.call(b)
                }, b._mouseDestroy = function () {
                    var b = this;
                    b.element.unbind({
                        touchstart: a.proxy(b, "_touchStart"),
                        touchmove: a.proxy(b, "_touchMove"),
                        touchend: a.proxy(b, "_touchEnd")
                    }), d.call(b)
                }
            }
        }(jQuery);
        //End TouchPunch

', Header::APPLICATION_JAVASCRIPT);
    }
}