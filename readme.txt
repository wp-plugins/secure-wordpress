=== Acunetix Secure WordPress ===
Author: Acunetix
Contributors: Acunetix
Tags: security, securityscan, chmod, permissions, admin, administration, authentication, database, dashboard, post, notification, password, plugin, posts,
plugins, private, protection, tracking, wordpress
Requires at least: 3.0
Tested up to: 3.6
Stable tag: trunk

Scans your WordPress installation for security vulnerabilities.

== Description ==
Acunetix Secure WordPress plugin is a free and comprehensive security tool that helps you secure your WordPress
installation and suggests corrective measures for: securing file permissions, security of the database, version hiding,
WordPress admin protection and lots more.

Acunetix Secure WordPress checks your WordPress website/blog for security vulnerabilities and suggests corrective actions such as:

1. Passwords
2. File permissions
3. Database security
4. Version hiding
5. WordPress admin protection/security
6. Removes WP Generator META tag from core code

= Requirements =

* WordPress version 3.0 and higher
* PHP5 (tested with PHP Interpreter >= 5.2.9)

For more information on the Acunetix WP Security plug-in and other WordPress security news, visit the
<a href="http://www.acunetix.com/blog/" target="_blank">Acunetix Blog</a> and join
our <a href="http://www.facebook.com/Acunetix" target="_blank">Facebook</a> page. Post any questions or feedback
on the <a href="http://wordpress.org/support/plugin/secure-wordpress" target="_blank">Acunetix Secure WordPress plug-in forum</a>.


= Key security features: =

* Easy backup of WordPress database for disaster recovery
* Removal of error-information on login-page
* Addition of index.php to the wp-content, wp-content/plugins, wp-content/themes and wp-content/uploads directories to prevent directory listings
* Removal of wp-version, except in admin-area
* Removal of Really Simple Discovery meta tag
* Removal of Windows Live Writer meta tag
* Removal of core update information for non-admins
* Removal of plugin-update information for non-admins
* Removal of theme-update information for non-admins (only WP 2.8 and higher)
* Hiding of wp-version in backend-dashboard for non-admins
* Removal of version in URLs from scripts and stylesheets only on frontend
* Reporting of security overview after WordPress blog is scanned
* Reporting of file permissions following security checks
* Live traffic tool to monitor your website activity in real time
* Integrated tool to change the database prefix
* Disabling of database error reporting (if enabled)
* Disabling of PHP error reporting

For more information on the Acunetix Secure WordPress plug-in and other WordPress security news, visit the
<a href="http://www.acunetix.com/blog/" target="_blank">Acunetix Blog</a> and join
our <a href="http://www.facebook.com/Acunetix" target="_blank">Facebook</a> page. Post any questions or feedback
on the <a href="http://wordpress.org/support/plugin/secure-wordpress" target="_blank">Acunetix Secure WordPress plug-in forum</a>.



== Installation ==

* Make a backup of your current installation
* Unpack the downloaded package
* Upload the extracted files to the /wp-content/plugins/ directory
* Activate the plugin through the 'Plugins' menu in WordPress

If you encounter any bugs, or have comments or suggestions, please post them on the
<a href="http://wordpress.org/support/plugin/secure-wordpress" target="_blank">Acunetix Secure WordPress plug-in forum</a>.

For more information on the Acunetix Secure WordPress plug-in and other WordPress security news, visit the
<a href="http://www.acunetix.com/blog/" target="_blank">Acunetix Blog</a> and join
our <a href="http://www.facebook.com/Acunetix" target="_blank">Facebook</a> page. Post any questions or feedback
on the <a href="http://wordpress.org/support/plugin/secure-wordpress" target="_blank">Acunetix Secure WordPress plug-in forum</a>.

== WordPress Security ==

Security Scanner:

1. Scans Wordpress installation for file/directory permissions vulnerabilites
2. Recommends corrective actions
3. Scans for general security vulnerabilities


For more information on the Acunetix Secure WordPress plug-in and other WordPress security news, visit the
<a href="http://www.acunetix.com/blog/" target="_blank">Acunetix Blog</a> and join
our <a href="http://www.facebook.com/Acunetix" target="_blank">Facebook</a> page. Post any questions or feedback
on the <a href="http://wordpress.org/support/plugin/secure-wordpress" target="_blank">Acunetix Secure WordPress plug-in forum</a>.


== Frequently Asked Questions ==

= Can I deactivate Acunetix Secure WordPress once I've run it once? =

No.  Acunetix Secure WordPress needs to be left activated to work.  Version hiding,
turning off DB errors, removing WP ID META tag from HTML output, and other
functionality will cease if you deactivate the plugin.

= How do I change the file permissions on my WordPress installation?  =

From the Linux command line (for advanced users):
    chmod xxx filename.ext
    (replace xxx with with the permissions settings for the file or folder).

From your FTP client:
    Most FTP clients, such as FileZilla, etc, allow for changing file
permissions.  Please consult your client's documentation for your specific
directions.

