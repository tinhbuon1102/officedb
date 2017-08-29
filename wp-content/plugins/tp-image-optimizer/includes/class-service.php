<?php

if (!defined('TP_IMAGE_OPTIMIZER_BASE')) {
    exit; // Exit if accessed directly
}

/**
 * SERVICE COMPRESS
 * Provide featured to request optimize service.
 * 
 * @class TP_Image_Optimizer_Service
 * @package TP_Image_Optimizer/Classes
 * @category Class
 * @version 1.0
 */
if (!class_exists('TP_Image_Optimizer_Service')) {

    class TP_Image_Optimizer_Service {

        /**
         * Address of service 
         * 
         * @type String 
         */
        public static $service;

        /**
         * Info of website, used to validate action
         * 
         * @var Object 
         */
        public static $authentication;

        /**
         * @var string User Token
         */
        public static $token;

        /**
         * @var int Compress Level
         */
        public static $compress_level;

        /**
         *  Main address of service
         * 
         * @var String
         */
        public $api_main;

        public static function init() {
            add_action('tpio_process_optimize', array(__CLASS__, 'process_optimize'), 10);
            add_action('wp_ajax_cancel_cronjob', array(__CLASS__, 'cancel_optimize'), 10);

            TP_Image_Optimizer_Service::$service = "http://api.themespond.com/api/v1/io/";
            TP_Image_Optimizer_Service::$token   = get_option('tp_image_optimizer_token');

            $authentication                             = array(
                'token' => TP_Image_Optimizer_Service::$token
            );
            $authentication                             = json_encode($authentication);
            TP_Image_Optimizer_Service::$authentication = base64_encode($authentication);
            TP_Image_Optimizer_Service::$compress_level = get_option('tp_image_optimizer_compress_level');
        }

        public function __construct() {
            $this->api_main = "http://api.themespond.com/io/";
        }

        public function __get($name) {
            return $this->$name;
        }

        /**
         * Get token from server
         * Update token key to WP Option
         * 
         * @category Ajax
         * @since 1.0.0
         */
        public function get_token() {

            $token = get_option('tp_image_optimizer_token');

            // Check key exist
            if (($token != false) && (strlen($token) == 35 )) {
                $data['log'] = esc_html__('Detect token of service has already created before !', 'tp-image-optimizer');
                wp_send_json_success($data);
            }

            $url = TP_Image_Optimizer_Service::$service . "request";

            $data = array(
                'timeout' => 300,
                'body'    => array(
                    'action' => 'request_token'
                )
            );

            $response = wp_remote_post($url, $data);

            $status_code = wp_remote_retrieve_response_code($response);

            if ($status_code == 200) {
                $response = wp_remote_retrieve_body($response);
                $response = json_decode($response);

                if (isset($response->key)) {
                    update_option('tp_image_optimizer_token', $response->key);
                    wp_send_json_success($response);
                }

                wp_send_json_error(esc_html__('Cannot get token key, some thing error was happened.', 'tp-image-optimizer'));
            }

            wp_send_json_error(esc_html__('Service cannot established.', 'tp-image-optimizer'));
        }

        /**
         * Get Stastics from ThemesPond service
         * 
         * @category Ajax
         * @since 1.0.0
         */
        public function get_stastics() {

            // Get cache 
            $data = get_transient('tp_image_optimizer_stastics_service');

            if (!empty($data)) {
                wp_send_json_success($data);
            }

            // If no cache or expired
            $url = TP_Image_Optimizer_Service::$service . 'stastics';

            $data = array(
                'timeout' => 300,
                'body'    => array(
                    'action'         => 'request_stastics',
                    'authentication' => TP_Image_Optimizer_Service::$authentication
                )
            );

            $response = wp_remote_post($url, $data);

            $status_code = wp_remote_retrieve_response_code($response);

            if ($status_code == 404 || $status_code == 500 || !$status_code) {
                wp_send_json_error(esc_html__("Service cannot established.", 'tp-image-optimizer'));
            }

            $response = wp_remote_retrieve_body($response);

            $response = json_decode($response);

            if (!empty($response->success) && $response->success == true && !empty($response->data)) {
                //set_transient( 'tp_image_optimizer_stastics_service', $response, 24 * 60 * 60 );
                wp_send_json_success($response);
            }
            else {
                delete_option('tp_image_optimizer_token');
                $this->get_token();
            }

            wp_send_json_error(esc_html__('Oops! Unexpected error from service.', 'tp-image-optimizer'));
        }

        /**
         * Get token by email
         * 
         * @category Ajax
         * @since 1.0.6
         */
        public function register_by_mail() {
            $email = htmlentities($_POST['email']);

            $url    = $this->api_main . 'api/register';
            $authen = array(
                'email' => $email,
            );

            $authen = base64_encode(json_encode($authen));
            $data   = array(
                'body' => array(
                    'action'         => 'register',
                    'authentication' => $authen
                ),
            );

            $response = wp_remote_post($url, $data);
            $response = wp_remote_retrieve_body($response);
            wp_send_json_success($response);
        }

        /**
         * Running optimize image as background service
         * 
         * 
         * @since 1.0.7
         */
        public function cronjob_optimize_progress() {
            $force = $_POST['force'];

            update_option("tpio_cron_status", 1);
            wp_clear_scheduled_hook('tpio_process_optimize');

            if (!wp_next_scheduled('tpio_process_optimize')) {
                wp_schedule_single_event(time() + 1, 'tpio_process_optimize', array($force));
            }
            wp_die();
        }

        /**
         * Image process cronjob
         * 
         * @param boolean $force Force optimize
         * @return type
         * 
         * @since 1.0.0
         */
        public static function process_optimize($force) {
            global $wpdb;
            // Lock cron
            $lock_cron = _get_cron_lock();
            update_option("tp_image_optimizer_error", 0);
            update_option("tpio_current_cron", $lock_cron);
            update_option("tpio_force", $force);
            // Clear cache stastics
            delete_transient('tp_image_optimizer_stastics_service');
            $db_table  = new TP_Image_Optimizer_Table();

            /**
             * MULTI OPTIMIZER
             */
            // Remove cache
            $total_image = $db_table->count_optimize_image($force);
            update_option("tpio_cron_total", $total_image['total']);

            $error_count = 0;
            // Get list image size
            $list_size   = get_option('tp_image_optimizer_sizes');
            $list_size   = preg_split("/[\s,]+/", $list_size);

            update_option("tpio_cron_status", 1);
            if ($force) {
                update_option("tpio_cron_run", 0);
            }
            else {
                update_option("tpio_cron_run", ($total_image['compressed']));
            }
            update_option("tpio_cron_count", $total_image['count']);

            update_option("tpio_cron_compressed", $total_image['compressed']);
            for ($number = 0; $number < $total_image['count'] + 1; $number++) {
                update_option("tpio_cron_number", $number);
                // Update current running
                if (!$force) {
                    update_option("tpio_cron_run", $number + $total_image['compressed']);
                }
                else {
                    update_option("tpio_cron_run", $number);
                }
                // Result compress
                $result = array();

                $attachment_id = $db_table->get_pre_optimize_image($number, $force, $error_count);
                $check_error   = 0;
                foreach ($list_size as $size_name) {
                    // Check cronjob running
                    $query                 = $wpdb->prepare("SELECT `option_value` FROM $wpdb->options WHERE option_name = %s", 'tpio_cron_status');
                    $check_cronjob_running = $wpdb->get_row($query, OBJECT);
                    if (!$check_cronjob_running->option_value) {
                        // STOP COMPRESS
                        TP_Image_Optimizer_Service::cancel_optimize();
                        return;
                    }

                    $rs = TP_Image_Optimizer_Service::request_service($attachment_id, $size_name);
                    if (isset($rs['success']) && ($rs['success'] != 1)) {
                        $result['success'] = false;
                        $result['log']     = $rs['error_log'];
                        $db_table->update_status_for_attachment($attachment_id, "full", "error");

                        $check_error = $check_error + 1;
                        update_option("tp_image_optimizer_error", $error_count);
                        update_option('tpio_cron_last_compress_status', false);
                    }
                    else {
                        $result['success'] = true;
                        $result['url']     = wp_get_attachment_thumb_url($attachment_id);

                        // Set stastus for flag to exclude this attachment id from pre-optimize list
                        if (($rs['size'] == 'full') && (isset($rs['compressed'])) && ( $rs['compressed'] == true)) {
                            $result['full_detail'] = $rs;
                        }
                    }
                }

                // Check error
                if ($check_error > 0) {
                    update_option('tpio_cron_image_last_error_log', $result['log']);
                    $error_count = $error_count + 1;
                    update_option('tp_image_optimizer_error', $error_count);
                    $success     = false;
                    update_option('tpio_cron_image_result_success', '');
                }
                else {
                    $success = true;
                    $db_table->update_status_for_attachment($attachment_id, 'full', "optimized");
                    update_option('tpio_cron_last_compress_status', true);
                    $result  = json_encode($result['full_detail']);
                    update_option('tpio_cron_image_result_success', $result);
                }
                // Update success result for cronjob
                update_option('tpio_cron_image_done', $attachment_id);

                // COMPRESS DONE
                if ($number == ($total_image['count'] - 1)) {
                    update_option('tpio_cron_last_result_success', $result);
                    update_option('tpio_cron_last_optimizer', $attachment_id);
                }
                if ($number == $total_image['count']) {
                    TP_Image_Optimizer_Service::cancel_optimize();
                    return;
                }
            }
        }

        /**
         * Request ThemesPond compress service
         * Send image to optimize by ThemesPond compress service
         * 
         * @category Ajax
         * @param double $id - ID of attachment image
         * @param string $size - Size of attachment will be optimized
         * @param boolean $for_validate Use when validate API key
         * 
         * @return string  - Data for display notification
         * @throws Exception
         * @since 1.0.0
         */
        public static function request_service($attachment_id = '', $size_name = 'full') {
            $db_table = new TP_Image_Optimizer_Table();
            $service  = TP_Image_Optimizer_Service::$service . 'compress';

            // Data return to debug
            $data_return   = array(
                'id'      => $attachment_id,
                'success' => false,
                'log'     => '',
                'size'    => $size_name
            );
            $file_size_old = 0;

            $image_file        = tp_image_optimizer_scaled_image_path($attachment_id, $size_name);
            $file_size_old     = filesize($image_file);
            $check_image_on_db = $db_table->check_image_size_on_db($attachment_id, $size_name);

            if (!$check_image_on_db && $file_size_old > 0) {
                $db_table->assign_attachment_to_io($attachment_id, $size_name);
            }
            // If image removed before optimizer
            if (!file_exists($image_file)) {
                $data_return['old_size']  = $file_size_old;
                $data_return['new_size']  = $file_size_old;
                $data_return['success']   = true;
                $data_return['error_log'] = esc_html__("404 error: This attachment image (original image or cropped image by WordPress) has been existing in Database, but removed.", "tp-image-optimizer");
                // Attachment image has been deleted, need remove this ID from IO Database Table
                //$db_table->remove_deleted_attachment_image($attachment_id);
                return $data_return;
            }

            // Image is too small
            if (filesize($image_file) < 5120) {
                $data_return['old_size'] = $file_size_old;
                $data_return['new_size'] = $file_size_old;
                $data_return['success']  = true;
                $data_return['log']      = esc_html__("Image is too small", "tp-image-optimizer");
                return $data_return;
            }
            // Validate Image Type
            if (!wp_attachment_is_image($attachment_id)) {
                $data_return['success']   = false;
                $data_return['error_log'] = esc_html__("This attachment isn't image type", 'tp-image-optimizer');
                // Remove this ID from IO Database Table
                $db_table->remove_deleted_attachment_image($attachment_id);
                return $data_return;
            }

            // Reject unsupported image type    
            $image_type = get_post_mime_type($attachment_id);
            if (($image_type != 'image/png' ) && ($image_type != 'image/jpeg')) {
                $data_return['success']   = false;
                $image_type               = strtoupper(str_replace('image/', '', $image_type));
                $data_return['error_log'] = esc_html__(sprintf("%s isn't support at this time", $image_type), 'tp-image-optimizer');
                return $data_return;
            }

            $data         = array(
                'headers' => array(
                    'authorization'  => TP_Image_Optimizer_Service::$authentication,
                    'compress-level' => TP_Image_Optimizer_Service::$compress_level,
                    'accept'         => 'application/json', // The API returns JSON
                    'content-type'   => 'application/binary', // Set content type to binary
                    'image-type'     => $image_type
                ),
                'timeout' => 450,
            );
            $data['body'] = file_get_contents($image_file);

            // Send to service
            $response    = wp_remote_post($service, $data);
            $status_code = wp_remote_retrieve_response_code($response);

            if ($status_code != 200) {
                wp_send_json(array(
                    'success' => false,
                    'data'    => esc_html__('Cannot connect to service.', 'tp-image-optimizer'),
                    'status'  => 404)
                );
            }
            $response = wp_remote_retrieve_body($response);

            if (($response == '') || ($response == 'false')) {
                wp_send_json_error(esc_html__('There is no Internet connection!', 'tp-image-optimizer'));
            }

            /*             * ****************************************   
             * VALIDATE DATA RESPONSE IS IMAGE or NOT *
             * *************************************** */

            // Condition 1 : Service return error
            $check  = isJSON($response);
            // If $check == true, it mean server return an error
            // Condition 2 : Unexpected error
            $check2 = false;

            if ((strpos($response, 'something went wrong') !== false) || (strpos($response, '<html>') !== false)) {
                $check2 = true;
            }
            if (!$check && !$check2) {
                $origin_path     = tp_image_optimizer_scaled_image_path($attachment_id, $size_name);
                /**
                 *  Replace original attachment image by optimized file
                 *  Override original image by response image from PostImage Service
                 */
                $img_origin_load = @fopen($origin_path, "w");
                $result_write_file = fwrite($img_origin_load, $response);
                // Result
                $data_return['old_size']   = $file_size_old;
                $data_return['new_size']   = $result_write_file;
                $data_return['success']    = true;
                $data_return['log']        = esc_html__("Succcess optimizer #", 'tp-image-optimizer') . $attachment_id . ' - ' . $size_name;
                $data_return['compressed'] = true;
                // Update current size after optimized
                $db_table->update_current_size_for_attachment($attachment_id, $size_name, $result_write_file);

                // Total current
                $total_current_size = get_option('tp_image_optimizer_total_current_size');

                // Caculator new size
                $save_size = $file_size_old - $result_write_file;
                update_option('tp_image_optimizer_total_current_size', $total_current_size - $save_size);
                update_option('tp_image_optimizer_image_compressed', $attachment_id);

                return $data_return;
            }
            else {
                if ($check2) {
                    $data_return['success']   = false;
                    $data_return['error_log'] = esc_html__("Unexpected error!", 'tp-image-optimizer');
                    return $data_return;
                }
                /**
                 * SERVER RETURN JSON LOG
                 * Catch error
                 */
                $error_data = json_decode($response);

                if ($error_data->status == 400) {
                    $data_return['old_size'] = $file_size_old;
                    $data_return['new_size'] = $file_size_old;
                    $data_return['log']      = esc_html__("Succcess optimizer #", 'tp-image-optimizer') . $attachment_id . ' - ' . $size_name;
                    $data_return['success']  = true;

                    update_option('tp_image_optimizer_image_compressed', $attachment_id);
                    return ($data_return);
                }

                // Logging
                $data_return['error_log'] = $error_data->error;
                $data_return['success']   = false;
                return ($data_return);
            }
            $data_return['success']   = false;
            $data_return['error_log'] = esc_html__("Unexpected error!", 'tp-image-optimizer');

            return $data_return;
        }

        /**
         * Cancel cronjob
         * 
         * @since 1.0.8
         */
        public static function cancel_optimize() {
            $response = update_option("tpio_cron_status", 0);
            delete_option('tpio_cron_id');
            delete_option('tpio_cron_run');
            delete_option('tpio_cron_total');

            $check_running_cronjob = get_option('tpio_cron_status');
            wp_clear_scheduled_hook('tpio_process_optimize');
            $doing_cron            = get_transient('doing_cron');
            $lock_cron             = get_option('tpio_current_cron');
            if ($lock_cron == $doing_cron) {
                delete_transient('doing_cron');
            }
            delete_option('tpio_current_cron');

            sleep(5); // Time to clear cronjob
            wp_send_json($response);
        }

        /**
         * Manual compress 
         * 
         * @since 1.0.8
         */
        public function manual_optimize_progress() {
            $attachment_id = esc_html($_POST['id']);

            $list_size = get_option('tp_image_optimizer_sizes');
            $list_size = preg_split("/[\s,]+/", $list_size);
            $result    = array();
            $error     = 0;
            foreach ($list_size as $size) {
                $rs                = TP_Image_Optimizer_Service::request_service($attachment_id, $size);
                $result['success'] = true;
                if (!$rs['success']) {
                    $result['log'] = $rs['error_log'];
                    $error         = $error + 1;
                }
                else {
                    if (($rs['size'] == 'full') && (isset($rs['compressed'])) && ( $rs['compressed'] == true)) {
                        $result['full_detail'] = $rs;
                    }
                }
            }
            if ($error > 0) {
                $result['number_error'] = $error;
                $result['success']      = false;
                wp_send_json_error($result);
            }
            wp_send_json_success($result);
        }

        /**
         * Clear when cronjob done
         * 
         * @sicne 1.0.8
         */
        public function clear_when_cronjob_done() {
            delete_option('tpio_cron_image_done');
            delete_option('tpio_cron_image_result_success');
            delete_option('tpio_cron_last_optimizer');
            delete_option('tpio_cron_last_compress_status');
            delete_option('tpio_cron_last_result_success');
            wp_send_json_success();
        }

    }

}
TP_Image_Optimizer_Service::init();


