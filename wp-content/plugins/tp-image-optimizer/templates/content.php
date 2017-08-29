<div id='tp-image-optimizer' class="tp-image-optimizer io-detail-page wrap" data-process='false'  data-total='<?php echo esc_html($total_image); ?>'>

    <h1 class="wp-heading-inline"><?php echo esc_html($title); ?></h1>

    <?php do_action('tpio_do_metaboxes'); ?>

    <div id="poststuff">

        <div class='content'>
            <div class="tp-io-notice-bar">
                <?php if ($cron) : ?>
                    <div class="notice notice-success is-dismissible">
                        <p>
                            <?php echo esc_html__("TP Image Optimizer will still auto-optimize all your images, even you close this window.", 'tp-image-optimizer'); ?>
                        </p> 
                    </div>
                <?php endif; ?>
            </div>
            <div class='io-top-panel'>
                <div class='panel-settings'>
                    <?php do_meta_boxes(null, 'tpio_heading', array()); ?>
                </div>
            </div>

        </div>

        <div id="post-body" class="metabox-holder columns-2">

            <div id="post-body-content">

                <div class='panel_stastics'>
                    <?php do_meta_boxes(null, 'tpio_content', array()); ?>
                </div>
            </div>

            <div id="postbox-container-1" class="tpio_secondary postbox-container">
                <?php do_meta_boxes(null, 'tpio_secondary', array()); ?>
            </div>

        </div>

        <br class="clear">

    </div>
</div>

<?php do_action('tpio_content_after'); ?>
