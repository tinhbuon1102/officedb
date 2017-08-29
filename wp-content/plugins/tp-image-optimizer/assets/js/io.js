(function ($) {

    var id;
    var popup;

    /**
     * Hover view on detail table
     * Open Tooltip : Stastics of images by sizes
     * 
     * @since 1.0.0
     */
    $(document).on("hover", ".badge", function (e) {
        e.preventDefault()
        id = $(this).attr("href");
        popup = new jBox('Mouse', {
            attach: '#'.id,
            title: tp_image_optimizer_lang.main.detail_of + $(this).attr("href"),
            adjustPosition: true,
            target: '#'.id,
            ajax: {
                type: 'GET',
                url: tp_image_optimizer_admin_js.ajax_url,
                beforeSend: function () {
                    this.position();
                },
                data: {
                    action: 'get_stastics_detail',
                    id: id
                },
                success: function (html) {
                    popup.position();
                },
                complete: function () {
                    popup.position();
                }
            },
            onCloseComplete: function () {
                this.destroy();
            },
        }).open();
        $(document).on('mouseleave', '.badge', function (event) {
            $(".jBox-Mouse").remove()
        })
    });

    /**
     * Optimize log box
     * Open or Collapse Sticky log
     * 
     * @since 1.0.0
     */

    $(document).on("click", ".sticky-header", function (e) {
        e.preventDefault();
        if ($(".io-sticky-notice").hasClass("collapse")) {
            $(".io-sticky-notice").removeClass("collapse");
        } else {
            $(".io-sticky-notice").addClass("collapse");
        }
    });

    /** 
     * Show stastics of image by size
     * Actived when user click to link of  Success Log
     * 
     * @since 1.0.0
     */
    $(document).on("click", ".io-sticky-notice li", function (event) {
        event.preventDefault();
        id = $(this).data('id');
        popup = new jBox('Modal', {
            title: tp_image_optimizer_lang.main.detail_of + id,
            adjustPosition: true,
            isolateScroll: true,
            ajax: {
                type: 'GET',
                url: tp_image_optimizer_admin_js.ajax_url,
                data: {
                    action: 'get_stastics_detail',
                    id: id
                },
                success: function (html) {
                },
            },
            closeOnClick: true,
            onCloseComplete: function () {
                this.destroy();
            },
        });
        popup.open();
    })

    /**
     * Draw service stastics chart
     * @use easyPieChart
     * 
     * @since 1.0.0
     */
    $(document).on('ready', function () {
        if ($("#io-chart").length) {
            $('#io-chart').easyPieChart({
                onStep: function (from, to, percent) {
                    $(this.el).find('.percent').text(tp_image_optimizer_lang.main.saving + " " + Math.round(percent));
                },
                delay: 3000,
                barColor: '#50cfe6',
                trackColor: '#c7ced6',
                scaleColor: false,
                lineWidth: 20,
                trackWidth: 16,
                lineCap: 'butt',
                size: 170
            });
        }
    });


    /**
     * Show tooltip for FAQ
     * 
     * @param String name of tooltip
     * @since 1.0.2
     */

    function faq_tooltip(name) {
        var title = name + "_title";
        popup = new jBox('Mouse', {
            title: tp_image_optimizer_lang.faq[title],
            content: tp_image_optimizer_lang.faq[name],
            adjustPosition: true,
            width: 250,
            onCloseComplete: function () {
                this.destroy();
            },
        }).open();
        $(document).on('mouseleave', '.faq-' + name, function (event) {
            $(".jBox-Mouse").remove()
        })
    }

    /**
     * FAQ Help - Tooltip Service stastics
     * 
     * @since 1.0.2
     */
    $(document).on("hover", ".faq-stastics_service", function (e) {
        e.preventDefault()
        faq_tooltip('stastics_service');
    });

    /**
     * FAQ Help - Quality Tooltip
     * 
     * @since 1.0.2
     */
    $(document).on("hover", ".faq-quality", function (e) {
        e.preventDefault();
        faq_tooltip('quality');
    });

    // Prevent default click badge
    $(document).on("click", ".badge", function (e) {
        e.preventDefault();
    });

    /**
     * FAQ Help - Error tooltip
     * 
     * @since 1.0.2
     */
    $(document).on("hover", ".faq-compress_error", function (e) {
        e.preventDefault()
        if ($(this).data('log') != '') {
            popup = new jBox('Mouse', {
                title: tp_image_optimizer_lang.faq.compress_error_title,
                content: $(this).data('log'),
                adjustPosition: true,
                width: 250,
                onCloseComplete: function () {
                    this.destroy();
                },
            }).open();
            $(document).on('mouseleave', ".faq-compress_error", function (event) {
                $(".jBox-Mouse").remove()
            })
            return;
        }
        faq_tooltip('compress_error');
    });

    /**
     * FAQ Help - Size tooltip
     * 
     * @since 1.0.2
     */
    $(document).on("hover", ".faq-size", function (e) {
        e.preventDefault();
        faq_tooltip('size');
    });

    /**
     * Tooltip Force Re-Optimize
     * 
     * @since 1.0.2
     */
    $(document).on("hover", ".faq-force", function (e) {
        e.preventDefault()
        faq_tooltip('force');
    });

    /**
     * FAQ Original 
     * 
     * @since 1.0.8
     */
    $(document).on("hover", ".faq-original", function (e) {
        e.preventDefault()
        faq_tooltip('original');
    });


    $(document).on("hover", ".faq-stastics_original", function (e) {
        e.preventDefault()
        faq_tooltip('stastics_original');
    })

    /**
     * Keep origin
     * Set select or unselect Original Compression
     * 
     * @since 1.0.8
     */
    $(document).on('change', '.keep_original', function () {
        var check = $("input#io-keep-original").prop("checked");
        $(".tpio-size-settings input[value='full']").prop("checked", check);
        console.log(check);
        $.ajax({
            type: 'POST',
            url: tp_image_optimizer_admin_js.ajax_url,
            data: {
                action: 'compress_origin_select',
                origin_compress: check
            },
            success: function (res) {
                console.log(res);
                if (!res.data.success) {
                    $('.io_optimizer_setting').html('<div class="result_alert" style="display: block;">' + tp_image_optimizer_lang.main.update_successfull + '</div>');
                    setTimeout(function () {
                        $(".result_alert").fadeOut();
                    }, 1000)
                }
            }
        });
    });

    $(document).on('change', '.choose-full', function () {
        var check = $(".tpio-size-settings input[value='full']").prop("checked");
        $("#io-keep-original").prop("checked", check);
    })

    $(document).on('ready', function () {
        var check = $(".tpio-size-settings input[value='full']").prop("checked");
        $("#io-keep-original").prop("checked", check);
    })


})(jQuery);