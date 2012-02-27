<?php
/*
Plugin Name: Wordpress Varnish ESI Widget
Plugin URI: http://code.google.com/p/cd34-wordpress/wiki/WordpressVarnishESI
Description: Utilize Varnish and cache the sidebar using ESI
Author: Chris Davies
Version: 0.2
Author URI: http://cd34.com/

* selective purge based on site - comment posted, do we need to purge sidebar or frontpage? (only frontpage if # of comments is displayed)
*/

function widget_esi_control() {
?>
<p>
Place the Widgets in 'ESI Widget Sidebar' and configure as needed.
</p>
<?php
}

function widget_esi($args) {
  echo $before_widget;
?>
<esi:include src="<?php echo plugin_dir_url(__FILE__);?>esihandler.php"/>
<?php
  echo $after_widget;
}
	
function widget_esi_init() {
  if ( !function_exists('register_sidebar_widget') ||
       !function_exists('register_widget_control') ) {
    return;
  }

  register_sidebar_widget('ESI Widget', 'widget_esi');
  register_widget_control('ESI Widget', 'widget_esi_control');

  if ( function_exists('register_sidebar') ) {
    register_sidebar(array(
        'name' => 'ESI Widget Sidebar',
        'before_widget' => '<li id="%1$s" class="widget %2$s">',
        'after_widget' => '</li>',
        'before_title' => '<h2 class="widgettitle">',
        'after_title' => '</h2>',
    ));
  }
}

function esi_purge($post_id) {
  $url = parse_url(get_permalink($post_id));
  _esi_purge($url['host'], $url['path']);
  _esi_purge(site_url(), plugin_dir_url(__FILE__) + "esihandler.php");
  _esi_purge(site_url(), '/');
}

function _esi_purge($hostname, $uri) {
  $purgecmd = "BAN $uri HTTP/1.0\nHost: $hostname\n\n";

  $varnish_ips = explode(',', get_option('varnish-esi-servers'));
  foreach ($varnish_ips as $ip) {
    $fp = fsockopen(trim($ip), 80, $errno, $errstr, 5);
    if ($fp) {
      fwrite($fp, $purgecmd);
      while (!feof($fp)) {
          fgets($fp, 4096);
      }
      fclose($fp);
    } //if ($fp) {
  } // foreach ($varnish_ips as $ip) {
}

function esi_credits() {
?>
<!-- Powered by ESI-Widget -->
<?php
}

function esi_widget_menu() {
  add_options_page('ESI Widget Options', 'Varnish ESI Widget', 'manage_options', 'esi-widget-options', 'esi_widget_options');
}

function esi_widget_options() {
    if (!current_user_can('manage_options'))  {
        wp_die( __('You do not have sufficient permissions to access this page.') );
    }
?>

<div class="wrap">
<h2>Varnish ESI Widget setup</h2>
<form method="post" action="options.php">
    <?php settings_fields( 'varnish-esi' ); ?>

    <table class="form-table">
        <tr valign="top">
        <th scope="row">Varnish Server IPs - comma separated </th>
        <td><input type="text" name="varnish-esi-servers" value="<?php echo get_option('varnish-esi-servers'); ?>" /></td>
        </tr>
    </table>

<p class="submit">
<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
</p>
</form>
<?php
}

function esi_widget_init() {
  register_setting('varnish-esi', 'varnish-esi-servers');
}

add_action('init', 'widget_esi_init');
add_action('edit_post', 'esi_purge');
add_action('deleted_post', 'esi_purge');
add_action('wp_footer', 'esi_credits');

add_action('admin_menu', 'esi_widget_menu');
add_action('admin_init', 'esi_widget_init');
