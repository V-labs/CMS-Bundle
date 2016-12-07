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
        'vlabs-link': function (context) {

            context.memo('button.vlabs-link', function () {

                return $.summernote.ui.button({
                    contents: '<i class="fa fa-link"></i>',
                    tooltip: 'Insérer un lien vers un article',
                    click: function () {

                        var range = $('[data-editor="postContent"]').summernote('createRange');
                        $('[data-editor="postContent"]').data('range', range);
                        $.ajax({
                            url: Routing.generate('vlabs_cms_admin_summernote_modal', {
                                slug: 'link'
                            }),
                            type: 'GET',
                            success: function (data) {
                                $('body').append(data);

                                $('[data-modal="link"]').fadeIn();
                                $('.close', '[data-modal="link"]').click(function(){
                                    $('[data-modal="link"]').remove();
                                });

                                $('select', '[data-modal="link"]').select2({
                                    placeholder: 'Sélectionnez un article'
                                }).on('change', function () {
                                    $.ajax({
                                        url: Routing.generate('vlabs_cms_admin_summernote_block', {
                                            slug: 'link',
                                            id: $(this).val()
                                        }),
                                        type: 'GET',
                                        success: function (html) {
                                            $('[data-editor="postContent"]').data('range').pasteHTML(html);
                                            $('[data-editor="postContent"]').val($('[data-editor="postContent"]').summernote('code'));
                                            $('[data-modal="link"]').remove();
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
