(function ($) {
    $(document).on('ready', function () {
// Optimizer box
        function Optimizer() {
            this.$wrapper = $(".io-optimizer-wrapper");
            // Button
            this.$optimize_btn = this.$wrapper.find('#optimizer_btn');
            this.$update_image_btn = this.$wrapper.find("#update-image");
            this.$spinner = this.$wrapper.find('.spinner');
            // Checkbox 
            this.$force_checkbox = this.$wrapper.find('#io-reoptimized');
            // Cancel button
            this.$cancel_btn = this.$wrapper.find(".cancel_optimizer");
            // Progress 
            this.$progress_container = $('.tp-panel__progress-bar');
            this.$progress_bar = $('.tp-panel__progress-bar .progress-bar');
            this.$label_process_bar = this.$wrapper.find('.io-label-process-bar');
            // Log progress & Notify
            this.$notify_group = this.$wrapper.find('.io-notify-group');
            this.$show_log = this.$wrapper.find(".io-show-log");
            // Progress stastics
            this.$total_image = this.$wrapper.find('.total-number');
            this.$optimized_number = this.$wrapper.find('.optimized-number');
            this.$error_detect = this.$wrapper.find(".io-error");
            this.$optimizer_setting = this.$wrapper.find('.io_optimizer_setting');
            this.$compressed_image = this.$wrapper.find('.compressed-image');
            this.$total_compress_images = this.$wrapper.find('.total-compressed-images');
            this.getTotalImage = function () {
                return parseInt(this.$total_image.html());
            }

            this.getOptimizedNumber = function () {
                return parseInt(this.$optimized_number.html());
            }

            this.getCompressedImageWithSize = function () {
                return parseInt(this.$compressed_image.html());
            }

            this.getNumberSelectedSize = function () {
                return parseInt(this.$compressed_image.data('number-selected-size'));
            }

            this.getErrorNumber = function () {
                return parseInt(this.$error_detect.html());
            }
            this.getPositionProgress = function () {
                return parseInt(this.$optimized_number.html());
            }
            /**
             * Hide Optimizer button and show Cancel Button, active spinner
             */
            this.styleStartOptimizer = function () {
// Hide Optimizer button and show Cancel button
                this.$cancel_btn.addClass(" is-active");
                this.$optimize_btn.css("display", 'none');
                // Hide Update image button
                this.$update_image_btn.css("display", 'none');
                // Show Spinner
                this.$spinner.addClass('is-active');
                // Show log
                this.$show_log.addClass('active');
                this.$show_log.html();
                this.$notify_group.addClass('active');
                // Show progress bar
                this.$progress_container.fadeIn();
                this.$label_process_bar.html(tp_image_optimizer_lang.load.processing);
                this.$show_log.html(tp_image_optimizer_lang.main.get_list_attachment);
                // Reset error counter
                this.$error_detect.html(0);
            }

            /**
             * Show Optimizer button and hide Cancel Button, deactive spinner
             */
            this.styleStopOptimizer = function () {
// Hide cancel button and show Optimizer button
                this.$update_image_btn.css("display", 'inline-block');
                // Hide spinner
                this.$spinner.removeClass('is-active');
            }
            return this;
        }

        var Optimizer = new Optimizer();
        // Stastics box
        function Stastics() {
            this.$wrapper = $(".io-stastics-wrapper");
            this.$total = this.$wrapper.find('.io-total-img');
            this.$total_uncompress = this.$wrapper.find('.io-total-uncompress');
            // Stastics data
            this.$total_number_compressed = this.$wrapper.find('.total-image');
            this.$total_size_uploaded = this.$wrapper.find('.uploaded-size');
            this.$total_size_compressed = this.$wrapper.find('.compressed-size');
            this.$total_size_saving = this.$wrapper.find('.saving-size');
            this.$service_stastics_wrapper = this.$wrapper.find('.io-service-stastics');
            this.$error_notice = this.$wrapper.find('.connect-err');
            this.getTotal = function () {
                return parseInt(this.$total.data('total'));
            }

            this.getCompressedTotal = function () {
                return parseInt(this.$total_uncompress.data('compressed'));
            }

            this.getUnCompressedTotal = function () {
                return parseInt(this.$total_uncompress.html());
            }
            return this;
        }
        var Stastics = new Stastics();
        // Size box
        function Size() {
            this.$wrapper = $(".tpio-size-settings");
            this.$submit_btn = this.$wrapper.find('.submit');
        }
        var Size = new Size();
        // Sticky box
        function Log() {
            this.$wrapper = $(".io-sticky-wrapper");
            this.$header = this.$wrapper.find(".sticky-header");
            this.$content = this.$wrapper.find(".sticky-content");
            this.$loading_box = this.$wrapper.find(".loading-sticky-box");
            this.$log = this.$wrapper.find("log");
            this.$spinner = this.$wrapper.find('.spinner');
            this.show_current_notify = function () {
                Log.$loading_box.css('display', 'block');
            }

            this.hide_loading = function () {
                Log.$loading_box.css('display', 'none');
            }

            // Collapse sticky box
            this.collapse = function () {
                this.$wrapper.addClass('collapse');
            }
            // Collapse sticky box
            this.open = function () {
                this.$wrapper.removeClass('collapse');
                this.$content.addClass("active");
                // Open sticky box
                this.$wrapper.addClass("active");
                this.draggable();
                // Show notify on Sticky box
                this.show_current_notify();
            }
            this.close = function () {
                this.$content.removeClass("active");
                // Open sticky box
                this.$wrapper.removeClass("active");
            }

            // Make sticky box to draggable
            this.draggable = function () {
                Log.$wrapper.draggable(
                        {
                            axis: "x",
                            containment: "window"
                        }
                );
                Log.$wrapper.css('top', '');
            }
        }
        var Log = new Log();
        function Progress_Bar() {
            this.$wrapper = $(".tp-panel__progress-bar");
            this.$active = $(".tp-panel__progress-bar.active-cron");
            this.$progress = this.$wrapper.find(".progress");
            this.$progress_bar = this.$wrapper.find(".progress-bar");
            this.$progress_percent = this.$wrapper.find('.progress-percent');
            this.show = function () {
                this.$wrapper.addClass('active-cron');
            }
            this.hide = function () {
                this.$wrapper.removeClass('active-cron');
            }
        }

        var Progress_Bar = new Progress_Bar();
        /**
         * Update stastics from server
         * 
         * @since 1.0.0
         */
        if ($('.io-stastics-wrapper').length) {
            var percent_success;
            $.ajax({
                type: 'POST',
                url: tp_image_optimizer_admin_js.ajax_url,
                beforeSend: function () {
                    $('.io-stastics-wrapper .spinner').addClass('is-active');
                },
                data: {
                    action: 'get_stastics_from_service',
                },
                success: function (response) {
                    if (!response.success) {
                        Stastics.$error_notice.html(response.data).addClass('active');
                        $('.io-service-stastics').hide();
                    } else if (response.success && response.data.hasOwnProperty('key')) {
                        var r = confirm(tp_image_optimizer_lang.confirm_fix_token);
                        if (r) {
                            location.reload();
                        }
                    } else {

                        Stastics.$total_number_compressed.html(response.data.total_image_success);
                        Stastics.$total_size_uploaded.html(tp_image_optimizer_dislay_size(response.data.total_uploaded_success));
                        Stastics.$total_size_compressed.html(tp_image_optimizer_dislay_size(response.data.total_compressed_success));
                        Stastics.$total_size_saving.html(tp_image_optimizer_dislay_size(response.data.total_saving));
                        percent_success = parseInt(response.data.total_percent_success);
                        $('#io-chart').data('percent', percent_success);
                        // Show chart
                        $('.io-stastics-wrapper .chart').addClass('active');
                        // Remove loading
                        $('.io-stastics-wrapper .spinner').removeClass('is-active');
                        // Update chart
                        $('#io-chart').data('easyPieChart').update(percent_success);
                        if (response.data.hasOwnProperty('user') && tp_image_optimizer_lang.hasOwnProperty(response.data.user)) {
                            $('.account_info .account_info__text').text(tp_image_optimizer_lang[response.data.user]);
                            if (response.data.user == 'pro') {
                                $('.account_info__icon').attr('class', 'account_info__icon account_info__icon--pro');
                            } else {
                                $('.account_info__icon').attr('class', 'account_info__icon');
                            }

                        }
                    }
                }
            });
        }

        /**
         * UPDATE SETTING SITE
         * 
         * @since 1.0.0
         */
        $(document).on('click', '#update-api', function (e) {

            var $this = $(this);
            var $result = $this.closest('.inside').find('.result_alert');
            if ($this.hasClass('disabled')) {
                return false;
            }

            var level = $("#io-compress-level").val();
            $.ajax({
                type: 'POST',
                url: tp_image_optimizer_admin_js.ajax_url,
                data: {
                    level: level,
                    action: 'update_setting',
                },
                beforeSend: function () {
                    $this.attr('data-current_text', $this.val());
                    $this.addClass('disabled').val(tp_image_optimizer_lang.wait);
                    setTimeout(function () {
                        $result.fadeOut();
                    }, 2000);
                    $result.empty().hide();
                },
                success: function (res) {

                    if (res.success) {
                        $result.html(res.data).show();
                    }

                    $this.val($this.attr('data-current_text')).removeClass('disabled');
                }
            });
        });
        /**
         * REFRESH IMAGE LIST
         * 
         * @since 1.0.0
         */

        $(document).on('click', '.refresh-library', function (e) {
            e.preventDefault();
            var $this = $(this);
            if ($this.attr('disabled') == undefined) {
                $this.text(tp_image_optimizer_lang.wait);
                $this.attr('disabled', 'disabled');
                $('.count-media, .update-image .load-speeding-wheel').css('display', 'inline-block');
                add_image_to_plugin(0);
            }
        });
        /**
         * Accept Install
         * 
         * @since 1.0.0
         */

        var $install_progressbar = $('.tp-panel__progress-bar .progress-bar');
        $(document).on('click', '#accept-install', function (e) {
            e.preventDefault();
            var $this = $(this);
            if ($this.hasClass('disabled')) {
                return false;
            }

            if (false == navigator.onLine) {
                $(".install-required").show();
                return;
            }


            var Ajax = $.ajax({
                type: 'POST',
                url: tp_image_optimizer_admin_js.ajax_url,
                data: {
                    action: 'get_token',
                },
                beforeSend: function () {
                    $(".install-required").hide();
                    $this.addClass('disabled');
                    $this.text(tp_image_optimizer_lang.wait);
                    $(".tp-panel__progress-bar").fadeIn();
                    $install_progressbar.css('width', '0%').removeClass('progress-bar--error');
                }
            }).done(function (res) {

                if (!res.success) {

                    $('.install-required').html(res.data).fadeIn();
                    Ajax.abort();
                    $this.removeClass('disabled').text(tp_image_optimizer_lang.getstarted);
                    $install_progressbar.css('width', '100%').addClass('progress-bar--error');
                    $install_progressbar.find('.progress-percent').text('Error');
                } else {

                    $install_progressbar.css('width', '0%');
                    $install_progressbar.find('.progress-percent').text('0%');
                    setTimeout(function () {
                        add_image_to_plugin(0);
                    }, 1500);
                }

            });
        });
        /**
         * Add image to plugin
         * Refresh library
         * 
         * @param int count_flag Pagination
         * @since 1.0.3
         */
        function add_image_to_plugin(count_flag) {

            var total_image = parseInt($('#tp-image-optimizer').data('total'));
            var number = total_image / 800 + 1;
            var number_percent = (100 / (number)).toFixed(0);
            var percent_update;
            $.ajax({
                type: 'POST',
                url: tp_image_optimizer_admin_js.ajax_url,
                data: {
                    action: 'recheck_library',
                    paged: count_flag
                }

            }).done(function (res) {

                if (res.success) {

                    percent_update = number_percent * count_flag;
                    if (percent_update < 100) {
                        $install_progressbar.css('width', percent_update + '%');
                        $install_progressbar.find('.progress-percent').text(percent_update + '%');
                    }

                    count_flag++;
                    if (count_flag < number) {
                        add_image_to_plugin(count_flag);
                    } else {
                        setTimeout(set_status_to_installed, 1000);
                    }
                }
            });
        }

        /**
         * Set stastus plugin to Installed
         * 
         * @since 1.0.3
         */
        function set_status_to_installed() {
            $.ajax({
                type: 'POST',
                url: tp_image_optimizer_admin_js.ajax_url,
                data: {
                    action: 'set_status_to_installed'
                }
            }).done(function (res) {

                $install_progressbar.css('width', '100%');
                $install_progressbar.find('.progress-percent').text('100%');
                setTimeout(function () {
                    location.reload(); // Reload the page.
                }, 2000);
            });
        }

        /**
         * Update list size image optimize
         * 
         * @since 1.0.0
         */
        $(document).on('click', '#tpio-update-size', function (e) {

            e.preventDefault();
            var $this = $(this);
            var $result = $this.prev('.result_alert');
            if ($this.hasClass('disabled')) {
                return false;
            }

            var list_sizes = [];
            var size;
            $("input[name='io-list-size[]']:checked").each(function (e) {
                size = $(this).val();
                list_sizes.push(size);
            });
            $.ajax({
                type: 'POST',
                url: tp_image_optimizer_admin_js.ajax_url,
                data: {
                    listsizes: list_sizes.toString(),
                    action: 'update_sizes'
                },
                beforeSend: function () {
                    $this.attr('data-current_text', $this.val());
                    $this.addClass('disabled').val(tp_image_optimizer_lang.wait);
                    $result.empty().hide();
                }
            }).done(function (res) {

                $this.val($this.attr('data-current_text')).removeClass('disabled');
                if (res.success) {
                    $result.html(res.data).show();
                    setTimeout(function () {
                        $(".result_alert").fadeOut();
                    }, 3000);
                }
            });
        })


        var content_append;
        var text;
        /**
         * Append success image to logbox
         * 
         * @since 1.0.0
         */
        function append_success_compressed_to_log(attachment_id) {
            text = tp_image_optimizer_lang.success.optimized + attachment_id;
            content_append = "<li data-id=" + attachment_id + ">"
                    + "<span class='sticky-number-id'></span>"
                    + "<a href ='#' data-id=" + attachment_id + ">" + text + "</a>"
                    + "</li>";
            Log.$content.find("ul").prepend(content_append);
        }

        /**
         *  Add error on compress progress to log box
         * 
         * @param {type} size
         * @returns {Object.size}
         * @since 1.0.0
         */
        function log_error_on_compress_progress(attachment_id, log) {
            content_append = "<li data-id=" + attachment_id + " >"
                    + "<span class='sticky-number-id error'></span>"
                    + "<a href ='#' data-id=" + attachment_id + "> #" + attachment_id + ' - ' + log + "</a>"
                    + "</li>";
            Log.$content.find("ul").prepend(content_append);
        }


        /**
         * Display image size
         * 
         * @param type $size
         * @return String Display size ( Byte, KB, MB )
         */
        function tp_image_optimizer_dislay_size(size) {

            var display_size;
            if (size < 1024) {
                display_size = size + tp_image_optimizer_lang.size.B;
            } else if (size < 1024 * 1024) {
                size = (size / (1024)).toFixed(2);
                display_size = size + tp_image_optimizer_lang.size.KB;
            } else {
                size = (size / (1024 * 1024)).toFixed(2);
                display_size = size + tp_image_optimizer_lang.size.MB;
            }
            return display_size;
        }



        /*
         * Uninstall
         * Not show on default panel
         * Usefull for developer
         * 
         * @since 1.0.1
         */
        $(document).on('click', '#uninstall', function (e) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: tp_image_optimizer_admin_js.ajax_url,
                data: {
                    action: 'uninstall',
                },
                beforeSend: function () {
                    $('.io-sizes-option-wrapper .spinner').addClass('is-active');
                },
                success: function (html) {
                    $('.io-sizes-option-wrapper .spinner').removeClass('is-active');
                },
                error: function (e) {
                }

            }).done(function () {
                setTimeout(function () {
                    location.reload(); // Reload the page.
                }, 2000);
            })
        })

        /**
         * Compress option for specific image
         * 
         * @since 1.0.1
         */
        $(document).on('click', '.single-compress', function (e) {
            e.preventDefault();
            $(this).remove();
            var id = $(this).attr('href');
            $('.compress-' + id + ' .spinner').addClass('is-active');
            $.ajax({
                type: 'POST',
                url: tp_image_optimizer_admin_js.ajax_url,
                data: {
                    action: 'manual_optimizer',
                    id: id,
                },
                complete: function (result) {
                    $('.compress-' + id + ' .spinner').remove();
                    if (result.hasOwnProperty('responseJSON')) {
                        if (result.responseJSON.success) {
                            if (result.responseJSON.data.full_detail.success_detail != null) {
                                update_stastics_detail_after_optimized(id, result.responseJSON.data.full_detail.old_size, result.responseJSON.data.full_detail.new_size);
                            } else {
                                update_stastics_detail_after_optimized(id, 0, 0);
                            }
                        } else {
                            add_status_for_image(id, false, result.responseJSON.data.log);
                        }
                    }
                    delete id;
                }
            });
        })

        /**
         * Update stastics for an image after ajax completed
         * 
         * @param int attachment_id 
         * @param double orginal_size
         * @param double current_size
         * @returns void
         * @since 1.0.2
         */
        function update_stastics_detail_after_optimized(attachment_id, original_size, current_size) {
            // Caculator
            var new_size = tp_image_optimizer_dislay_size(current_size);
            var saving = original_size - current_size;
            var percent_raw = ((saving / original_size) * 100).toFixed(2);
            var percent = percent_raw + '%';
            // Count saving
            var saving = original_size - current_size;
            if (percent_raw > 1) {
                // New size 
                $('.current_size .table-detail-' + attachment_id).html(new_size);
                // Saving
                $('.detail-saving-' + attachment_id).html(tp_image_optimizer_dislay_size(saving));
                var percent = ((saving / original_size) * 100).toFixed(2);
                percent = percent + '%'
                $('.percent-saving-' + attachment_id).html(percent);
            }
            // Show success icon
            $('.compress-' + attachment_id).html('');
            $('.compress-' + attachment_id).append('<span class="success-optimize"></span>');
        }

        /**
         * Add status for image
         * 
         * @param int attachment_id
         * @param boolean Success or error
         * @param String Error log  
         * @since 1.0.8 
         */
        function add_status_for_image(attachment_id, success, error_log) {
            $('.compress-' + attachment_id).html('');
            if (success) {
                $('.compress-' + attachment_id).append('<span class="success-optimize"></span>');
            } else {
                $('.compress-' + attachment_id).append('<span class="faq-compress_error" data-log="' + error_log + '"></span>');
            }
        }


        /**
         * Show stastics for cronjob work
         * - Update progress bar
         * - Active log bar
         */
        if (Progress_Bar.$active.length) {
            Log.open();
            get_stastics_for_cron();
        } else {
            swich_optimizer_to_stop(true);
        }

        /** 
         * Start running cronjob optimizer
         */
        $(document).on('click', '#optimizer_btn', function (e) {
            // Optimizer group
            var $optimizer = Optimizer;
            var $log = Log;
            // Clear option
            $.ajax({
                type: 'POST',
                url: tp_image_optimizer_admin_js.ajax_url,
                data: {
                    action: 'clear_when_cronjob_done',
                },
                complete: function (res) {
                }
            });
            // Show notice
            $(".tp-io-notice-bar").html("<div class='notice notice-success is-dismissible'><p>" + tp_image_optimizer_lang.main.can_close_window + "</p> </div>'");
            if (Progress_Bar.length) {
                Progress_Bar.show();
            }
            // Force optimizer
            var force = 0;
            if ($("input#io-reoptimized:checked").length) {
                force = 1;
            }

            // If complete compresstion
            if (!force && Stastics.getUnCompressedTotal() == 0) {
                display_finish_compress_notice(true);
                return;
            }

            swich_optimizer_to_stop(false);
            $optimizer.$optimized_number.html(0);
            $optimizer.$compressed_image.html(0);
            $optimizer.$error_detect.html(0);
            $(".result_alert").fadeOut();
            $.ajax({
                type: 'POST',
                url: tp_image_optimizer_admin_js.ajax_url,
                data: {
                    action: 'process_optimize_image',
                    force: force
                },
                complete: function () {
                    $log.open();
                    $optimizer.$notify_group.css('display', 'block');
                    get_stastics_for_cron();
                }
            })
        });
        /**
         * Event CANCEL when optimizing image
         * 
         */
        $(document).on("click", '.cancel_optimizer', function (e) {
            Log.hide_loading();
            Optimizer.$show_log.html(tp_image_optimizer_lang.main.pause);
            Optimizer.$label_process_bar.html(tp_image_optimizer_lang.success.success);
            Optimizer.styleStopOptimizer();
            // Set status page to stop process - Usefull to prevent reload

            $.ajax({
                type: 'POST',
                url: tp_image_optimizer_admin_js.ajax_url,
                data: {
                    action: 'cancel_cronjob',
                },
                beforeSend: function () {
                    // Change text to loading cancel
                    $(this).val(tp_image_optimizer_lang.load.wait);
                },
                complete: function (res) {
                    if (res.hasOwnProperty('responseJSON')) {
                        $(this).val(tp_image_optimizer_lang.main.stop);
                        swich_optimizer_to_stop(true);
                    }
                }
            });
        });
        /**
         * Get stastics for cronjob
         * Update per second
         * 
         * @category Cronjob
         */
        function get_stastics_for_cron() {
            var $progressbar = Progress_Bar;
            var $optimizer = Optimizer;
            var $log = Log;
            // Run cronjob
            $.ajax({
                type: 'POST',
                url: tp_image_optimizer_admin_js.ajax_url,
                data: {
                    action: 'get_stastics_for_cron',
                },
                complete: function (response) {
                    if (response.hasOwnProperty('responseJSON')) {
                        var total_image = response.responseJSON.data.total_image; // Count total image
                        var total_error = response.responseJSON.data.total_error; // Count total detect error
                        var run = response.responseJSON.data.run; // Count number image processed done
                        var number_size = $optimizer.getNumberSelectedSize();
                        var total_number = $optimizer.getTotalImage();
                        $optimizer.$total_compress_images.html(total_number * number_size);
                        if (run != 0) {
                            $optimizer.$optimized_number.html(run);
                            $optimizer.$compressed_image.html(number_size * run);
                            $optimizer.$error_detect.html(total_error);
                        }

                        // Update progress bar
                        var percent = response.responseJSON.data.percent + "%";
                        $progressbar.$progress_bar.css('width', percent);
                        $progressbar.$progress_percent.html(percent);
                        // Detail progress
                        var attachment_id = response.responseJSON.data.id_completed; // ID attachment procressed complete
                        var last_status = response.responseJSON.data.last_status; // Status of compress progress - true or false
                        var error_log = response.responseJSON.data.last_error_log; // Error log if last_status = false
                        // Size
                        var old_size = 1;
                        var new_size = 1;
                        var success_detail = null;
                        if (response.responseJSON.data.success_detail != null) {
                            success_detail = response.responseJSON.data.success_detail;
                            old_size = success_detail.old_size; // Size of image before compress
                            new_size = success_detail.new_size; // Size of imaeg after compress
                        }

                        if ($progressbar.$progress_bar.data('compressed') != attachment_id) {


                            // Append success compress image to log box
                            if ((attachment_id) && (attachment_id != 'N/A')) {
                                if ((success_detail == null) && (response.responseJSON.data.last_error_log != "")) {
                                    log_error_on_compress_progress(attachment_id, error_log);
                                    add_status_for_image(attachment_id, false, error_log);
                                } else if (last_status == '1' && (success_detail != null) && (success_detail.success)) {
                                    // Show log for image
                                    append_success_compressed_to_log(attachment_id);
                                    add_status_for_image(attachment_id, true, '');
                                    // Update for Image stastics
                                    update_stastics_detail_after_optimized(attachment_id, old_size, new_size);
                                    // Update HTML for Uncompress stastics
                                    if (response.responseJSON.data.force != '1') {
                                        var $uncompress_image = $(".io-total-uncompress");
                                        var uncompress = parseInt($uncompress_image.html());
                                        uncompress = uncompress - 1;
                                        if (uncompress >= 0) {
                                            $uncompress_image.html(uncompress);
                                        }
                                    }
                                }
                            }
                            $progressbar.$progress_bar.data('compressed', attachment_id);
                        }

                        if (parseInt(response.responseJSON.data.cron) != 0) {
                            setTimeout(function () {
                                get_stastics_for_cron();
                            }, 1000);
                        } else {
                            // Hide log box
                            $log.hide_loading();
                            $log.collapse();
                            // Change text
                            $optimizer.$label_process_bar.html(tp_image_optimizer_lang.success.success);
                            $optimizer.$optimized_number.html($optimizer.getOptimizedNumber() + 1);
                            $optimizer.$compressed_image.html(number_size + $optimizer.getCompressedImageWithSize());
                            if ((total_error == 0) && (run == total_image)) {
                                display_finish_compress_notice(1);
                            } else {
                                display_finish_compress_notice(2);
                            }
                            swich_optimizer_to_stop(true);
                            $(".sticky-header").html(tp_image_optimizer_lang.main.optimized);
                        }
                    }
                }
            })
        }

        /**
         * Display finish notice
         * 
         * @param int success
         * @returns void
         * @since 1.0.8
         */
        function display_finish_compress_notice(success) {
            switch (success) {
                case 1 :
                    Optimizer.$optimizer_setting.html('<div class="result_alert" style="display: block;">' + tp_image_optimizer_lang.success.done + '</div>');
                    break;
                case 2 :
                    Optimizer.$optimizer_setting.html('<div class="result_alert result_alert--warning" style="display: block;">' + tp_image_optimizer_lang.error.detect + '</div>');
                    break;
                default:
                    Optimizer.$optimizer_setting.html('<div class="result_alert result_alert--warning" style="display: block;">' + tp_image_optimizer_lang.success.finish + '</div>');
            }
        }

        /**
         * Switch optimize status to no activity
         * @param boolean optimize
         */

        function swich_optimizer_to_stop(optimize) {

            if (optimize) { // Stop optimize
                Optimizer.$optimize_btn.addClass('is-active');
                Optimizer.$cancel_btn.removeClass('is-active');
                Progress_Bar.hide();
            } else { // Optimizing ..
                Optimizer.$optimize_btn.removeClass('is-active');
                Optimizer.$cancel_btn.addClass('is-active');
                Progress_Bar.show();
            }
        }

    });
})(jQuery);