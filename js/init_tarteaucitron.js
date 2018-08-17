tarteaucitron.init({
  "hashtag": Backdrop.settings.gdpr_cookies.hashtag, /* Automatically open the panel with the hashtag */
  "highPrivacy": Boolean(Backdrop.settings.gdpr_cookies.highPrivacy), /* disabling the auto consent feature on navigation? */
  "orientation": Backdrop.settings.gdpr_cookies.orientation, /* the big banner should be on 'top' or 'bottom'? */
  "adblocker": Boolean(Backdrop.settings.gdpr_cookies.adblocker), /* Display a message if an adblocker is detected */
  "showAlertSmall": Boolean(Backdrop.settings.gdpr_cookies.showAlertSmall), /* show the small banner on bottom right? */
  "cookieslist": Boolean(Backdrop.settings.gdpr_cookies.cookieslist), /* Display the list of cookies installed ? */
  "removeCredit": Boolean(Backdrop.settings.gdpr_cookies.removeCredit), /* remove the credit link? */
  "defaultRejected": Boolean(Backdrop.settings.gdpr_cookies.defaultRejected) /* Should the services be rejected by default? */
});
