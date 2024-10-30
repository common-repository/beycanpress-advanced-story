<div class="wrap">
    <h1 class="wp-heading-inline">
        <?php echo esc_html__('Admin story boxes', $this->textDomain); ?>
    </h1>
    <?php if ($this->db->pluginTables->asb->getCount() >= 5) {
        $this->notice('error', esc_html__('You cannot create a story because you have reached the 5 story box limit.', $this->textDomain));
    } else { ?>
        <a href="<?php echo esc_url($this->pageURL.'&add-new=1'); ?>" class="page-title-action">
            <?php echo esc_html__('Add New', $this->textDomain); ?>
        </a>
    <?php } ?>
    <hr class="wp-header-end">
    <br>
    <?php $table->render(); ?>
</div>