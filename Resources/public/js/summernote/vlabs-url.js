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
        'vlabs-url': function (context) {

            context.memo('button.vlabs-url', function () {

                return $.summernote.ui.button({
                    contents: '<i class="fa fa-external-link"></i>',
                    tooltip: 'Insérer un lien HTTP',
                    click: function () {

                        var range = $('[data-editor="postContent"]').summernote('createRange');
                        if (range.toString() == '')  return;
                        $('[data-editor="postContent"]').data('range', range);
                        $.ajax({
                            url: Routing.generate('vlabs_cms_admin_summernote_modal', {
                                slug: 'url'
                            }),
                            type: 'GET',
                            success: function (data) {
                                $('body').append(data);

                                var text = range.toString();

                                $('[data-modal="url"]').fadeIn(function(){
                                    $('[type=text]', this).select();
                                });

                                $('.close', '[data-modal="url"]').click(function(){
                                    $('[data-modal="url"]').remove();
                                });

                                $('button', '[data-modal="url"]').on('click', function () {

                                    var url = $('input', '[data-modal="url"]').val();

                                    if (!url) return;

                                    $.ajax({
                                        url: Routing.generate('vlabs_cms_admin_summernote_block', {
                                            slug: 'url',
                                            url: url,
                                            text: text
                                        }),
                                        type: 'GET',
                                        success: function (html) {
                                            $('[data-editor="postContent"]').data('range').pasteHTML(html);
                                            $('[data-editor="postContent"]').val($('[data-editor="postContent"]').summernote('code'));
                                            $('[data-modal="url"]').remove();
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
