# GDPR Cookies

This module aims to help site administrators follow the guidelines of 
the "General Data Protection Regulation" (GDPR) regarding user 
tracking and integration of third party content.

GDPR Cookies lets you prevent scripts and embedded content 
(e.g. videos) from being rendered until the user has given their
consent to do so.

Please note that installing and using this module does not mean your 
website becomes GDPR compliant.

NB This is a renamed version of a port of the Drupal module 
`blizz_vanisher`. It is recommended that users install this
newer version which will be updated and improved as issues are
reported.

## Details
The new European "General Data Protection Regulation" (GDPR) decrees 
that the well-known yet simple "This website uses cookies" banner is 
no longer sufficient and aims to provide for more transparency on the 
use of the website visitor's data.

As part of the new regulation websites are not allowed to set 
ANY cookie without explicit consent of the visitor. What first deems 
to be not that great an issue becomes a real problem when it comes to 
the integration of third party content - because website operators are 
also responsible for potential data usage by third parties.

## Does your website use
+ Google Analytics?
+ Youtube videos?
+ Vimeo videos?
+ Google Webfonts?
+ Twitter plugins?
+ Facebook plugins?
+ Or any other content integrated via CDNs?

If using these or similar services you now have to get explicit 
permission from the site visitor to include content from these 
services into your website - BEFORE any display occurs!

GDPR Cookies integrates the cookie manager script `tarteaucitron.js`,
which elegantly provides customization features to the website's 
end user and does all the heavy lifting for you. Simply install 
the module, configure the services needed and you're done.

When configured, GDPR Cookies in conjunction with `tarteaucitron.js` 
prevents external services from being integrated into your website 
without proper consent.

This initial port to Backdrop is from Drupal release 7.x-1.3.

## Dependencies
+ [Entity Plus module](https://github.com/backdrop-contrib/entity_plus)  
+ The `tarteaucitron.js` script library (v1.8.4) is included in the module

## Installation
- Install this module using [the official Backdrop CMS instructions](https://backdropcms.org/guide/modules).
- Use the configuration page at **admin/config/system/gdpr_cookies/settings**
 to control which services are provided and need user consent.
- There are some challenges to initialising this module which adjusts 
according to the current cookies on the site. If experiencing difficulty,
please clear cookies in your browser and set GDPR Cookies to detect 
at least one of the inbuilt third party services.
- The included `tarteaucitron.js` library has a number of language files
which provide in editable form the text of the on-screen messages.
 
## License
 This project is GPL v2 software. See the LICENSE.txt 
 file in this directory for complete text.
 
 The `tarteaucitron.js` script is released under the MIT license.

## References and Credits
+ [tarteaucitron.js](https://github.com/AmauriC/tarteaucitron.js)
+ '[The GDPR is here](https://www.youtube.com/watch?v=CyIFNsSHPxQ)' (video)

### Port to Backdrop

+ Graham Oliver (github.com/Graham-72)

### Maintainers of Blizz Vanisher for Drupal:

+ Lars Rosenberg (rackberg)
+ Christian Lamine (CHiLi.HH)
+ marvin_B8

### Acknowledgement

This port to Backdrop would not, of course, be possible without all
the work done by the developers and maintainers of the Drupal module.
