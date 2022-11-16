/**
 * Welcome to your Workbox-powered service worker!
 *
 * You'll need to register this file in your web app and you should
 * disable HTTP caching for this file too.
 * See https://goo.gl/nhQhGp
 *
 * The rest of the code is auto-generated. Please don't update this file
 * directly; instead, make changes to your Workbox build configuration
 * and re-run your build process.
 * See https://goo.gl/2aRDsh
 */

importScripts("https://storage.googleapis.com/workbox-cdn/releases/4.3.1/workbox-sw.js");

self.addEventListener('message', (event) => {
  if (event.data && event.data.type === 'SKIP_WAITING') {
    self.skipWaiting();
  }
});

/**
 * The workboxSW.precacheAndRoute() method efficiently caches and responds to
 * requests for URLs in the manifest.
 * See https://goo.gl/S9QRab
 */
self.__precacheManifest = [
  {
    "url": "404.html",
    "revision": "f887c7258cafa7d033ca4586fbe423df"
  },
  {
    "url": "assets/css/0.styles.3a701227.css",
    "revision": "e0ba767d1cc5d7a68289b790f4102d3b"
  },
  {
    "url": "assets/img/copied.26408bed.svg",
    "revision": "26408bed185146a74d6fb7d71b4207e9"
  },
  {
    "url": "assets/img/copy.e3634ccf.svg",
    "revision": "e3634ccf2a60445e59d5f255481010fd"
  },
  {
    "url": "assets/js/10.5cd2f584.js",
    "revision": "c5c39c1cfd2271e6155c07d6f6e5caab"
  },
  {
    "url": "assets/js/11.5e177460.js",
    "revision": "e4761d07e17c66e27644decc9007fbd7"
  },
  {
    "url": "assets/js/12.2f9c2f74.js",
    "revision": "e04f8bafd49e274b6904faa92aaaafa2"
  },
  {
    "url": "assets/js/13.e5a300be.js",
    "revision": "ace9c3dda50bb5615ba76f86650832c5"
  },
  {
    "url": "assets/js/14.7ae1f655.js",
    "revision": "b8f180248c0884d8d4dfc3d36db44527"
  },
  {
    "url": "assets/js/15.8131be53.js",
    "revision": "f96032976d5577a4bcb56c51b0434519"
  },
  {
    "url": "assets/js/16.0d798cb1.js",
    "revision": "83e9ec0412c97cd3aa9ea62bb4f040f4"
  },
  {
    "url": "assets/js/17.94a9cbc9.js",
    "revision": "afcdc3942b88e9084b17f6ebadde8b9c"
  },
  {
    "url": "assets/js/18.9ed0fff7.js",
    "revision": "0fa5cfbe5435cab116608ece7229651a"
  },
  {
    "url": "assets/js/19.3641a284.js",
    "revision": "c215d513554e9e32cf9bf6ac3fa2f874"
  },
  {
    "url": "assets/js/2.79923db3.js",
    "revision": "9530ee1dcf801e928c7bf77d175a1040"
  },
  {
    "url": "assets/js/20.261180c8.js",
    "revision": "ead05e4dfbef4faa596988e20de8f514"
  },
  {
    "url": "assets/js/21.66b92e35.js",
    "revision": "417364799a6ca2c2e05cfac9f54b8085"
  },
  {
    "url": "assets/js/3.e240be30.js",
    "revision": "21677a90d5a11fabd591a60891cc3c4a"
  },
  {
    "url": "assets/js/4.577eab19.js",
    "revision": "6bb225b4f49513c4d3492cc1439a8cfb"
  },
  {
    "url": "assets/js/5.c1d8b2b9.js",
    "revision": "e6c8013e2a276c8d3e3e7e5c5705ba41"
  },
  {
    "url": "assets/js/6.1d138eb0.js",
    "revision": "9b2d3560eea1cd662a2a1c62e1d8304b"
  },
  {
    "url": "assets/js/7.a9ccbf84.js",
    "revision": "1d3a63a973557da9ddf30a94fee03fe4"
  },
  {
    "url": "assets/js/8.5365e911.js",
    "revision": "13751d1be054aa012429334f572f01bb"
  },
  {
    "url": "assets/js/9.34310160.js",
    "revision": "aa48b6f8e2f433ef8b9b4005429f5ee8"
  },
  {
    "url": "assets/js/app.ca3c0e5d.js",
    "revision": "f3c887a2cbd92a27c629bd3ebb0d2c16"
  },
  {
    "url": "guide/defining-states.html",
    "revision": "b1d95eeab5ed2fabd22a6cbdb782959a"
  },
  {
    "url": "guide/hooks.html",
    "revision": "c31e144fb91e1f31f98bdcc8644a269e"
  },
  {
    "url": "guide/multicast.html",
    "revision": "121abef37bd0380f2732b609adc6e16a"
  },
  {
    "url": "guide/transitions.html",
    "revision": "6f53aba6dab2b9bd170002c7529f87b6"
  },
  {
    "url": "index.html",
    "revision": "467991ca99446bbbfdd39d8b938a8a3b"
  },
  {
    "url": "installation.html",
    "revision": "47aab343c518b5cfa17c42ee4e684c24"
  },
  {
    "url": "introduction.html",
    "revision": "51f175c3e5bc6258628441847d22fa68"
  }
].concat(self.__precacheManifest || []);
workbox.precaching.precacheAndRoute(self.__precacheManifest, {});
addEventListener('message', event => {
  const replyPort = event.ports[0]
  const message = event.data
  if (replyPort && message && message.type === 'skip-waiting') {
    event.waitUntil(
      self.skipWaiting().then(
        () => replyPort.postMessage({ error: null }),
        error => replyPort.postMessage({ error })
      )
    )
  }
})
