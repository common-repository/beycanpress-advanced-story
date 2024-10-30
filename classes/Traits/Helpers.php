<?php 

namespace BeycanPress\Story\Traits;
use \BeycanPress\Story\Helpers\EasyQuery;

/**
 * Contains the commonly used ones for this plugin
 */
trait Helpers
{   

    public static $properties = BPSTORY;
    
    public $tables = [
        'us' => 'user_stories',
        'as' => 'admin_stories',
        'asb' => 'admin_story_boxes',
    ];

    private $pluginDB = null;

    // magic methods!
    public function __set($property, $value){
        return self::$properties[$property] = $value;
    }

    public function __get($property){
        if ($property == 'db') return $this->db();
        return array_key_exists($property, self::$properties) ? self::$properties[$property] : null;
    }
    
    protected function db()
    {
        if (is_null($this->pluginDB)) {
            global $wpdb;
            $this->pluginDB = &$wpdb;
    
            if (is_array($this->tables)) {
                $this->pluginDB->pluginTables = (object) array_map(function($tableName) use ($wpdb) {
                    $tableName = $this->pluginDB->prefix . $this->textDomain . '_' . $tableName;
                    return new EasyQuery($tableName, $wpdb);
                }, $this->tables);
            }
        }

        return $this->pluginDB;
    }

    /**
     * @param string $view_name Directory name within the folder
     * @return void
     */
    protected function view($viewName, $args = [])
    {
        extract($args);
        ob_start();
        include $this->viewDir . $viewName . '.php';
        return ob_get_clean();
    }

    /**
     * Easy use for get_option
     * @param string $setting
     * @return mixed
     */
    protected function setting($setting = null)
    {
        $settings = get_option($this->settingsName); 

        if (is_null($setting)) {
            return $settings;
        }

        if (isset($settings[$setting])) :
            return $settings[$setting];
        else :
            return null;
        endif;
    }

    /**
     * @param string $type error, success more
     * @param string $notice notice to be given
     * @param bool $dismissible in-dismissible button show and hide
     * @return void
     */
    protected function notice($type, $notice, $dismissible = false)
    {
        echo $this->view('notice', array(
            'type' => $type,
            'notice' => $notice,
            'dismissible' => $dismissible
        ));
    }   

    /**
     * Ajax action hooks
     * @param string $action ajax function name
     * @return void
     */
    protected function ajaxAction($action)
    {
        add_action('wp_ajax_'.$action , array($this, $action));
        add_action('wp_ajax_nopriv_'.$action , array($this, $action));
    }

    /**
     * Nonce control mehod
     * @return void
     */
    protected function checkNonce()
    {
        $key = $this->textDomain . '_nonce';
        check_ajax_referer($key, 'nonce');
    }

    /**
     * New nonce create method
     * @return void
     */
    protected function createNewNonce()
    {
        $key = $this->textDomain . '_nonce';
        return wp_create_nonce($key);
    }

    /**
     * New nonce field create method
     * @return void
     */
    protected function createNewNonceField()
    {
        $key = $this->textDomain . '_nonce';
        wp_nonce_field($key, 'nonce');
    }

    /**
     * Nonce field control method
     * @return void
     */
    protected function checkNonceField()
    {
        $key = $this->textDomain . '_nonce';
        if (!isset($_POST['nonce'])) return false;
        return @wp_verify_nonce($_POST['nonce'], $key) ? true : false;
    }

    protected function currentURL()
    {
        $siteURL = explode('/', get_site_url());
        $requestURL = explode('/', $_SERVER['REQUEST_URI']);
        $currentURL = array_unique(array_merge($siteURL, $requestURL));
        return implode('/', $currentURL);
    }

    protected function dateToTimeAgo(string $date)
    {
        return human_time_diff(strtotime(wp_date('Y-m-d H:i:s')), strtotime($date));
    }

    protected function fileAudit($file)
    {
        $maxFileSize = $this->setting('max_file_size') * 1024 * 1024;
        $supportedMediaTypes = explode(',', $this->setting('supported_media_types'));

        $result = (object) [
            'success' => true,
            'message' => null
        ];

        $type = $this->getFileType($file);

        if (!in_array($type, $supportedMediaTypes)) {
            $result->success = false;
            $result->message = esc_html__('Unsupported Media Type', $this->textDomain);
        }

        if ($this->getFileSize($file) > $maxFileSize || $this->getFileSize($file) > wp_max_upload_size()) {
            $result->success = false;
            $result->message = esc_html__('The file you are trying to upload exceeds the maximum allowed file size!', $this->textDomain);
        }

        return $result;
    }

    protected function getFileSize($file)
    {
        if (strpos($file, 'base64') !== false) {
            $fileSize = strlen(base64_decode($file));
        } elseif (is_array($file) && isset($file['size'])) {
            $fileSize = $file['size'];
        } else {
            $fileSize = null;
        }

        return $fileSize;
    }

    protected function getFileTypeByName($name)
    {
        $type = explode('.', $name);
        return end($type);
    }

    protected function getFileType($file)
    {
        if (is_string($file)) {
            if (strpos($file, 'base64') !== false) {
                $pos = strpos($file, ';');
                $type = explode(':', substr($file, 0, $pos))[1];
                $type = explode('/', $type)[1];
            } else {
                $type = $this->getFileTypeByName($file);
            }
        } elseif (is_array($file) && isset($file['name'])) {
            $type = $this->getFileTypeByName($file['name']);
        } else {
            $type = null;
        }

        return $type;
    }

    protected function base64FileUpload($file) 
    {
        $wpUpload = wp_upload_dir();
        $path = $wpUpload['path'];
        $url = $wpUpload['url'];

        // Path slash control
        $path .= substr($path, -1) != '/' ? '/' : '';
        $url .= substr($url, -1) != '/' ? '/' : '';

        // Get file type
        $type = $this->getFileType($file);

        // Prepare file
        $fileName = strtotime(wp_date('Y-m-d H:i:s')).'.'.$type;
        $filePath = $path.$fileName;
        $fileURL = $url.$fileName;
        $decodedFile = base64_decode(substr($file, strpos($file, ',') + 1, strlen($file)));
        
        file_put_contents($filePath, $decodedFile);
        
        return (object) [
            'name' => $fileName,
            'path' => $filePath,
            'URL' => $fileURL
        ];
    }

}