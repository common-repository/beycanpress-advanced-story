<?php 

namespace BeycanPress\Story\Pages;

use \BeycanPress\Story\Traits\Helpers;
use \Beycan\WordPressTableCreator\Table;

/**
 * Admin story boxes page
 */
class AdminStoryBoxes
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
            add_menu_page( 
                esc_html__('BP Story', $this->textDomain), 
                esc_html__('BP Story', $this->textDomain), 
                'manage_options', 
                'bpstory-admin-story-boxes', 
                array($this, 'page'), 
                'dashicons-format-gallery'
            );
            add_submenu_page(
                'bpstory-admin-story-boxes',
                esc_html__('Admin story boxes', $this->textDomain),
                esc_html__('Admin story boxes', $this->textDomain),
                'manage_options',
                'bpstory-admin-story-boxes',
                array($this, 'page')
            );
        });
    }

    public static function getPageURL()
    {
        return admin_url('admin.php?page=bpstory-admin-story-boxes');
    }

    public function page()
    {
        if (isset($_GET['add-new'])) {
            if ($this->db->pluginTables->asb->getCount() >= 5) {
                $this->notice('error', esc_html__('You cannot create a story because you have reached the 5 story box limit.', $this->textDomain));
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

        echo $this->view('pages/admin-story-boxes/add-new');
    }

    private function addNewProcess()
    {
        if (isset($_POST['add-new'])) {
            if ($this->db->pluginTables->asb->getCount() >= 5) {
                $this->notice('error', esc_html__('You cannot create a story because you have reached the 5 story box limit.', $this->textDomain));
            } else {
                if (!$this->checkNonceField()) {
                    $this->notice('error', esc_html__('Sorry something went wrong.', $this->textDomain), true);
                }

                $storyBox = isset($_POST['story_box']) ? sanitize_title($_POST['story_box']) : null;
                $storyBoxImage = isset($_POST['story_box_image']) ? esc_url_raw($_POST['story_box_image']) : null;

                if (!$storyBox || !$storyBoxImage) {
                    $this->notice('error', esc_html__('Storybox name and storybox image are required!', $this->textDomain), true);
                }

                $result = $this->db->pluginTables->asb->insert([
                    'story_box' => $storyBox,
                    'story_box_image' => $storyBoxImage,
                    'status' => 1
                ]);

                if (!$result) {
                    $this->notice('error', esc_html__('There was a problem adding the story box!', $this->textDomain), true);
                } else {
                    wp_redirect($this->pageURL . '&last-process=add-new');
                }
            }
        }
    }

    private function edit()
    {
        
        $this->editProcess();

        $storyBoxId = isset($_GET['edit']) ? absint($_GET['edit']) : null;
        
        if (!$storyBoxId) {
            $this->notice('error', esc_html__('Missing parameter!', $this->textDomain), true);
        } else {
            $storyBox = $result = $this->db->pluginTables->asb->findOneBy([
                'id' => $storyBoxId
            ]);
            echo $this->view('pages/admin-story-boxes/edit', [
                'storyBox' => $storyBox
            ]);
        }
    }

    private function editProcess()
    {
        if (isset($_POST['edit'])) {
            if (!$this->checkNonceField()) {
                $this->notice('error', esc_html__('Sorry something went wrong.', $this->textDomain), true);
            }

            $id = isset($_POST['id']) ? absint($_POST['id']) : null;
            $storyBox = isset($_POST['story_box']) ? sanitize_title($_POST['story_box']) : null;
            $storyBoxImage = isset($_POST['story_box_image']) ? esc_url_raw($_POST['story_box_image']) : null;
            $status = isset($_POST['status']) ? absint($_POST['status']) : null;

            if (!$storyBox || !$storyBoxImage) {
                $this->notice('error', esc_html__('Storybox name and storybox image are required!', $this->textDomain), true);
            }

            $result = $this->db->pluginTables->asb->update([
                'story_box' => $storyBox,
                'story_box_image' => $storyBoxImage,
                'status' => $status
            ], ['id' => $id]);

            if ($result === false) {
                $this->notice('error', esc_html__('There was a problem edited the story box!', $this->textDomain), true);
            } else {
                wp_redirect($this->pageURL . '&last-process=edit');
            }
        }
    }

    private function list()
    {
        if (isset($_GET['last-process'])) {
            if ($_GET['last-process'] == 'add-new') {
                $this->notice('success', esc_html__('Story box successfully added.', $this->textDomain), true);
            } elseif ($_GET['last-process'] == 'edit') {
                $this->notice('success', esc_html__('Story box successfully edited.', $this->textDomain), true);
            }
        }
        
        if (isset($_GET['not-found-story-boxes'])) {
            $this->notice('error', esc_html__('In order to create a story, you must first create a story box.', $this->textDomain), true);
        }

        $this->deleteProcess();

        $table = $this->createTable();

        echo $this->view('pages/admin-story-boxes/list', [
            'table' => $table
        ]);
    }

    private function createTable()
    {
        $storyBoxes = $this->db->pluginTables->asb->findAll();

        $table = new Table(
            [
                'id'              => esc_html__('ID', $this->textDomain),
                'story_box'       => esc_html__('Story box', $this->textDomain),
                'story_box_image' => esc_html__('Story box image', $this->textDomain),
                'status'          => esc_html__('Status', $this->textDomain),
                'created_at'      => esc_html__('Created at', $this->textDomain),
                'edit'            => esc_html__('Edit', $this->textDomain),
                'delete'          => esc_html__('Delete', $this->textDomain)
            ],
            $storyBoxes
        );

        $table->setOptions([
            'search' => [
                'id' => 'search-box',
                'title' => esc_html__('Search...', $this->textDomain)
            ]
        ]);

        $table->addHooks([
            'story_box_image' => function($args) {
                return '<img width="33" height="33" src="'.$args->story_box_image.'">';
            },
            'status' => function($args) {
                return $args->status ? esc_html__('Active', $this->textDomain) : esc_html__('Passive', $this->textDomain);
            },
            'edit' => function($args) {
                ob_start();
                ?>
                <a href="<?php echo esc_url($this->pageURL . '&edit=' . $args->id); ?>" class="button"><?php echo esc_html__('Edit', $this->textDomain); ?></a>
                <?php
                return ob_get_clean();
            },
            'delete' => function($args) {
                ob_start();
                ?>
                <form action="<?php echo esc_url($this->currentURL()); ?>" method="post">
                    <?php $this->createNewNonceField(); ?>
                    <input type="hidden" name="delete" value="<?php echo $args->id; ?>">
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

            $id = isset($_POST['delete']) ? absint($_POST['delete']) : null;
            if (!$id ) {
                $this->notice('error', esc_html__('Missing parameter!', $this->textDomain), true);
            }

            $result = $this->db->pluginTables->asb->delete(['id' => $id]);

            if (!$result) {
                $this->notice('error', esc_html__('There was a problem deleted the story box!', $this->textDomain), true);
            } else {
                $this->notice('success', esc_html__('Story box deleted successfully.', $this->textDomain), true);
            }
        }
    }
}