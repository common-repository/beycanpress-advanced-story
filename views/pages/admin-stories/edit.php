<div class="wrap">
    <h1 class="wp-heading-inline">
        <?php echo esc_html__('Story edit', $this->textDomain); ?>
    </h1>
    <hr class="wp-header-end">
    <form action="<?php echo esc_url($this->currentURL()); ?>" method="post">
        <?php $this->createNewNonceField(); ?>
        <input type="hidden" name="edit" value="1">
        <input type="hidden" name="story_id" value="<?php echo esc_attr($_GET['edit']); ?>">
        <?php echo $this->view('pages/admin-stories/form', [
            'storyBoxId' => $story->story_box_id,
            'mediaURL' => $story->media_url,
            'externalURL' => $story->external_url,
            'showUntilDate' => $story->show_until_date,
            'status' => $story->status,
        ]); ?>
    </form><!-- form -->
</div>