For more information, please visit http://codex.wordpress.org/Changing_File_Permissions

= Why do I need to hide my version of WordPress?  =

Many attackers and automated tools will try and determine software versions
before launching exploit code. Removing your WordPress blog version may
discourage some attackers and certainly will mitigate virus and malware programs
that rely on software versions.

For more information on the Acunetix Secure WordPress plug-in and other WordPress security news, visit the
<a href="http://www.acunetix.com/blog/" target="_blank">Acunetix Blog</a> and join
our <a href="http://www.facebook.com/Acunetix" target="_blank">Facebook</a> page. Post any questions or feedback
on the <a href="http://wordpress.org/support/plugin/secure-wordpress" target="_blank">Acunetix Secure WordPress plug-in forum</a>.


== Screenshots ==

1. The File Scan Report
2. The Settings page, displaying all configurable options
3. The Live Traffic page
4. The new Dashboard page


== Other Notes ==
= License =
Good news, this plugin is free for everyone! Since it's released under the GPL, you can use it free of charge on your personal or commercial blog.

For more information on the Acunetix Secure WordPress plug-in and other WordPress security news, visit the
<a href="http://www.acunetix.com/blog/" target="_blank">Acunetix Blog</a> and join
our <a href="http://www.facebook.com/Acunetix" target="_blank">Facebook</a> page. Post any questions or feedback
on the <a href="http://wordpress.org/support/plugin/secure-wordpress" target="_blank">Acunetix Secure WordPress plug-in forum</a>.



== Changelog ==

= 3.0.1 =
* Added the missing files


= 3.0.0 =
* Complete core update
* Added live traffic functionality
* Added check for the wp-config.php file one level above if not found in the install directory
* Fixed broken functionalities
* Security settings are now configurable
* Removed all languages


= v2.0.8 =
* Removed the registration requirement

= v2.0.7 =
* Update: Updated the deprecated function call get_bloginfo('siteurl') to get_bloginfo('url')
* Update: Updated validation for plug-in form fields (email address, user name, target id, etc.)

= v2.0.6 =
* New setting: Option to open / close WebsiteDefender dashboard widget
* Update: Internal code updates

= v2.0.5 =
* BugFix: The bug reported about ALTER rights retrieval has been addressed
* Update: Code cleanup
* Update: Minor internal updates

= v2.0.4 =
* Feature: The Acunetix RSS widget added to the admin dashboard
* Update: The plug-in has been made compatible with WP Security Scan and WebsiteDefender WordPress Security
* Feature: Turkish language files added.

= v2.0.3 (07/21/2011) =
* Bugfix: The import of external resources has been fixed.

= v2.0.2 (07/20/2011) =
* Bugfix: Updated the links to websitedefender.com

= v2.0.1 (07/20/2011) =
* Update: Major code cleanup
* Update: Updated the class that handles the authentication/registration with WebsiteDefender.com in order to avoid code collision when both plug-ins are active.
* New: Dependent files (.css/.js/.php) have been added

= v2.0.0 (03/22/2011) =
* Feature: Release new stable version
* Feature: Support for WordPress 3.1
* Feature: Change owner of the plugin to WebsiteDefender
* Feature: Re-branding of the plugin
* Feature: Integrated WebsiteDefender registration in Settings

= v1.0.6 (11/15/2010) =
* Bugfix: change from `public` to `var` for variables to use the plugin on PHP5.2 and smaller

= v1.0.5 (11/10/2010) =
* Feature: Remove WordPress version on urls form scripts and stylesheets
* Maintenance: rescan and update german language file
* Remove: exclude to add string fpr wp-scanner-service; Wish of the community users

= v1.0.4 (10/09/2010 =
* Bugfix: update options

= v1.0.3 (10/06/2010) =
* Bugfix: include JS for remove version in backend for Non-Admins
* Bugfix: change for php-warning at update options
* Maintenance: update italian language files
* Maintenance: update german language files
* Maintenance: update pot file

= v1.0.2 (09/10/2010) =
* add persian language file
* change the backend; remove WP Scanner function
* change the include of javascript for metaboxes

= v1.0.1 (08/06/2010) =
* add more hooks to remove WordPress Version; was change with WP3.0

= v1.0 (07/09/2010) =
* release stable version
* small changes on the source
* change owner of the plugin

= v0.8.6 (06/18/2010) =
* fix a problem with https://; see [Ticket #13941](http://core.trac.wordpress.org/ticket/13941)

= v0.8.5 (05/16/2010) =
* small code changes for WP coding standards
* add free malware and vulnerabilities scan for test this; the scan has most interested informations and scan all of the server

= v0.8.4 (05/05/2010) =
* add method for use the plugin also on ssl-installs
* change uninstall method

= v0.8.3 (04/14/2010) =
* bugfix fox secure block bad queries on string for case-insensitive

= v0.8.2 (03/21/2010) =
* fix syntax error on ask for rights to block bad queries
* add french language files

= v0.8.1 (03/08/2010) =
* remove versions-information on backend with javascript
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