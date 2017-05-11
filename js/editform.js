$(document).ready(function () {
    $('select[id*="id_config_upload_or_external"]').each(function () {
        var val = $(this).val();
        var parent = $(this).parents('.fitem_fselect');
        var file_upload = $(parent).siblings('.file_upload');
        var external_video = $(parent).siblings('.external_video');
        var overlay = $(parent).siblings('.overlayblock');
        var overlay_or_block = $(parent).siblings('.overlay_or_block');
        var block = $(parent).siblings('.textblock');

        if (val == 0) {
            $(external_video).hide();
            $(file_upload).hide();
            $(overlay).hide();
            $(overlay_or_block).hide();
            $(block).hide();
        } else if (val == 1) {
            $(external_video).hide();
        } else if (val == 2) {
            $(file_upload).hide();
            $(overlay).hide();
            $(overlay_or_block).hide();
            $(block).show();
        }

        $(this).click(function () {
            var val = $(this).val();
            var parent = $(this).parents('.fitem_fselect');
            var file_upload = $(parent).siblings('.file_upload');
            var external_video = $(parent).siblings('.external_video');
            var overlay = $(parent).siblings('.overlayblock');
            var overlay_or_block = $(parent).siblings('.overlay_or_block');
            var overlay_or_block_val = $(overlay_or_block).find('select[id*="id_config_block_or_overlay"]').val();
            var block = $(parent).siblings('.textblock');

            if (val == 0) {
                $(external_video).hide();
                $(file_upload).hide();
                $(overlay).hide();
                $(overlay_or_block).hide();
                $(block).hide();
            } else if (val == 1) {
                $(external_video).hide();
                $(file_upload).show();
                $(overlay_or_block).show();
                if (overlay_or_block_val == 0) {
                    $(overlay).hide();
                    $(block).hide();
                } else if (overlay_or_block_val == 1) {
                    $(overlay).hide();
                    $(block).show();
                } else {
                    $(block).hide();
                    $(overlay).show();
                }
            } else if (val == 2) {
                $(file_upload).hide();
                $(overlay).hide();
                $(overlay_or_block).hide();
                $(external_video).show();
                $(block).show();
            }
        });
    });

    $('select[id*="id_config_block_or_overlay"]').each(function () {
        var val = $(this).val();
        var parent = $(this).parents('.overlay_or_block');
        var overlay = $(parent).siblings('.overlayblock');
        var block = $(parent).siblings('.textblock');
        var upload_or_external = $(parent).siblings().find('select[id*="id_config_upload_or_external"]').val();

        if (val == 0) {
            $(overlay).hide();
            $(block).hide();
        } else if (val == 1) {
            $(overlay).hide();
            $(block).show();
        } else if (val == 2) {
            $(block).hide();
            $(overlay).show();
        }

        if ((val == 0 || val == 1 || val == 2) && upload_or_external == 2) {
            $(overlay).hide();
            $(block).show();
        }

        $(this).click(function () {
            var val = $(this).val();
            var parent = $(this).parents('.overlay_or_block');
            var overlay = $(parent).siblings('.overlayblock');
            var block = $(parent).siblings('.textblock');

            if (val == 0) {
                $(overlay).hide();
                $(block).hide();
            } else if (val == 1) {
                $(overlay).hide();
                $(block).show();
            } else {
                $(block).hide();
                $(overlay).show();
            }
        });
    });

});