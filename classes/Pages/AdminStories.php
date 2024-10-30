<?php 

namespace BeycanPress\Story\Pages;

use \BeycanPress\Story\Traits\Helpers;
use \Beycan\WordPressTableCreator\Table;
use \BeycanPress\Story\Pages\AdminStoryBoxes;

/**
 * Admin stories page
 */
class AdminStories
{   
    use Helpers;

    // Current page url
    protected $pageURL;

    /**
     * Class construct
     * @return void
     */
    public function __construct()
    {
        $this->pageURL = self::getPageURL();
        
        add_action('admin_menu', function(){
            add_submenu_page(
                'bpstory-admin-story-boxes',
                esc_html__('Admin stories', $this->textDomain),
                esc_html__('Admin stories', $this->textDomain),
                'manage_options',
                'bpstory-admin-stories',
                array($this, 'page')
            );
        });
    }

    public static function getPageURL()
    {
        return admin_url('admin.php?page=bpstory-admin-stories');
    }

    public function page()
    {
        $storyBoxes = $this->db->pluginTables->asb->findAll();

        if (!$storyBoxes) {
            wp_redirect(AdminStoryBoxes::getPageURL() . '&not-found-story-boxes=1');
        }
        
        if (isset($_GET['add-new'])) {
            if ($this->db->pluginTables->as->getCount() >= 30) {
                $this->notice('error', esc_html__('You cannot create a story because you have reached the 30 story limit.', $this->textDomain));
            } else {
                $this->addNew();
            }
        } elseif (isset($_GET['edit'])) { 
            $this->edit();
        } else {
            $this->list();
        }
    }

    private function addNew()
    {
        $this->addNewProcess();

        echo $this->view('pages/admin-stories/add-new');
    }

    private function addNewProcess()
    {
        if (isset($_POST['add-new'])) {

            if ($this->db->pluginTables->as->getCount() >= 30) {
                $this->notice('error', esc_html__('You cannot create a story because you have reached the 30 story limit.', $this->textDomain));
            } else {
                if (!$this->checkNonceField()) {
                    $this->notice('error', esc_html__('Sorry something went wrong.', $this->textDomain), true);
                }
    
                $storyBoxId = isset($_POST['story_box_id']) ? absint($_POST['story_box_id']) : null;
                $mediaURL = isset($_POST['media_url']) ? esc_url_raw($_POST['media_url']) : null;
                $externalURL = isset($_POST['external_url']) ? esc_url_raw($_POST['external_url']) : null;
                $showUntilDate = isset($_POST['show_until_date']) ? sanitize_text_field($_POST['show_until_date']) : '0000-00-00';
    
                if (!$mediaURL || !$storyBoxId) {
                    $this->notice('error', esc_html__('Media URL are required!', $this->textDomain), true);
                }
    
                $result = $this->db->pluginTables->as->insert([
                    'story_box_id' => $storyBoxId,
                    'media_url' => $mediaURL,
                    'external_url' => $externalURL,
                    'show_until_date' => $showUntilDate,
                    'status' => 1
                ]);
    
                if (!$result) {
                    $this->notice('error', esc_html__('There was a problem adding the story!', $this->textDomain), true);
                } else {
                    wp_redirect($this->pageURL . '&last-process=add-new');
                }
            }
        }
    }
    private function edit()
    {
        
        $this->editProcess();

        $storyId = isset($_GET['edit']) ? absint($_GET['edit']) : null;
        
        if (!$storyId) {
            $this->notice('error', esc_html__('Missing parameter!', $this->textDomain), true);
        } else {
            $story = $result = $this->db->pluginTables->as->findOneBy([
                'story_id' => $storyId
            ]);
            echo $this->view('pages/admin-stories/edit', [
                'story' => $story
            ]);
        }
    }

