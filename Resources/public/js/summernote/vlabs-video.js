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
        'vlabs-video': function (context) {

            context.memo('button.vlabs-video', function () {

                return $.summernote.ui.button({
                    contents: '<i class="fa fa-file-video-o"></i>',
                    tooltip: 'Insérer une vidéo YoutTube',
                    click: function () {

                        var range = $('[data-editor="postContent"]').summernote('createRange');
                        $('[data-editor="postContent"]').data('range', range);
                        $.ajax({
                            url: Routing.generate('vlabs_cms_admin_summernote_modal', {
                                slug: 'video'
                            }),
                            type: 'GET',
                            success: function (data) {
                                $('body').append(data);

                                $('[data-modal="video"]').fadeIn();
                                $('.close', '[data-modal="video"]').click(function () {
                                    $('[data-modal="video"]').remove();
                                });
                                $('[data-title]', '[data-modal="video"]').tooltip();

                                $('button', '[data-modal="video"]').click(function () {
                                    var content = $('textarea', '[data-modal="video"]').val();
                                    if (!content) return;

                                    var $block = $('<p class="video-container">');
                                    $block.html(content);

                                    $('[data-editor="postContent"]').data('range').pasteHTML($block, true);
                                    $('[data-editor="postContent"]').val($('[data-editor="postContent"]').summernote('code'));
                                    $('[data-modal="video"]').remove();
                                    $('[data-editor="postContent"]').summernote('undo');
                                    $('[data-editor="postContent"]').summernote('redo');
                                });
                            }
                        });
                    }
                }).render();
            });
        }
    });
}));
