<?php
    $options = array_merge($options, [
        "refresh" => intval($this->setting('auto_refresh_time')),
        "transitionTime" => intval($this->setting('transition_time')),
        "supportedMediaTypes" => explode(',', $this->setting('supported_media_types')),
        "maxFileSize" => intval($this->setting('max_file_size')),
        "multiLang" => [
            "seeMore" => esc_html__('See More', $this->textDomain),
            "unsupportedMedia" => esc_html__('Unsupported Media Type', $this->textDomain),
            "maxFileSizeWarning" => esc_html__('The file you are trying to upload exceeds the maximum allowed file size!', $this->textDomain),
            "enterURLAddress" => esc_html__('Please enter a URL address!', $this->textDomain),
            "wantAddURL" => esc_html__('Want to add a URL link to your story?', $this->textDomain),
            "invalidURL" => esc_html__('You have entered an invalid URL format, please enter a valid URL format.', $this->textDomain),
            "publish" => esc_html__('Publish', $this->textDomain),
            "selectNewMedia" => esc_html__('Select new media', $this->textDomain),
            "close" => esc_html__('Close', $this->textDomain),
            "createNewStory" => esc_html__('Create new story', $this->textDomain),
            "confirmDelete" => esc_html__('Are you sure you want to delete your story?', $this->textDomain),
            "waiting" => esc_html__('Please wait...', $this->textDomain)
        ]
    ]);
?>
<div 
    class="bpstory-placeholder" 
    id="bpstory-<?php echo esc_attr($id); ?>" 
    data-options='<?php echo isset($options) ? esc_attr(json_encode($options)) : null; ?>'
>
</div>