<div class='tpio-size-settings'>
    <p>
        <?php
        echo esc_html__('The following image sizes will be optimized  by TP Image Optimizer', 'tp-image-optimizer') . ' <span class="faq-i faq-size"></span><br/>';
        ?>
    </p> 
    <label class='choose-full'><input type="checkbox" name="io-list-size[]" value="full"  <?php if (in_array('full', $optimize_sizes)) : echo "checked";
        endif ?>><b><?php echo esc_html__('Original', 'tp-image-optimizer'); ?></b></label>

    <?php
    if (!empty($sizes)):foreach ($sizes as $size):
            ?>
            <label>
                <input type="checkbox" name="io-list-size[]" value='<?php echo esc_attr($size) ?>'<?php
                if (in_array($size, $optimize_sizes)): echo esc_html("checked");
                endif;
                ?>><?php echo $size ?>
            </label>
            <?php
        endforeach;
    endif;

    echo '<div class="result_alert"></div>';

    submit_button("Update sizes", "button-secondary", "tpio-update-size", false, array("type='submit'"));
    ?>
</div>