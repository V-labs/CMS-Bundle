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
        'vlabs-template': function (context) {

            context.memo('button.vlabs-template', function () {

                return $.summernote.ui.button({
                    contents: '<i class="fa fa-object-group"></i>',
                    tooltip: 'Insérer un gabarit',
                    click: function () {

                        var range = $('[data-editor="postContent"]').summernote('createRange');
                        $('[data-editor="postContent"]').data('range', range);
                        $.ajax({
                            url: Routing.generate('vlabs_cms_admin_summernote_modal', {
                                slug: 'template'
                            }),
                            type: 'GET',
                            success: function (data) {
                                $('body').append(data);

                                $('[data-modal="template"]').fadeIn();
                                $('.close', '[data-modal="template"]').click(function () {
                                    $('[data-modal="template"]').remove();
                                });

                                // close button
                                $('button.btn-danger', '[data-modal="template"]').click(function () {
                                    $('[data-modal="template"]').remove();
                                });

                                // template choice
                                $('[data-select="template"]', '[data-modal="template"]').select2({
                                    placeholder: 'Sélectionnez un gabarit'
                                });

                                $('[data-select="template"]', '[data-modal="template"]').on('change', function () {
                                    if ($(this).val() == 'block_text_image_left' || $(this).val() == 'block_text_image_right') {
                                        $('.color-container', '[data-modal="template"]').hide();
                                    } else {
                                        $('.color-container', '[data-modal="template"]').show();
                                    }
                                });

                                // colorpicker
                                $('[data-select="color"]', '[data-modal="template"]').simplecolorpicker();

                                $('button.btn-success', '[data-modal="template"]').click(function () {

                                    if ($('[data-select="template"]', '[data-modal="template"]').val()) {
                                        $.ajax({
                                            url: Routing.generate('vlabs_cms_admin_summernote_block', {
                                                slug: 'template_' + $('.form-group:first-child select', '[data-modal="template"]').val(),
                                                color: $('[data-select="color"]', '[data-modal="template"]').val()
                                            }),
                                            type: 'GET',
                                            success: function (html) {
                                                $('[data-editor="postContent"]').data('range').pasteHTML(html, true);
                                                $('[data-editor="postContent"]').val($('[data-editor="postContent"]').summernote('code'));
                                                $('[data-modal="template"]').remove();
                                            }
                                        });
                                    }
                                });
                            }
                        });
                    }
                }).render();
            });
        }
    });
}));
