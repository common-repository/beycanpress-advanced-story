<?php

namespace BeycanPress\Story\Integrations;

use \BeycanPress\Story\Traits\Helpers;

/**
 * BP Story meta boxes class
 */
class MetaBoxes
{
    use Helpers;

    /**
     * Class construct
     * @return void
     */
    public function __construct()
    {
        add_action('post_updated', array( $this, 'saveStoryVisibility' ), 10, 3);

        add_action('add_meta_boxes', function() {
            // Post and product post type
            add_meta_box(
                'bpstory_post_and_product_story_visibility',
                esc_html__('Story settings', $this->textDomain),
                array($this, 'postAndProductStoryVisibility'),
                array('post', 'product' ),
                'side',
                'high'
            );
        });
    }

    /**
     * Post type story setting
     * @param object $post
     * @return void
     */
    public function postAndProductStoryVisibility($post)
    {
        $this->createNewNonceField();
        $mediaURL = get_post_meta($post->ID, 'bpstory_media_url', true);
        $storyVisibility = get_post_meta($post->ID, 'bpstory_story_visibility', true);
        
        echo $this->view('post-and-product-story-visibility', [
            'storyVisibility' => $storyVisibility,
            'mediaURL' => $mediaURL
        ]);
    }

    /**
     * Story creator save
     * @param integer $postId current post
     * @param object $postAfter post savad after data
     * @return void
     */
    function saveStoryVisibility($postId, $postAfter)
    {
        // Permission control
        $postType = get_post_type_object($postAfter->post_type);
        if (!current_user_can( $postType->cap->edit_post, $postId)) {
            return $postId;
        }

        // Post type control
        if('post' !== $postAfter->post_type && 'product' !== $postAfter->post_type) {
            return $postId;
        }
            
        // Nonce control
        if (!$this->checkNonceField()) {
            return $postId;
        }

        // Autosave control
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return $postId;
        }
    
        if (isset($_POST['bpstory_info'])) {
            $storyVisibility = isset($_POST['bpstory_info']['story_visibility']) ? sanitize_text_field($_POST['bpstory_info']['story_visibility']) : null;
            $mediaURL = isset($_POST['bpstory_info']['media_url']) ? esc_url_raw($_POST['bpstory_info']['media_url']) : null;

            update_post_meta($postId, 'bpstory_media_url', $mediaURL);
            update_post_meta($postId, 'bpstory_story_visibility', $storyVisibility);
        }
        
        return $postId;
    }

}