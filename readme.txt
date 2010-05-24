=== Secure WordPress ===
Contributors: Bueltge
Donate link: http://bueltge.de/wunschliste/
Tags: secure, notice, hack, hacked, protection, version, security
Requires at least: 2.6
Tested up to: 3.0-beta2
Stable tag: 0.1

Secure your WordPress Installation with small functions.

== Description ==
Little help to secure your WordPress installation: Remove Error information on login page; adds index.html to plugin directory; removes the wp-version, except in admin area.

1. removes error-information on login-page
1. adds index.php plugin-directory (virtual)
1. removes the wp-version, except in admin-area
1. removes Really Simple Discovery
1. removes Windows Live Writer
1. remove core update information for non-admins
1. remove plugin-update information for non-admins
1. remove theme-update informationfor non-admins (only WP 2.8 and higher)
1. hide wp-version in backend-dashboard for non-admins
1. Add string for use [WP Scanner](http://blogsecurity.net/wpscan "WP Scanner")
1. Block bad queries
1. Validate your site with a free malware and vulnerabilities scan with [SiteSecurityMonitor.com](http://www.sitesecuritymonitor.com/)


= Localizations =
Italien translation by [Gianni Diurno](http://gidibao.net/ "Gianni Diurno"), polish translation by Michal Maciejewski, belorussian file by [Fat Cow](http://www.fatcow.com/ "Fat Cow"), ukrainian translation by [AzzePis](http://wordpress.co.ua/plugins/ "AzzePis"), russian language by [Dmitriy Donchenko](http://blogproblog.com/), hungarian language files by [K&ouml;rmendi P&eacute;ter](http://www.seo-hungary.com/ "K&ouml;rmendi P&eacute;ter"), danish language files by [GeorgWP](http://wordpress.blogos.dk/s%C3%B8g-efter-downloads/?did=175 "S&oslash;g efter downloads")m spanish language files by [Pablo Jim&eacute;nez](http://www.ministeriosccc.org" www.ministeriosccc.org"), chinese language (zh_CN) by [tanghaiwei](http://dd54.net), french translation files by [Jez007](http://forum.gmstemple.com/ "forum.gmstemple.com"), japanese translation by [Fumito Mizuno](http://ounziw.com/ "Standing on the Shoulder of Linus"), dutch translation by [Rene](http://wpwebshop.com "wpwebshop.com") and arabic language files by [رس](http://www.r-sn.com/wp). Thanks a lot.

= Interested in WordPress tips and tricks =
You may also be interested in WordPress tips and tricks at [WP Engineer](http://wpengineer.com/) or for german people [bueltge.de](http://bueltge.de/) 


== Installation ==
1. Unpack the download-package
1. Upload the file to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Configure the options
1. Ready

See on [the official website](http://bueltge.de/wordpress-login-sicherheit-plugin/652/ "Secure WordPress").


== Screenshots ==
1. options-area (WordPress 2.9-rare)


== Other Notes ==
= Licence =
Good news, this plugin is free for everyone! Since it's released under the GPL, you can use it free of charge on your personal or commercial blog. But if you enjoy this plugin, you can thank me and leave a [small donation](http://bueltge.de/wunschliste/ "Wishliste and Donate") for the time I've spent writing and supporting this plugin. And I really don't want to know how many hours of my life this plugin has already eaten ;)

= Translations =
The plugin comes with various translations, please refer to the [WordPress Codex](http://codex.wordpress.org/Installing_WordPress_in_Your_Language "Installing WordPress in Your Language") for more information about activating the translation. If you want to help to translate the plugin to your language, please have a look at the .pot file which contains all defintions and may be used with a [gettext](http://www.gnu.org/software/gettext/) editor like [Poedit](http://www.poedit.net/) or the very fine plugin [CodeStyling Localization](http://www.code-styling.de/english/development/wordpress-plugin-codestyling-localization-en "Codestyling Localization") for WordPresss.


== Changelog ==
= v0.8.5 (05/16/2010) =
* small code changes for WP coding standards
* add free malware and vulnerabilities scan for test this; the scan has most interested informations and scan all of the server

= v0.8.4 (05/05/2010) =
* add methode for use the plugin also on ssl-installs
* change uninstall method

= v0.8.3 (04/14/2010) =
* bugfix fox secure block bad queries on string for case-insensitive

= v0.8.2 (03/21/2010) =
* fix syntax error on ask for rights to block bad queries
* add french language files

= v0.8.1 (03/08/2010) =
* remove versions-informations on backend with javascript
* small changes

= v0.8 (03/04/2010) =
* Protect WordPress against malicious URL requests, use the idea and script from Jeff Star, [see post](http://perishablepress.com/press/2009/12/22/protect-wordpress-against-malicious-url-requests/ "Protect WordPress Against Malicious URL Requests")

= v0.7 (03/01/2010) =
* add updates for WP 3.0

= v0.6 (01/11/2010) =
* fix for core update under WP 2.9
* fix language file de_DE

= v0.5 (12/22/2009) =
* small fix for use WP and the plugin with SSL `https`

= v0.4 (12/02/2009) =
* add new feature: hide version for smaller right as admin

= v0.3.9 (09/07/2009) =
* change index.html in index.php for better works

= v0.3.8 (06/22/2009) =
* add function to remove theme-update information for non-admins
* rescan language file; edit de_DE

Please see the older changes on version on the [the official website](http://bueltge.de/wordpress-login-sicherheit-plugin/652/ "Secure WordPress")!
