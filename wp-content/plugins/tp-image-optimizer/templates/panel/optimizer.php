<div class='io-optimizer-wrapper'>
    <div class='io-notify-group <?php if ($cron): echo "active";endif;?>'>
        <ul>
            <li>
                <p class='io-label-process-bar'> 
                    <?php print esc_html__("Processing ", 'tp-image-optimizer'); ?>
                </p>
                <p class='optimized-number'>0</p> <p>/</p> <p class='total-number'><?php echo esc_html($total_file); ?></p>
            </li>

            <li><p class=""> <?php print esc_html__("Optimized", 'tp-image-optimizer'); ?></p></p>
                <p class="compressed-image" data-number-selected-size="<?php echo esc_html($total_selected_size); ?>">0</p> / <p class="total-compressed-images">0</p> <?php print esc_html__("images", 'tp-image-optimizer'); ?>
            </li>
            <li><p><?php print esc_html__("Error ", 'tp-image-optimizer'); ?></p>  <p class='io-error'><?php echo esc_html($total_error); ?></p></li>
        </ul>
        <div class="io-show-log"><?php print esc_html__("Getting Started ...", 'tp-image-optimizer'); ?> </div>
    </div>
    
    <div class='keep_original'>
        <label class='original_label'><input type="checkbox" name="keep-original" id="io-keep-original" class='fa-original'>
            <?php echo esc_html__('Compress original image', 'tp-image-optimizer'); ?>
            <span class="faq-i faq-original"></span>
        </label>
    </div>
    
    <label><input type="checkbox" name="force-re-optiomizer" id="io-reoptimized"> <?php echo esc_html__('Force Re-Optimize', 'tp-image-optimizer'); ?>  <span class="faq-i faq-force fa-force"></span></label>

    <?php wp_nonce_field("tp_image_optimizer_key_img", "img_key_ajax"); ?>
    <?php wp_nonce_field('auto_data_nonce', 'set_auto_key'); ?>
    <div class='submit-optimizer'>
        <button type="submit" name="optimizer_btn" id="optimizer_btn" class="button button-primary <?php if (!$cron): echo "is-active";
    endif;
    ?>">
<?php echo esc_html__("One click optimize ", 'tp-image-optimizer'); ?>
        </button>
        <input type="button" name="cancel_btn" id="cancel_optimizer" class="button cancel_optimizer <?php if ($cron): echo "is-active";
endif;
?>" value="<?php echo esc_html__("STOP ", 'tp-image-optimizer'); ?>">
    </div>


    <div class='io_optimizer_setting'></div>

</div>
