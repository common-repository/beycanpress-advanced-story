<?php

namespace BeycanPress\Story\StoryTypes;

use \BeycanPress\Story\Helpers\Response;
use \BeycanPress\Story\Traits\Helpers;

/**
 * Everything about admin stories is here
 */
class AdminStories
{
    use Helpers;

    /**
     * Register admin stories short code
     */
    public function __construct()
    {
        if (is_admin()) {
            // Ajax
            $this->ajaxAction('getAdminStoriesList');
        } else {
            add_action('init', function(){
                add_shortcode('bpstory-admin-stories', array($this, 'init'));
            });
        }
    }

    public function init()
    {
        return $this->view('bpstory-js', [
            'id' => 'admin-stories',
            'options' => [
                "getStoriesParameter" => "getAdminStoriesList"
            ]
        ]);
    }

    protected function getStoryBoxes()
    {
        $query = "SELECT 
        asb.id, asb.story_box, asb.story_box_image, asb.status, ast.*, ast.status AS status_ast
        FROM `{$this->db->pluginTables->asb->tableName}` AS asb 
        LEFT JOIN `{$this->db->pluginTables->as->tableName}` AS ast ON asb.id = ast.story_box_id
        WHERE asb.status = 1 AND ast.status = 1 
        AND ast.show_until_date = '0000-00-00' OR ast.show_until_date >= NOW()
        ORDER BY ast.created_at ASC";

        $storyBoxes = $this->db->get_results($query);

        return $this->decorateStoryBoxes($storyBoxes);
    }

    private function decorateStoryBoxes(array $storyBoxes)
    {
        array_map(function($story) use (&$newStoryBoxes) {
            $newStoryBoxes[$story->story_box_id]['storyBox'] = $story->story_box;
            $newStoryBoxes[$story->story_box_id]['storyBoxImage'] = $story->story_box_image;
            $newStoryBoxes[$story->story_box_id]['lastStoryCreatedAt'] = strtotime($story->created_at);
            $newStoryBoxes[$story->story_box_id]['lastStoryPublishTime'] = $this->dateToTimeAgo($story->created_at);
            $newStoryBoxes[$story->story_box_id]['storyItems'][] = $this->decorateStoryItem($story);

        }, $storyBoxes);

        usort($newStoryBoxes, function($a, $b) {
            return $a['lastStoryCreatedAt'] < $b['lastStoryCreatedAt'];
        });
        
        return array_values($newStoryBoxes);
    }

    private function decorateStoryItem(object $story)
    {   
        return [
            'storyId' => absint($story->story_id),
            'mediaURL' => $story->media_url,
            'externalURL' => $story->external_url,
            'createdAt' => $story->created_at,
            'publishTime' => $this->dateToTimeAgo($story->created_at)
        ];
    }

    public function getAdminStoriesList()
    {
        $storyBoxes = $this->getStoryBoxes();
        if (count($storyBoxes) == 0) {
            Response::success(esc_html__('No story found.', $this->textDomain));
        }

        Response::success(null, $storyBoxes);
    }

}