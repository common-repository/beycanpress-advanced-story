<?php defined('ABSPATH') || exit;

if (class_exists('CSF')) :

    $prefix = BPSTORY['settingsName'];
    $textDomain = BPSTORY['textDomain'];
    
    CSF::createOptions($prefix, array(

        'framework_title'         => esc_html__('Settings', $textDomain) . ' <small>By BeycanPress</small>',

        // menu settings
        'menu_title'              => esc_html__('Settings', $textDomain),
        'menu_slug'               => 'bpstory-settings',
        'menu_capability'         => 'manage_options',
        'menu_type'               => 'submenu',
        'menu_parent'             => 'bpstory-admin-story-boxes',
        'menu_position'           => null,
        'menu_hidden'             => false,

        // menu extras
        'show_bar_menu'           => false,
        'show_sub_menu'           => false,
        'show_network_menu'       => true,
        'show_in_customizer'      => false,

        'show_search'             => false,
        'show_reset_all'          => false,
        'show_reset_section'      => false,
        'show_footer'             => true,
        'show_all_options'        => true,
        'sticky_header'           => true,
        'save_defaults'           => true,
        'ajax_save'               => true,
        
        // database model
        'transient_time'          => 0,

        // contextual subtitle
        'contextual_subtitle'         => array(),

        // typography options
        'enqueue_webfont'         => false,
        'async_webfont'           => false,

        // others
        'output_css'              => false,

        // theme
        'theme'                   => 'dark',

        // external default values
        'defaults'                => array(),

    ));

    CSF::createSection($prefix, array(

        'id'     => 'general_options', 
        'title'  => esc_html__('General options', $textDomain),
        'icon'   => 'fa fa-cog',
        'fields' => array(
            array(
                'type'    => 'content',
                'content' => '<a href="https://1.envato.market/eO0yz" title="Buy Premium"><img src="'.esc_url(BPSTORY['url']).'assets/img/buy-now-premium.png" alt="Buy Premium" style="max-width: 50%"></a>
                
                ',
                'title' => esc_html__('Buy now', $textDomain)
            ),
            array(
                'id'      => 'data_deletion_status',
                'title'   => esc_html__('Data deletion status', $textDomain),
                'type'    => 'switcher',
                'default' => false,
                'subtitle'    => esc_html__('This setting is passive come by default. You enable this setting. All data created by the plug-in will be deleted while removing the plug-in.', $textDomain)
            ),
            array(
                'id'      => 'transition_time',
                'title'   => esc_html__('Default transition time (in sec)', $textDomain),
                'type'    => 'content',
                'content' => '
                <h2>This field is only available in premium version.</h2>

                
                ',
                'subtitle'    => esc_html__('Default transition time for stories. (If the story is a video, the transition time is automatically set to the video duration)', $textDomain)
            ),
            array(
                'id'      => 'auto_refresh_time',
                'title'   => esc_html__('Auto refresh time (in min)', $textDomain),
                'type'    => 'content',
                'content' => '
                <h2>This field is only available in premium version.</h2>

                
                ',
                'subtitle'    => esc_html__('Time to automatically refresh the story list when the page remains open.', $textDomain)
            ),
            array(
                'id'      => 'show_story_list_in_center',
                'title'   => esc_html__('Show story list in center', $textDomain),
                'type'    => 'content',
                'content' => '
                <h2>This field is only available in premium version.</h2>

                
                ',
                'subtitle'    => esc_html__('If you choose this, the story list will start from the center instead of starting from the left, that is, it will be centered.', $textDomain)
            ),
        )
    ));

    CSF::createSection($prefix, array(
        'id'     => 'user_stories', 
        'title'  => esc_html__('User stories', $textDomain),
        'icon'   => 'fa fa-user',
        'fields' => array(
            array(
                'type'    => 'content',
                'content' => '<a href="https://1.envato.market/eO0yz" title="Buy Premium"><img src="'.esc_url(BPSTORY['url']).'assets/img/buy-now-premium.png" alt="Buy Premium" style="max-width: 50%"></a>
                
                ',
                'title' => esc_html__('Buy now', $textDomain)
            ),
            array(
                'id'      => 'user_stories_only_registered_users_visible',
                'title'   => esc_html__('User stories only registered users visible', $textDomain),
                'type'    => 'content',
                'content' => '
                <h2>This field is only available in premium version.</h2>

                
                ',
                'subtitle'    => esc_html__('If true, Someone who is not a member or not logged in to your site cannot see user stories.', $textDomain)
            ),
            array(
                'id'      => 'only_buddypress_friends',
                'title'   => esc_html__('Show only BuddyPress friends\' stories', $textDomain),
                'type'    => 'content',
                'content' => '
                <h2>This field is only available in premium version.</h2>

                
                ',
                'subtitle'    => esc_html__('If this setting is turned on, a user can only see the stories of people they are friends with.', $textDomain)
            ),
            array(
                'id'      => 'user_stories_story_box_redirect_url',
                'title'   => esc_html__('Story box redirect URL', $textDomain),
                'type'    => 'content',
                'content' => '
                <h2>This field is only available in premium version.</h2>

                
                ',
                'subtitle'    => esc_html__('When viewing the stories, click on the profile above and choose where to direct it.', $textDomain)
            ),
            array(
                'id'      => 'max_file_size',
                'title'   => esc_html__('Maximum file size (in MB)', $textDomain),
                'type'    => 'content',
                'content' => '
                <h2>This field is only available in premium version.</h2>

                
                ',
                'subtitle'    => esc_html__('Maximum file size you will allow to be uploaded.', $textDomain)
            ),
            array(
                'id'      => 'supported_media_types',
                'title'   => esc_html__('Supported media types', $textDomain),
                'type'    => 'content',
                'content' => '
                <h2>This field is only available in premium version.</h2>

                
                ',
                'subtitle'    => esc_html__('File types you will allow to be uploaded. (Separate with comma)', $textDomain)
            ),
            array(
                'id'      => 'buddypress_my_stories',
                'title'   => esc_html__('BuddyPress Stories page', $textDomain),
                'type'    => 'content',
                'content' => '
                <h2>This field is only available in premium version.</h2>

                
                ',
                'subtitle'    => esc_html__('BuddyPress, Stories page open/close.', $textDomain)
            ),
            array(
                'id'      => 'bbpress_my_stories',
                'title'   => esc_html__('bbPress Stories space', $textDomain),
                'type'    => 'content',
                'content' => '
                <h2>This field is only available in premium version.</h2>

                
                ',
                'subtitle'    => esc_html__('bbPress, Stories space open/close.', $textDomain)
            ),
        ) 
    ));

    CSF::createSection($prefix, array(
        'id'     => 'post_and_product_stories', 
        'title'  => esc_html__('Post & Product stories', $textDomain),
        'icon'   => 'fa fa-copy',
        'fields' => array(
            array(
                'type'    => 'content',
                'content' => '<a href="https://1.envato.market/eO0yz" title="Buy Premium"><img src="'.esc_url(BPSTORY['url']).'assets/img/buy-now-premium.png" alt="Buy Premium" style="max-width: 50%"></a>
                
                ',
                'title' => esc_html__('Buy now', $textDomain)
            ),
            array(
                'id'      => 'default_category_image',
                'title'   => esc_html__('Default category image', $textDomain),
                'type'    => 'content',
                'content' => '
                <h2>This field is only available in premium version.</h2>

                
                ',
                'subtitle'    => esc_html__('This will appear if there is no picture of the category when the posts are listed as stories.', $textDomain)
            ),
            array(
                'id'      => 'default_media_url',
                'title'   => esc_html__('Default media', $textDomain),
                'type'    => 'content',
                'content' => '
                <h2>This field is only available in premium version.</h2>

                
                ',
                'subtitle'    => esc_html__('Appears if you haven\'t selected a featured image and story media when adding posts and products.', $textDomain)
            ),
            array(
                'id'      => 'story_limit',
                'title'   => esc_html__('Story limit', $textDomain),
                'type'    => 'content',
                'content' => '
                <h2>This field is only available in premium version.</h2>

                
                ',
                'subtitle'    => esc_html__('The maximum number of products and posts to appear as stories in a category.', $textDomain)
            ),
        ) 
    ));

    CSF::createSection($prefix, array(
        'id'     => 'backup', 
        'title'  => esc_html__('Backup', $textDomain),
        'icon'   => 'fa fa-shield',
        'fields' => array(
            array(
                'type'    => 'content',
                'content' => '<a href="https://1.envato.market/eO0yz" title="Buy Premium"><img src="'.esc_url(BPSTORY['url']).'assets/img/buy-now-premium.png" alt="Buy Premium" style="max-width: 50%"></a>
                
                ',
                'title' => esc_html__('Buy now', $textDomain)
            ),
            array(
                'type'    => 'content',
                'content' => '
                <h2>This field is only available in premium version.</h2>

                
                ',
                'title' => esc_html__('Backup', $textDomain)
            ),
        ) 
    ));
    
endif;