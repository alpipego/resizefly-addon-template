<?php
/**
 * @var \Alpipego\Resizefly\Plugin $plugin
 * @var array $addon
 */

namespace Alpipego\Resizefly\AddonTemplate;

$error = false;
// parent not active
if ( is_null( $plugin ) ) {
	$error = sprintf(
		wp_kses( __( 'Please activate <a href="https://wordpress.org/plugins/resizefly/">%s</a> first. And then try activating the <em>%s</em> add-on again.', 'resizefly-lazy' ),
			[ 'a' => [ 'rel', 'href', 'target', 'title' ], 'em' => [] ],
			[ 'https' ]
		),
		'ResizeFly',
		$addon['nicename']
	);
}

// parent not in version 2.1.0+
$version = ! empty( $addon['min_version'] ) ? $addon['min_version'] : $plugin['config.version'];
if ( ! $error && version_compare( $plugin['config.version'], $version ) === - 1 ) {
	$error = sprintf(
		wp_kses(
			__( 'The %s addon requires at least <strong>ResizeFly %s</strong>. You have version %s installed. Please update ResizeFly.', 'resizefly-lazy' ),
			[ 'strong' => [] ],
			[ 'https' ]
		),
		$addon['nicename'],
		$addon['min_version'],
		$plugin['config.version']
	);

}

if ( $error ) {
	add_action( 'admin_init', function () use ( $error, $addon ) {
		add_action( 'admin_notices', function () use ( $error, $addon ) {
			printf( '<div class="error"><p>%s</p></div>', $error );
		} );
		deactivate_plugins( $addon['file'], true );
	} );

	return false;
}

return true;
