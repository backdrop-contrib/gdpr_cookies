// GPSies.com
tarteaucitron.services.gpsies = {
  "key": "gpsies",
  "type": "other",
  "name": "GPSies.com",
  "uri": "http://www.gpsies.com/page.do?page=privacy",
  "needConsent": true,
  "cookies": [],
  "js": function () {
    "use strict";
    tarteaucitron.fallback(['tac_iframe'], function (x) {
      var width = x.getAttribute("width"),
          height = x.getAttribute("height"),
          url = x.getAttribute("data-url");

      return '<iframe src="' + url + '" width="' + width + '" height="' + height + '" frameborder="0" scrolling="no" allowtransparency allowfullscreen></iframe>';
    });
  },
  "fallback": function () {
    "use strict";
    var id = 'iframe';
    tarteaucitron.fallback(['tac_iframe'], function (elem) {
      elem.style.width = elem.getAttribute('width') + 'px';
      elem.style.height = elem.getAttribute('height') + 'px';
      return tarteaucitron.engage(id);
    });
  }
};

// matomo
tarteaucitron.services.matomo = {
  "key": "matomo",
  "type": "analytic",
  "name": "Matomo (formerly known as Piwik)",
  "uri": "https://matomo.org/faq/general/faq_146/",
  "needConsent": true,
  "cookies": ['_pk_ref', '_pk_cvar', '_pk_id', '_pk_ses', '_pk_hsr', 'piwik_ignore', '_pk_uid'],
  "js": function () {
    "use strict";
    if (tarteaucitron.user.matomoId === undefined) {
      return;
    }

    window._paq = window._paq || [];
    window._paq.push(["setSiteId", tarteaucitron.user.matomoId]);
    window._paq.push(["setTrackerUrl", tarteaucitron.user.matomoHost + "piwik.js"]);

    if (typeof tarteaucitron.user.matomoParameters === 'function') {
      tarteaucitron.user.matomoParameters();
    }

    tarteaucitron.addScript(tarteaucitron.user.matomoHost + 'piwik.js', '', '', true, 'defer', 'defer');
  }
};

// hotjar
tarteaucitron.services.hotjar = {
  "key": "hotjar",
  "type": "analytic",
  "name": "Hotjar",
  "uri": "https://www.hotjar.com/",
  "needConsent": true,
  "cookies": [' _hjIncludedInSample'],
  "js": function () {
    "use strict";
    (function(h,o,t,j,a,r){
      h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};
      h._hjSettings={hjid:tarteaucitron.user.hotjarId,hjsv:tarteaucitron.user.hotjarsv};
      a=o.getElementsByTagName('head')[0];
      r=o.createElement('script');r.async=1;
      r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;
      a.appendChild(r);
    })(window,document,'//static.hotjar.com/c/hotjar-','.js?sv=');
  }
};

tarteaucitron.services.doubleclick = {
  "key": "doubleclick",
  "type": "ads",
  "name": "DoubleClick",
  "needConsent": true,
  "uri": "https://policies.google.com/privacy?hl=en",
  "cookies": [''],
  "js": function() {
    if (tarteaucitron.user.doubleClickId === undefined) {
      return;
    }

    var axel = Math.random() + "";
    var a = axel * 10000000000000;

    var iframe = document.createRange().createContextualFragment('<iframe src="https://' + tarteaucitron.user.doubleClickId + '.fls.doubleclick.net/activityi;src=' + tarteaucitron.user.doubleClickId + ';type=retar0;cat=homep0;dc_lat=;dc_rdid=;tag_for_child_directed_treatment=;ord=' + a + '?" width="1" height="1" frameborder="0" style="display:none">');
    document.body.appendChild(iframe);
  }
};