    private function editProcess()
    {
        if (isset($_POST['edit'])) {
            if (!$this->checkNonceField()) {
                $this->notice('error', esc_html__('Sorry something went wrong.', $this->textDomain), true);
            }

            $storyId = isset($_POST['story_id']) ? absint($_POST['story_id']) : null;
            $storyBoxId = isset($_POST['story_box_id']) ? absint($_POST['story_box_id']) : null;
            $mediaURL = isset($_POST['media_url']) ? esc_url_raw($_POST['media_url']) : null;
            $status = isset($_POST['status']) ? absint($_POST['status']) : null;

            if (!$mediaURL || !$storyBoxId) {
                $this->notice('error', esc_html__('Media URL are required!', $this->textDomain), true);
            }

            $result = $this->db->pluginTables->as->update([
                'story_box_id' => $storyBoxId,
                'media_url' => $mediaURL,
                'status' => $status
            ], ['story_id' => $storyId]);

            if ($result === false) {
                $this->notice('error', esc_html__('There was a problem edited the story!', $this->textDomain), true);
            } else {
                wp_redirect($this->pageURL . '&last-process=edit');
            }
        }
    }

    private function list()
    {
        if (isset($_GET['last-process'])) {
            if ($_GET['last-process'] == 'add-new') {
                $this->notice('success', esc_html__('Story successfully added.', $this->textDomain), true);
            } elseif ($_GET['last-process'] == 'edit') {
                $this->notice('success', esc_html__('Story successfully edited.', $this->textDomain), true);
            }
        }

        $this->deleteProcess();

        $table = $this->createTable();

        echo $this->view('pages/admin-stories/list', [
            'table' => $table
        ]);
    }

    private function createTable()
    {
        $stories = $this->db->pluginTables->as->findAll();

        $table = new Table(
            [
                'story_id'        => esc_html__('ID', $this->textDomain),
                'story_box_id'    => esc_html__('Story box ID', $this->textDomain),
                'media_url'       => esc_html__('Media URL', $this->textDomain),
                'status'          => esc_html__('Status', $this->textDomain),
                'created_at'      => esc_html__('Created at', $this->textDomain),
                'edit'            => esc_html__('Edit', $this->textDomain),
                'delete'          => esc_html__('Delete', $this->textDomain)
            ],
            $stories
        );

        $table->setOptions([
            'search' => [
                'id' => 'search-box',
                'title' => esc_html__('Search...', $this->textDomain)
            ]
        ]);

        $table->addHooks([
            'status' => function($args) {
                return $args->status ? esc_html__('Active', $this->textDomain) : esc_html__('Passive', $this->textDomain);
            },
            'media_url' => function($args) {
                return '<a href="'.$args->media_url.'" class="button" target="_blank">'.esc_html__('Open', $this->textDomain).'</a>';
            },
            'edit' => function($args) {
                ob_start();
                ?>
                <a href="<?php echo esc_url($this->pageURL . '&edit=' . $args->story_id); ?>" class="button"><?php echo esc_html__('Edit', $this->textDomain); ?></a>
                <?php
                return ob_get_clean();
            },
            'delete' => function($args) {
                ob_start();
                ?>
                <form action="<?php echo esc_url($this->currentURL()); ?>" method="post">
                    <?php $this->createNewNonceField(); ?>
                    <input type="hidden" name="delete" value="<?php echo $args->story_id; ?>">
                    <button class="button"><?php echo esc_html__('Delete', $this->textDomain); ?></button>
                </form>
                <?php
                return ob_get_clean();
            },
        ]);
        
        return $table;
    }

    private function deleteProcess()
    {
        if (isset($_POST['delete'])) {
            if (!$this->checkNonceField()) {
                $this->notice('error', esc_html__('Sorry something went wrong.', $this->textDomain), true);
            }

            $storyId = isset($_POST['delete']) ? absint($_POST['delete']) : null;
            if (!$storyId) {
                $this->notice('error', esc_html__('Missing parameter!', $this->textDomain), true);
            }

            $result = $this->db->pluginTables->as->delete(['story_id' => $storyId]);

            if (!$result) {
                $this->notice('error', esc_html__('There was a problem deleted the story box!', $this->textDomain), true);
            } else {
                $this->notice('success', esc_html__('Story box deleted successfully.', $this->textDomain), true);
            }
        }
    }

}