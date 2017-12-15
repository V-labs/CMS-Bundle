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
        'vlabs-word': function (context) {

            context.memo('button.vlabs-word', function () {

                return $.summernote.ui.button({
                    contents: '<i class="fa fa-file-word-o"></i>',
                    tooltip: 'Insérer un document Word',
                    click: function () {

                        var range = $('[data-editor="postContent"]').summernote('createRange');
                        $('[data-editor="postContent"]').data('range', range);
                        $.ajax({
                            url: Routing.generate('vlabs_cms_admin_summernote_modal', {
                                slug: 'word'
                            }),
                            type: 'GET',
                            success: function (data) {
                                $('body').append(data);

                                $('[data-modal="word"]').fadeIn();
                                $('.close', '[data-modal="word"]').click(function () {
                                    $('[data-modal="word"]').remove();
                                });

                                $('select', '[data-modal="word"]').select2({
                                    placeholder: 'Sélectionnez un document Word existant'
                                }).on('change', function () {
                                    $.ajax({
                                        url: Routing.generate('vlabs_cms_admin_summernote_block', {
                                            slug: 'word',
                                            id: $(this).val()
                                        }),
                                        type: 'GET',
                                        success: function (html) {
                                            $('[data-editor="postContent"]').data('range').pasteHTML(html);
                                            $('[data-editor="postContent"]').val($('[data-editor="postContent"]').summernote('code'));
                                            $('[data-modal="word"]').remove();
                                        }
                                    });
                                });

                                $('input[type="file"]', '[data-modal="word"]').fileupload({
                                    acceptFileTypes: /(\.|\/)(docx|doc)$/i,
                                    maxFileSize: 5000000,
                                    url: Routing.generate('vlabs_cms_admin_summernote_media')
                                }).bind('fileuploadalways', function (e, data) {
                                    if (201 === data.jqXHR.status) {
                                        $.ajax({
                                            url: Routing.generate('vlabs_cms_admin_summernote_block', {
                                                slug: 'word',
                                                id: data.result
                                            }),
                                            type: 'GET',
                                            success: function (html) {
                                                $('[data-editor="postContent"]').data('range').pasteHTML(html);
                                                $('[data-editor="postContent"]').val($('[data-editor="postContent"]').summernote('code'));
                                                $('[data-modal="word"]').remove();
                                            }
                                        });
                                    }
                                }).bind('fileuploadprocessfail', function (e, data) {
                                    if (data.files[0].error == 'File is too large') {
                                        swal('Erreur', 'Fichier inférieur à 5 Mo requis', 'error');
                                    }
                                    if (data.files[0].error == 'File type not allowed') {
                                        swal('Erreur', 'Document Word requis', 'error');
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
