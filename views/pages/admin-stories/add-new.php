<div class="wrap">
    <h1 class="wp-heading-inline">
        <?php echo esc_html__('Add new story', $this->textDomain); ?>
    </h1>
    <hr class="wp-header-end">
    <form action="<?php echo esc_url($this->currentURL()); ?>" method="post">
        <?php $this->createNewNonceField(); ?>
        <input type="hidden" name="add-new" value="1">
        <?php echo $this->view('pages/admin-stories/form'); ?>
    </form><!-- form -->
</div>