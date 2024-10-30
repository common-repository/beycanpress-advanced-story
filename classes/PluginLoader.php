<?php

namespace BeycanPress\Story;

/**
 * Plugin loader class
 */
final class PluginLoader
{
    use Traits\Helpers;
    
    /**
     * Class construct
     * @return void
     */
    public function __construct()
    {
        // Load story types
        $this->initStoryTypes();

        // Load integrations after from all plugins load
        add_action('plugins_loaded', function(){
            new RegisterIntegrations;
        });

        if (is_admin()) {
            $this->wpAdminProcess();
        } else {
            $this->frontendProcess();
        }
    }

    private function initStoryTypes()
    {
        new StoryTypes\AdminStories;
    }

    private function initPages()
    {
        new Pages\AdminStoryBoxes;
        new Pages\AdminStories;
    }

    /**
     * @return void
     */
    public function wpAdminProcess()
    {
        // Init all page
        $this->initPages();

        // Load CSF
        require_once $this->path . 'includes/codestar-framework-2.2.2/codestar-framework.php';
        add_action('init', function(){
            require_once $this->path . 'includes/options.php';
        }, 9);

        // Hooks
        add_action('admin_enqueue_scripts', array($this, 'adminEnqueueScripts'), 9);
        
        $pluginFile = dirname(plugin_basename($this->pluginFile)) . '/bpstory.php';
        add_filter('plugin_action_links_' . $pluginFile, array($this, 'buyNowLink'));
    }

    public function buyNowLink($links) {
		$settingsLink = array(
			'<a style="font-weight: bold;" target="_blank" href="https://1.envato.market/eO0yz">Buy now premium</a>',
		);
		return array_merge($settingsLink, $links);
	}

    /**
     * @return void
     */
    public function adminEnqueueScripts()
    {
        wp_enqueue_media();
        wp_enqueue_style(
            'bpstory-app',
            $this->url . 'assets/css/backend.min.css',
            array(),
            $this->version
        );
        wp_enqueue_script(
            'bpstory-app',
            $this->url . 'assets/js/backend.min.js',
            array(),
            $this->version,
            true
        );
        wp_localize_script(
            'bpstory-app',
            'BPStory',
            self::jsData()
        );
        wp_localize_script(
            'jquery',
            'BPStory',
            self::jsData()
        );
    }

    /**
     * @return void
     */
    public function frontendProcess()
    {
        add_action('wp_head', function() {
            echo $this->view('api-url');
        });

        // Hooks
        add_action('wp_enqueue_scripts', array($this, 'wpEnqueueScripts'));
    }

    /**
     * js and css files for frontend
     * @return void
     */
    public function wpEnqueueScripts()
    { 
        wp_enqueue_style(
            'bpstory-js-chunk-vendors',
            $this->url . 'assets/bpstory-js/css/chunk-vendors.css',
            array(),
            $this->version
        );
        wp_enqueue_style(
            'bpstory-js-app',
            $this->url . 'assets/bpstory-js/css/app.css',
            array(),
            $this->version
        );
        wp_enqueue_script(
            'bpstory-js-chunk-vendors',
            $this->url . 'assets/bpstory-js/js/chunk-vendors.js',
            array(),
            $this->version,
            true
        );
        wp_enqueue_script(
            'bpstory-js-app',
            $this->url . 'assets/bpstory-js/js/app.js',
            array(),
            $this->version,
            true
        );
        wp_enqueue_style(
            'bpstory-app',
            $this->url . 'assets/css/frontend.min.css',
            array(),
            $this->version
        );
        wp_localize_script(
            'bpstory-js-chunk-vendors',
            'BPStory',
            self::jsData()
        );
    }

    /**
     * Dyanmic javaScript datas
     * 
     * @return array
     */
    public static function jsData()
    {
        return array(
            'selectMedia' => esc_html__('Select media', self::$properties['textDomain'])
        );
    }

}