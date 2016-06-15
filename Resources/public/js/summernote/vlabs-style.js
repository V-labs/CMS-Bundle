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
        'vlabs-style': function (context) {

            context.memo('button.vlabs-style', function () {

                return $.summernote.ui.button({
                    contents: '<i class="fa fa-font"></i>',
                    tooltip: 'Appliquer un style',
                    click: function () {

                        var range = $('[data-editor="postContent"]').summernote('createRange');
                        if (range.toString() == '')  return;
                        $('[data-editor="postContent"]').data('range', range);
                        $.ajax({
                            url: Routing.generate('vlabs_cms_admin_summernote_modal', {
                                slug: 'style'
                            }),
                            type: 'GET',
                            success: function (data) {
                                $('body').append(data);

                                var range = $('[data-editor="postContent"]').data('range');
                                var text = range.toString();
                                var render = text;

                                $('[data-modal="style"]').fadeIn();
                                $('.close', '[data-modal="style"]').click(function () {
                                    $('[data-modal="style"]').remove();
                                });

                                var callback = function () {
                                    $.ajax({
                                        url: Routing.generate('vlabs_cms_admin_summernote_block', {
                                            slug: 'style',
                                            color: $('[data-select="color"]', '[data-modal="style"]').val(),
                                            class: $('[data-select="class"]', '[data-modal="style"]').val(),
                                            text: text
                                        }),
                                        type: 'GET',
                                        success: function (html) {
                                            render = html;
                                            $('[data-preview="text"]', '[data-modal="style"]').html(render);
                                        }
                                    });
                                };

                                $('[data-select="class"]', '[data-modal="style"]').select2({allowClear: true})
                                    .on('change', callback);

                                $('[data-select="color"]', '[data-modal="style"]').simplecolorpicker()
                                    .on('change', callback);

                                $('button.btn-danger', '[data-modal="style"]').click(function () {
                                    $('[data-modal="style"]').remove();
                                });

                                $('button.btn-success', '[data-modal="style"]').click(function () {
                                    range.pasteHTML(render);
                                    $('[data-editor="postContent"]').val($('[data-editor="postContent"]').summernote('code'));
                                    $('[data-modal="style"]').remove();
                                    $('[data-editor="postContent"]').summernote('undo');
                                    $('[data-editor="postContent"]').summernote('redo');
                                });

                                callback();

                            }
                        });
                    }
                }).render();
            });
        }
    });
}));
