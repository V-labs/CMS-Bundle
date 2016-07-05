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
        'vlabs-mailto': function (context) {

            context.memo('button.vlabs-mailto', function () {

                return $.summernote.ui.button({
                    contents: '<i class="fa fa-envelope"></i>',
                    tooltip: 'Insérer une adresse e-mail',
                    click: function () {

                        var range = $('[data-editor="postContent"]').summernote('createRange');
                        if (range.toString() == '')  return;
                        $('[data-editor="postContent"]').data('range', range);
                        $.ajax({
                            url: Routing.generate('vlabs_cms_admin_summernote_modal', {
                                slug: 'mailto'
                            }),
                            type: 'GET',
                            success: function (data) {
                                $('body').append(data);

                                var text = range.toString();

                                $('[data-modal="mailto"]').fadeIn();
                                $('.close', '[data-modal="mailto"]').click(function(){
                                    $('[data-modal="mailto"]').remove();
                                });

                                $('button', '[data-modal="mailto"]').on('click', function () {

                                    var email = $('input', '[data-modal="mailto"]').val();

                                    if (!email) return;

                                    $.ajax({
                                        url: Routing.generate('vlabs_cms_admin_summernote_block', {
                                            slug: 'mailto',
                                            email: email,
                                            text: text
                                        }),
                                        type: 'GET',
                                        success: function (html) {
                                            $('[data-editor="postContent"]').data('range').pasteHTML(html);
                                            $('[data-editor="postContent"]').val($('[data-editor="postContent"]').summernote('code'));
                                            $('[data-modal="email"]').remove();
                                            $('[data-editor="postContent"]').summernote('undo');
                                            $('[data-editor="postContent"]').summernote('redo');
                                        }
                                    });
                                });

                            }
                        });
                    }
                }).render();
            });
        }
    });
}));
