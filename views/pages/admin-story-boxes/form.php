<div id="poststuff">
    <div id="post-body" class="metabox-holder columns-2">

        <div id="post-body-content">
            <div id="titlediv">
                <div id="titlewrap">
                    <input type="text" name="story_box" size="30" id="title" spellcheck="true" autocomplete="off" placeholder="<?php echo esc_html__('Story box name', $this->textDomain); ?>" value="<?php echo isset($storyBox) ? esc_attr($storyBox) : null; ?>" required>
                </div>
            </div>
        </div><!-- #post-body-content -->

        <div id="postbox-container-1" class="postbox-container">
            <div class="postbox">
                <div class="postbox-header">
                    <h2><?php echo isset($_GET['edit']) ? esc_html__('Edit story box', $this->textDomain) : esc_html__('Add story box', $this->textDomain); ?></h2>
                </div>
                <div class="inside">
                    <div class="minor-publishing">

                        <?php if (isset($_GET['edit'])) { ?>
                            <p class="post-attributes-label-wrapper page-template-label-wrapper">
                                <label class="post-attributes-label">
                                    <?php echo esc_html__('Status', $this->textDomain); ?>
                                </label>
                            </p>
                            <select name="status" class="widefat">
                                <option value="1" <?php echo isset($status) && $status == 1 ? 'selected' : null ?>>
                                    <?php echo esc_html__('Active', $this->textDomain); ?>
                                </option>
                                <option value="0" <?php echo isset($status) && $status == 0 ? 'selected' : null ?>>
                                    <?php echo esc_html__('Passive', $this->textDomain); ?>
                                </option>
                            </select>
                        <?php } ?>

                        <div class="minor-publishing-actions" style="padding:10px 0 0 0">
                            <button type="submit" class="button button-primary">
                                <?php echo isset($_GET['edit']) ? esc_html__('Edit story box', $this->textDomain) : esc_html__('Add story box', $this->textDomain); ?>
                            </button>
                            <div class="clear"></div>
                        </div><!-- .minor-publishing-actions -->

                    </div><!-- .minor-publishing -->
                </div><!-- .inside -->
            </div><!-- .postbox -->
        </div><!-- #postbox-container-1 -->

        <div id="postbox-container-2" class="postbox-container">
            <div class="postbox">
                <div class="postbox-header">
                    <h2><?php echo esc_html__('Story box image', $this->textDomain); ?></h2>
                </div>
                <div class="inside">
                    <div class="bpstory-container" style="display: flex; align-items:center">
                        <img id="story_box_image_preview" width="66" height="66" 
                        <?php echo isset($storyBoxImage) ? 'src="'.esc_url($storyBoxImage).'"' : null; ?>>
                        <input type="hidden" name="story_box_image" id="story_box_image" value="<?php echo isset($storyBoxImage) ? esc_url($storyBoxImage) : null; ?>">
                        <button type="button" class="button bpstory-select-story-box-image">
                            <?php echo esc_html__('Select image', $this->textDomain); ?>
                        </button>
                    </div>
                </div><!-- .inside -->
            </div><!-- .postbox -->
        </div><!-- .postbox-container-2 -->

    </div><!-- #bost-body -->
    <div class="clear"></div>
</div><!-- #poststuff -->