$(document).ready(function () {
    $('.center').center();
    $.fn.responsiveBlock();
    $.fn.checkLanguage();
});

$.fn.center = function () {
    var parent = this.parent();
    this.css('top', ($(parent).height() - this.height()) / 2 + 'px');

    return this;
}

$.fn.responsiveBlock = function () {
    var hasBlocks = true;

    $('.slick-slide').each(function () {
        if ($('.block-content').length == 0) {
            hasBlocks = false;
        }
    });

    if (hasBlocks) {
        $('.slick-slide').each(function () {
            $(this).find('.block-content').addClass('responsive-content');
            $(this).find('.block-with-element').children().first().addClass('responsive-element');
        });

        $('.slick-prev').addClass('responsive-arrow');
        $('.slick-next').addClass('responsive-arrow');
    }
}

$.fn.checkLanguage = function () {
    var lang = $('html').attr('lang');

    if (lang == 'de') {
        $('.block-with-element span[lang="en"]').hide();
    } else {
        $('.block-with-element span[lang="de"]').hide();
    }
}