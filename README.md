# ResizeFly Add-on Template
Use this template to register add-ons for the ResizeFly plugin.

## Installation
* Via Composer:<br> 
Create a new project with
```bash
composer create-project alpipego/resizefly-addon-template:dev-master  --no-install --remove-vcs YOUR_ADDON_NAME
```

* Manually:<br>
Check out this repository 

```bash
git checkout git@github.com:alpipego/resizefly-addon-template.git YOUR_ADDON_NAME
```

Make sure to update the namespace in all files and the `composer.json`.

## Configuration
### WordPress Plugin Header
Update the plugin header, check https://codex.wordpress.org/File_Header#Plugin_File_Header_Example for examples

### ResizeFly Add-on Config
Update the `name`, `nicename`, `version` and `min_version` to correctly register your add-on in the parent plugin. Take extra care not to use the `name` of another installed add-on, as this would override it.

```php
$addon = [
    'name'        => 'addon_template', // short name, only lowercase letters and underscores
    'nicename'    => 'Add-on Template', // Nice name, for use in UI
    'file'        => __FILE__, // Reference to this file, don't change
    'path'        => realpath( plugin_dir_path( __FILE__ ) ) . '/', // Path to this add-on, don't change
    'url'         => plugin_dir_url( __FILE__ ), // URL to this add-on, don't change
    'version'     => '1.0.0', // Version string, should match add-on version above
    'min_version' => '3.1.0', // Required minimum version of ResizeFly plugin, should match required version in composer.json
];
```

Your add-on is now registered with the parent plugin and has access to it's DI container, i.e. you can inject the plugins (or even other add-ons') classes into your code.

## Dependency Injection Container
ResizeFly uses a custom Pimple based dependency injection (DI) container. Register your classes:

```php
$plugin[ $addon['name'] ] = function ( $plugin ) use ( $addon ) {
    return new Addon( $plugin['addons'][$addon['name']] );
};
```

The add-ons configuration gets passed back to the `Addon()` class.
 
## Add-On

You can then use the `$config` array to e.g. register a custom script for the images:

```php
<?php

namespace Alpipego\Resizefly\AddonTemplate;

use Alpipego\Resizefly\Addon\AddonInterface;

final class Addon implements AddonInterface {
    private $config;

    /**
     * Addon constructor.
     *
     * @param array $config
     */
    public function __construct( $config ) {
        $this->config = $config;
    }

    /**
     * Set up add-on.
     * Register custom script
     */
    public function run() {
        add_action('wp_enqueue_scripts', [$this, 'enqueueScripts']);
    }
    
    /**
     * Enqueue script, reusing add-on configuration as much as possible 
     */
    public function enqueueScripts(){
        wp_enqueue_script($this->config['name'], $this->config['url']. '/js/'.$this->config['name'].'.js');
    }
}
``` 
