jQuery(function ($) {
    var $blobs, $add_new, $preview, $context,cache=[];

    $('body').on('editing_module_option', function (e, type, settings, context) {
        if (type === 'pointers') {
            if(settings.image_url){
                var w = settings.image_width?settings.image_width:'',
                    h = settings.image_height?settings.image_height:'',
                 k = Themify.hash(settings.image_url+w+h);
                cache[k] = '<img src="' +settings.image_url+ '" width="'+w+'" height="'+h+ '" alt="" />';
            }
            setTimeout(function () {
                $context = context;
                $blobs = $('#blobs_showcase', context);
                $add_new = $blobs.next('p.add_new').find('a');
                $preview = $('#pointers-preview > div', context);
                update_image();
                make_preview();
            }, 1000);
        }
    });
    $('body', top_iframe)
            .on('change', '#themify_builder_options_setting #image_url, #themify_builder_options_setting #image_width, #themify_builder_options_setting #image_height', update_image)
            .on('mousedown touchstart', '#pointers-preview .tb-blob', blob_edit)
            .on('click touchstart', '#pointers-preview img', add_new_blob)
            .on('click touchstart', '#pointers .tb-blob-delete', blob_delete);

    function make_preview() {
        // display previously added blobs
        $blobs.find('.tb_repeatable_field').each(function (i, v) {
            var left = $(this).find('[name="left"]').val();
            if (left!== '') {
                add_blob(left, $(this).find('[name="top"]').val(), ++i);
            }
        });
    }

    function remove_pointers() {
        $blobs.find('.tb-blob').remove();
    }

    function update_image() {
        /* early call, return back until the edit window is initialized */
        if ($preview === undefined) {
            return;
        }
       
        var url = $('#image_url', $context).val();
        if ('' === url) {
            $('#pointers', $context).hide();
        } 
        else {
            function callback(data){
                $('#pointers',$context).show();
                $preview.find('img').remove();
                $preview.prepend(data);
            }
            var w = $('#image_width',$context).val(),
                h = $('#image_height',$context).val(),
                k = Themify.hash(url+w+h);
                if(cache[k]!==undefined){
                    callback(cache[k]);
                    return;
                }
            $.ajax({
                url: themifyBuilder.ajaxurl,
                method: 'POST',
                data: {
                    action: 'builder_pointers_get_image',
                    pointers_image: url,
                    pointers_width: w,
                    pointers_height: h
                },
                beforeSend: function () {
                    $preview.find('.loading').fadeIn();
                },
                success: function (data) {
                    cache[k] = data;
                    callback(cache[k]);
                },
                complete: function () {
                    $preview.find('.loading').fadeOut();
                }
            });
        }
    }

    function add_blob(left, top, id) {
        return $('<div class="tb-blob" data-id="' + id + '" style="top: ' + top + '%; left: ' + left + '%;"><div class="tb-blob-icon"></div></div>').appendTo($preview)
                .draggable({
                    stop: function (e) {
                        var thiz = $(this),
                            top_percent = (parseFloat(thiz.css('top')) * 100) / $preview.height(),
                            left_percent = (parseFloat(thiz.css('left')) * 100) / $preview.width(),
                            row = $blobs.find('.tb_repeatable_field:nth-child(' + thiz.data('id') + ')');
                        $('[name="left"]', row).val(left_percent);
                        $('[name="top"]', row).val(top_percent).trigger('keyup');
                        if(themifybuilderapp.mode==='visual'){
                            $(document).trigger('mouseup');
                        }
                    }
                });
    }

    function add_new_blob(e) {
        var top_percent = (e.offsetY * 100) / $(this).height(),
                left_percent = (e.offsetX * 100) / $(this).width(),
                id = $blobs.find('.tb_repeatable_field').length + 1;

        $add_new.get(0).click();
        add_blob(left_percent, top_percent, id).trigger('mousedown'); // show options for the pointer
        var row = $blobs.find('.tb_repeatable_field').last();
        $('[name="left"]', row).val(left_percent);
        $('[name="top"]', row).val(top_percent).trigger('keyup');
    }

    function blob_edit() {
        var id = $(this).data('id');
        $preview.find('.tb-blob').removeClass('active');
        $(this).addClass('active');
        var row = $blobs.show().find('.tb_repeatable_field').hide().filter(':nth-child(' + id + ')').show();
        if (row.find('.tb-blob-delete').length === 0) {
            row.append('<a class="tb-blob-delete" href="#"><i class="fa fa-close"></i></a>');
        }
        return false;
    }

    function blob_delete() {
        var row = $(this).closest('.tb_repeatable_field'),
                index = row.index() + 1;
        $preview.find('.tb-blob[data-id="' + index + '"]').remove();
        row.remove();

        // redo the preview, to correct the indexes
        remove_pointers();
        make_preview();

        return false;
    }
});