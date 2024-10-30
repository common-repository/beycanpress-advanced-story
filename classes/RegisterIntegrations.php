<?php

namespace BeycanPress\Story;

use \Elementor\Plugin;

class RegisterIntegrations
{
    use Traits\Helpers;

    public function __construct()
    {   
        new Integrations\MetaBoxes;
    }
}