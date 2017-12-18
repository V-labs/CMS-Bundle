(function (factory) {
    if (typeof define === 'function' && define.amd) {
        define(['jquery'], factory);
    } else if (typeof module === 'object' && module.exports) {
        module.exports = factory(require('jquery'));
    } else {
        factory(window.jQuery);
    }
}(function ($) {

    $.extend($.summernote.plugins, {
        'vlabs-help': function (context) {

            context.memo('button.vlabs-help', function () {

                return $.summernote.ui.button({
                    contents: '<i class="fa fa-question-circle"></i>',
                    tooltip: 'Aide',
                    click: function () {

                        $.ajax({
                            url: Routing.generate('vlabs_cms_admin_summernote_modal', {
                                slug: 'help'
                            }),
                            type: 'GET',
                            success: function (data) {
                                var $modal = $(data);
                                $modal.find('.close').click(function () {
                                    $modal.fadeOut();
                                });
                                $('body').append($modal);
                                $modal.fadeIn();
                            }
                        });
                    }
                }).render();
            });
        }
    });
}));
