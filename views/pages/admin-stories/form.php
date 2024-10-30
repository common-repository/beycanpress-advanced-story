<div id="poststuff">
    <div id="post-body" class="metabox-holder columns-2">

        <div id="postbox-container-1" class="postbox-container">
            <div class="postbox">
                <div class="postbox-header">
                    <h2><?php echo isset($_GET['edit']) ? esc_html__('Edit story', $this->textDomain) : esc_html__('Add story', $this->textDomain); ?></h2>
                </div>
                <div class="inside">
                    <div class="minor-publishing">

                    
                        <p class="post-attributes-label-wrapper page-template-label-wrapper">
                            <label class="post-attributes-label">
                                <?php echo esc_html__('Story box', $this->textDomain); ?>
                            </label>
                        </p>
                        <?php 
                            $storyBoxes = $this->db->pluginTables->asb->findAll();
                        ?>
                        <select name="story_box_id" class="widefat">
                            <?php foreach ($storyBoxes as $key => $value) { ?>
                                <option value="<?php echo esc_attr($value->id); ?>" <?php echo isset($storyBoxId) && $storyBoxId == $value->id ? 'selected' : null ?>>
                                    <?php echo esc_html($value->story_box); ?>
                                </option>
                            <?php } ?>
                        </select>

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

                        <br><br>
                        <a href="https://1.envato.market/eO0yz" title="Buy Premium"><img src="<?php echo esc_url(BPSTORY['url']); ?>assets/img/buy-now-premium.png" alt="Buy Premium" style="max-width: 100%"></a>

                        <div class="minor-publishing-actions" style="padding:10px 0 0 0">
                            <button type="submit" class="button button-primary">
                                <?php echo isset($_GET['edit']) ? esc_html__('Edit story', $this->textDomain) : esc_html__('Add story', $this->textDomain); ?>
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
                    <h2><?php echo esc_html__('Story properties', $this->textDomain); ?></h2>
                </div>
                <div class="inside">
                    <p class="post-attributes-label-wrapper page-template-label-wrapper">
                        <label class="post-attributes-label">
                            <?php echo esc_html__('Show until date: (If you leave it blank it will always appear.)', $this->textDomain); ?>
                        </label>
                    </p>
                    <h2>This field is only available in premium version.</h2>

                    <p class="post-attributes-label-wrapper page-template-label-wrapper">
                        <label class="post-attributes-label">
                            <?php echo esc_html__('External URL:', $this->textDomain); ?>
                        </label>
                    </p>
                    <h2>This field is only available in premium version.</h2>
                    
                    <p class="post-attributes-label-wrapper page-template-label-wrapper">
                        <label class="post-attributes-label">
                            <?php echo esc_html__('Media URL: (Compulsory)', $this->textDomain); ?>
                        </label>
                    </p>
                    <input type="url" class="widefat bpstory-media-url" name="media_url" value="<?php echo isset($mediaURL) ? $mediaURL : null; ?>" required>
                    <button type="button" class="button bpstory-select-story-media" style="margin-top: 10px">
                        <?php echo esc_html__('Select story media', $this->textDomain); ?>
                    </button>
                </div><!-- .inside -->
            </div><!-- .postbox -->
        </div><!-- .postbox-container-2 -->

    </div><!-- #bost-body -->
    <div class="clear"></div>
</div><!-- #poststuff -->