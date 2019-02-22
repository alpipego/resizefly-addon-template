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
     * Register actions or filters here, runs on plugins_loaded.
     */
    public function run() {

    }
}
