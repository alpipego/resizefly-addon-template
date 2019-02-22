<?php

namespace Alpipego\Resizefly\AddonTemplate;

use Alpipego\Resizefly\Common\Composer\Autoload\ClassLoader;
use Alpipego\Resizefly\Plugin;

/**
 * Plugin Name: ResizeFly Add-On Template
 * Description:
 * Plugin URI:
 * Version:     1.0.0
 * Author:
 * Author URI:
 * License:     MIT
 * License URI: https://opensource.org/licenses/MIT
 */

$addon = [
    'name'        => 'addon_template', // short name, only lowercase letters and underscores
    'nicename'    => 'Add-on Template', // Nice name, for use in UI
    'file'        => __FILE__, // Reference to this file, don't change
    'path'        => realpath( plugin_dir_path( __FILE__ ) ) . '/', // Path to this add-on, don't change
    'url'         => plugin_dir_url( __FILE__ ), // URL to this add-on, don't change
    'version'     => '1.0.0', // Version string, should match add-on version above
    'min_version' => '3.1.0', // Required minimum version of ResizeFly plugin
];

// add the add-on to parent plugin
add_filter( 'resizefly/addons', function ( $addons ) use ( $addon ) {
    $addons[ $addon['name'] ] = $addon;

    return $addons;
} );

add_action( 'plugins_loaded', function () use ( $addon ) {
    // get the parent plugin instance
    /** @var Plugin $plugin */
    $plugin = apply_filters( 'resizefly/addons/' . $addon['name'], null );

    // check if parent plugin is active and all versions are compatible
    if ( ! require __DIR__ . '/app/compat-checker.php' ) {
        return;
    }

    // use parent autoloader
    /** @var ClassLoader $classLoader */
    $classLoader = $plugin->get( ClassLoader::class );
    $classLoader->setPsr4( __NAMESPACE__, __DIR__ . '/src' );
    $classLoader->register();

    // register the Addon() class in parent plugin
    // pass this add-ons' configuration back to the Addon() class
    $plugin[ $addon['name'] ] = function ( $plugin ) use ( $addon ) {
        return new Addon( $plugin['addons'][$addon['name']] );
    };

    $plugin->run();
}, 21 );
