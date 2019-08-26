<?php
/* Developed by WEBmods
 * Zagorski oglasnik j.d.o.o. za usluge | www.zagorski-oglasnik.com
 *
 * License: GPL-3.0-or-later
 * More info in license.txt
*/

/*
Plugin Name: Video Embed
Plugin URI: https://www.zagorski-oglasnik.com/
Description: Users can embed Youtube and Vimeo videos to their ads. Videos show in a nice player from plyr.io.
Version: 1.0.0
Author: WEBmods by Zagorski Oglasnik jdoo
Author URI: https://www.zagorski-oglasnik.com/
Short Name: zo_videoembed
Plugin update URI: video-embed
*/

define('VIDEOEMBED_PATH', dirname(__FILE__) . '/' );
define('VIDEOEMBED_FOLDER', osc_plugin_folder(__FILE__) . '/' );
define('VIDEOEMBED_REQUIRED', false);

require_once VIDEOEMBED_PATH.'oc-load.php';

function videoembed_install() {
    VideoEmbedModel::newInstance()->install();
}
osc_register_plugin(osc_plugin_path(__FILE__), 'videoembed_install');

function videoembed_uninstall() {
    VideoEmbedModel::newInstance()->uninstall();
}
osc_add_hook(osc_plugin_path(__FILE__) . '_uninstall', 'videoembed_uninstall');

function videoembed_configuration() {
    osc_plugin_configure_view(osc_plugin_path(__FILE__));
}
osc_add_hook(osc_plugin_path(__FILE__).'_configure', 'videoembed_configuration');
