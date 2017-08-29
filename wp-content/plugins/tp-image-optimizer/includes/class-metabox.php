<?php
/**
 * METABOX GENERATOR
 * Create metabox
 * 
 * @class TP_Image_Optimizer_Metabox
 * @package TP_Image_Optimizer/Classes
 * @category Class
 * @version 1.0
 * 
 */
if (!defined('TP_IMAGE_OPTIMIZER_BASE')) {
    exit; // Exit if accessed directly
}
if (!class_exists('TP_Image_Optimizer_Metabox')) {

    class TP_Image_Optimizer_Metabox {

        public $image_work;
        private $size_list;

        public function __construct() {
            add_action('tpio_do_metaboxes', array($this, 'add_metabox_setting'));
            add_action('tpio_content_after', array($this, 'sticky_box_show'));
        }

        /**
         * Install metabox
         * 
         * @since 1.0.0
         */
        public function metabox_do_install() {
            tp_image_optimizer_template('panel/install', array(''));
        }

        /**
         * Meta box detail table
         * 
         * @since 1.0.0
         */
        public function metabox_detail() {
            $service = new TP_Image_Optimizer_Service();
            $tb      = new TP_Image_Optimizer_Table();
            $flag    = true;

            $list_img = array();
            $image    = new TP_Image_Optimizer_Image();

            $table = $image->display_table_image();

            if ($table == "nodata") {
                $flag = false;
            }
            tp_image_optimizer_template('panel/detail', array(
                'list_img' => $list_img,
                'table'    => $table,
                'flag'     => $flag
                    )
            );
        }

        /**
         * Metabox top content
         * 
         * @since 1.0.0
         */
        public function metabox_top_box() {
            // CRON WORK
            $check_cron         = get_option('tpio_cron_status');
            $total_image_cron   = 0;
            $current_cron_image = 0;
            $percent_cron       = 0;
            if ($check_cron) {
                $total_image_cron = get_option('tpio_cron_total');
                if ($total_image_cron > 0) {
                    $current_cron_image = get_option('tpio_cron_run');
                    $percent_cron       = ($current_cron_image * 100) / $total_image_cron;
                }
            }

            $optimize_sizes  = get_option('tp_image_optimizer_sizes');
            $this->size_list = explode(",", $optimize_sizes);
            $image           = new TP_Image_Optimizer_Image();
            ?>
            <div class="tp-panel__progress-bar <?php if ($check_cron):echo "active-cron";
            endif;
            ?>">
                <div class="progress_wrap">
                    <div class="progress">
                        <div class="progress-bar">
                            <span class="progress-percent"><?php echo $percent_cron; ?>%</span>
                        </div>
                    </div>
                </div>

            </div>

            <?php
            echo "<div class='top-bar'>";
            // Stastics
            $stastics              = new TP_Image_Optimizer_Stastics();
            $percent_reduced       = number_format($stastics->get_total_percent_reduced(), 2);
            $total_image_with_size = count($this->size_list) * $stastics->get_total_image();

            $data = array(
                'total_current_in_media' => $image->count_attachment_file(),
                'total_file'             => $stastics->get_total_image(),
                'total_uncompress'       => $stastics->get_total_uncompress_img(),
                'total_compressed'       => $stastics->get_total_compressed_img(),
                'percent_reduced'        => $percent_reduced,
                'count_selected_size'    => $total_image_with_size,
            );

            tp_image_optimizer_template('panel/stastics', $data);

            // Action
            $stastics = new TP_Image_Optimizer_Stastics();
            $data     = array(
                'total_file'          => $stastics->get_total_image(),
                'total_error'         => $stastics->get_number_image_error(),
                'total_selected_size' => $stastics->get_total_selected_size(),
                'cron'                => $check_cron
            );
            tp_image_optimizer_template('panel/optimizer', $data);
            echo '</div>';
        }

        /**
         * Metabox size setting
         * 
         * @since 1.0.0
         */
        public function metabox_get_size() {
            $list_img_size = get_intermediate_image_sizes();
            tp_image_optimizer_template('panel/sizes', array('sizes' => $list_img_size, 'optimize_sizes' => $this->size_list));
        }

        /**
         * Setting metabox 
         * 
         * @since 1.0.0
         */
        public function metabox_setting() {

            $option_select   = array(
                1 => esc_attr__('Lower', 'tp-image-optimizer'),
                2 => esc_attr__('Medium', 'tp-image-optimizer'),
                3 => esc_attr__('High (Recommend)', 'tp-image-optimizer'),
                4 => esc_attr__('Very high', 'tp-image-optimizer'),
            );
            $option_compress = get_option('tp_image_optimizer_compress_level');
            $data            = array(
                'option'   => $option_select,
                'compress' => $option_compress
            );
            tp_image_optimizer_template('panel/settings', $data);
        }

        /**
         * Sticky box - Help box to fix error
         * 
         * @since 1.0.0
         */
        public function sticky_box_show() {
            $db = new TP_Image_Optimizer_Table();

            $list_error = $db->get_list_error_image();
            $data       = array(
            );
            tp_image_optimizer_template('sticky-box', $data);
        }

        /*
         * Register form
         * 
         * @since 1.0.6
         */

        public function metabox_register() {
            $data = array();
            tp_image_optimizer_template('panel/register', $data);
        }

        /**
         * Display coupon metabox
         * 
         * @since 1.0.7
         */
        public function metabox_coupon() {
            tp_image_optimizer_template('panel/coupon');
        }

        /**
         * Display account info
         * 
         * @since 1.0.7
         */
        public function metabox_account_info() {
            tp_image_optimizer_template('panel/account');
        }

        /**
         * Register metaboxes
         * @since 1.0.0
         */
        public function add_metabox_setting() {
            add_meta_box('tpio-account_info', __('Account', 'tp-image-optimizer'), array($this, 'metabox_account_info'), null, 'tpio_secondary');
            add_meta_box('tpio-image-stastics', __('Stastics', 'tp-image-optimizer'), array($this, 'metabox_top_box'), null, 'tpio_heading');
            add_meta_box('tpio-image-library', __('Image library', 'tp-image-optimizer'), array($this, 'metabox_detail'), null, 'tpio_content');
            add_meta_box('tpio-quality-settings', __('Quality settings', 'tp-image-optimizer'), array($this, 'metabox_setting'), null, 'tpio_secondary');
            add_meta_box('tpio-size-settings', __('Size settings', 'tp-image-optimizer'), array($this, 'metabox_get_size'), null, 'tpio_secondary');
            add_meta_box('tpio-coupon-settings', __('Coupon', 'tp-image-optimizer'), array($this, 'metabox_coupon'), null, 'tpio_secondary');
        }

    }

}

new TP_Image_Optimizer_Metabox();

