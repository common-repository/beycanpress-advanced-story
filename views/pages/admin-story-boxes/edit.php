<div class="wrap">
    <h1 class="wp-heading-inline">
        <?php echo esc_html__('Story box edit', $this->textDomain); ?>
    </h1>
    <hr class="wp-header-end">
    <form action="<?php echo esc_url($this->currentURL()); ?>" method="post">
        <?php $this->createNewNonceField(); ?>
        <input type="hidden" name="edit" value="1">
        <input type="hidden" name="id" value="<?php echo esc_attr($_GET['edit']); ?>">
        <?php echo $this->view('pages/admin-story-boxes/form', [
            'storyBox' => $storyBox->story_box,
            'storyBoxImage' => $storyBox->story_box_image,
            'status' => $storyBox->status
        ]); ?>
    </form><!-- form -->
</div>