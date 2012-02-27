=== Plugin Name ===
Plugin Name: Wordpress + Varnish ESI caching
Donate: http://cd34.com/
Contributors: cd34
Tags: cache, caching, varnish, esi
Requires at least: 2.6
Tested up to: 3.3
Stable tag: 0.2

This plugin uses Wordpress + Varnish and caches the sidebar with ESI.

== Description ==

This plugin uses [Varnish](https://www.varnish-cache.org/) and [ESI](https://www.varnish-cache.org/docs/3.0/tutorial/esi.html) to cache the sidebar separately from the page and aggressively uses Purges so that you can set long expire times without worrying about comments and post edits not showing up immediately.

I did a presentation on the methodology behind this which explains how
the plugin works. [Blog Post](http://cd34.com/blog/scalability/wordpress-varnish-and-esi-plugin/)

Should you use this?

Probably not.

If you're on a Virtual server, it is likely that you won't be able to install
Varnish or run your site behind it. If you are able to run Varnish, the use
case for this plugin is a little different than [Wordpress-Varnish](http://wordpress.org/extend/plugins/wordpress-varnish/).

If you are trying to cache your site where your visitors are hitting one
or two pages and you don't get many comments, [Wordpress-Varnish](http://wordpress.org/extend/plugins/wordpress-varnish/) will result in faster pageloads.

If your site is dynamic and you receive a lot of comments, or, your visitors
are likely to surf multiple pages rather than a single landing page, using
ESI will probably provide enough benefit to make the plugin worthwhile for 
you.

Image used under Creative Commons License from [Varnish Software](https://www.varnish-software.com/blog/superbunny-under-cc-license).

== Installation ==

1. Activate the plugin through the 'Plugins' menu in WordPress
2. Appearance, Widgets, drag the ESI Widget to Sidebar 1
3. Drag the Widgets that you want to display on your sidebar to the
newly created ESI Widget Sidebar.
4. Settings, Varnish ESI Widget, Set the Server IP(s) that the plugin
needs to communicate with to purge.
5. Make sure you have configured Varnish with the included default.vcl
or with one that you have created that allows the plugin to issue BANs.
Verify your ACL is set to allow purging.

== Frequently Asked Questions ==

= Why is this approach better than other caching systems? =

The worst queries in Wordpress are usually contained within the sidebar. If
your content is engaging and someone browses from one page to the next, 
most caching plugins need to generate the page and sidebar on a fresh visit.
Since this plugin caches the sidebar separately using ESI, the only 
queries executed on the second and successive pages is the page content - 
not the sidebar.

= What Plugins break with this? =

Any plugin that requires the page to load and not be served from cache
will break. Since your page might be served from Varnish, it will never
hit the backend server. Statistics plugins are generally affected, but
Google Analytics and Pikwik do work properly. Banner ad solutions also
need to serve the content through an IFrame or Javascript rather than
depending on a pageload.

WP Greet Box and WP Post Views are known to break with caching.

== Screenshots ==

1. Move the ESI Widget to the sidebar, populate the ESI Widget Sidebar
with the Widgets you want displayed.

2. Configure the plugin with the IP address(s) or Hostname(s) of your
Varnish Servers. You'll need to make sure your VCL allows the server
to purge.

== Changelog ==

= 0.2 =
* updated VCL to actually parse for ESI
* fixed ESI handler path

= 0.1 =
* Initial release

== Upgrade Notice ==

* Updated VCL to actually use ESI
* fixed ESI handler path
