# GDPR Cookies

GDPR Cookies aims to help site administrators follow the requirements of the
"General Data Protection Regulation" (GDPR) regarding user tracking and
integration of third party content.

GDPR Cookies lets you prevent scripts and embedded content (e.g. videos) from
being rendered until the user has given their consent to do so.

Please note that installing and using this module does not mean your
website becomes GDPR compliant.

### More Details
The European "General Data Protection Regulation" (GDPR) decreed that the
well-known yet simple "This website uses cookies" banner was no longer
sufficient and aimed to provide for more transparency on the use of the website
visitor's data.

As part of the regulation websites are not allowed to set ANY cookie without
explicit consent of the visitor. What first deems to be not that great an issue
becomes a real problem when it comes to the integration of third party content
because website operators are also responsible for potential data usage by
third parties.

### Does your website use
+ Google Analytics?
+ Youtube videos?
+ Vimeo videos?
+ Google Webfonts?
+ Twitter plugins?
+ Facebook plugins?
+ Or any other content integrated via CDNs?

If using these or similar services you now have to get explicit permission from
the site visitor to include content from these services into your website -
BEFORE any display occurs!

GDPR Cookies integrates the cookie manager script
[Tarte au Citron](https://tarteaucitron.io/), which elegantly provides
customization features to the website's end user and does all the heavy lifting
for you. Simply install the module, configure the services needed and you're
done.

When configured, GDPR Cookies, in conjunction with Tarte au Citron, prevents
external services from being integrated into your website without proper
consent.

## Requirements <!-- Do not include this section if there are no requirements. -->

This module requires that the following modules are also enabled:

 * [Entity Plus](https://github.com/backdrop-contrib/entity_plus)

The [Tarte au Citron](https://tarteaucitron.io/) library is bundled into this module.

## Installation <!-- This section is required. -->

- Install this module using the official Backdrop CMS instructions at
  https://docs.backdropcms.org/documentation/extend-with-modules.

- Visit the configuration page at Administration > Configuration > System >
  GDPR Cookies > Settings (admin/config/system/gdpr_cookies/settings)
  to control which services are provided and need user consent.

There are some challenges to initialising this module which adjusts according
to the current cookies on the site. If experiencing difficulty, please clear
cookies in your browser and set GDPR Cookies to detect at least one of the
inbuilt third party services.

The included [`tarteaucitron.js`](https://github.com/AmauriC/tarteaucitron.js) library has a number of language files which
provide in editable form the text of the on-screen messages.

## Issues <!-- This section is required. -->
Bugs and feature requests should be reported in [the Issue Queue](https://github.com/backdrop-contrib/gdpr_cookies/issues).

## Current Maintainers <!-- This section is required. -->

- [Martin Price](https://github.com/yorkshire-pudding) - [System Horizons Ltd](https://www.systemhorizons.co.uk)
- Collaboration and co-maintainers welcome!

## Credits <!-- This section is required. -->
- Current development supported by [System Horizons Ltd](https://www.systemhorizons.co.uk)
- Ported to Backdrop CMS by [Graham Oliver](https://github.com/Graham-72).
- Maintainers of Blizz Vanisher for Drupal:
  - [Lars Rosenberg](https://www.drupal.org/u/rackberg)
  - [Christian Lamine](https://www.drupal.org/u/chilihh)
  - [marvin_B8](https://www.drupal.org/u/marvin_b8)
- [Tarte au Citron - a GDPR friendly cookie consent manager library](https://tarteaucitron.io/)

## License <!-- This section is required. -->

This project is GPL v2 software.
See the LICENSE.txt file in this directory for complete text.

The [`tarteaucitron.js` library](https://github.com/AmauriC/tarteaucitron.js)
is released under the [MIT license](https://github.com/AmauriC/tarteaucitron.js?tab=MIT-1-ov-file#readme).
