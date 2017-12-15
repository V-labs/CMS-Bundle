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
        'vlabs-pdf': function (context) {

            context.memo('button.vlabs-pdf', function () {

                return $.summernote.ui.button({
                    contents: '<i class="fa fa-file-pdf-o"></i>',
                    tooltip: 'Insérer un document PDF',
                    click: function () {

                        var range = $('[data-editor="postContent"]').summernote('createRange');
                        $('[data-editor="postContent"]').data('range', range);
                        $.ajax({
                            url: Routing.generate('vlabs_cms_admin_summernote_modal', {
                                slug: 'pdf'
                            }),
                            type: 'GET',
                            success: function (data) {
                                $('body').append(data);

                                $('[data-modal="pdf"]').fadeIn();
                                $('.close', '[data-modal="pdf"]').click(function () {
                                    $('[data-modal="pdf"]').remove();
                                });

                                $('select', '[data-modal="pdf"]').select2({
                                    placeholder: 'Sélectionnez un document PDF existant'
                                }).on('change', function () {
                                    $.ajax({
                                        url: Routing.generate('vlabs_cms_admin_summernote_block', {
                                            slug: 'pdf',
                                            id: $(this).val()
                                        }),
                                        type: 'GET',
                                        success: function (html) {
                                            $('[data-editor="postContent"]').data('range').pasteHTML(html);
                                            $('[data-editor="postContent"]').val($('[data-editor="postContent"]').summernote('code'));
                                            $('[data-modal="pdf"]').remove();
                                        }
                                    });
                                });

                                $('input[type="file"]', '[data-modal="pdf"]').fileupload({
                                    acceptFileTypes: /(\.|\/)(pdf)$/i,
                                    maxFileSize: 5000000,
                                    url: Routing.generate('vlabs_cms_admin_summernote_media')
                                }).bind('fileuploadalways', function (e, data) {
                                    if (201 === data.jqXHR.status) {
                                        $.ajax({
                                            url: Routing.generate('vlabs_cms_admin_summernote_block', {
                                                slug: 'pdf',
                                                id: data.result
                                            }),
                                            type: 'GET',
                                            success: function (html) {
                                                $('[data-editor="postContent"]').data('range').pasteHTML(html);
                                                $('[data-editor="postContent"]').val($('[data-editor="postContent"]').summernote('code'));
                                                $('[data-modal="pdf"]').remove();
                                            }
                                        });
                                    }
                                }).bind('fileuploadprocessfail', function (e, data) {
                                    if (data.files[0].error == 'File is too large') {
                                        swal('Erreur', 'Fichier inférieur à 5 Mo requis', 'error');
                                    }
                                    if (data.files[0].error == 'File type not allowed') {
                                        swal('Erreur', 'Fichier de type PDF requis', 'error');
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
