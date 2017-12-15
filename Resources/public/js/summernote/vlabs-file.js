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
        'vlabs-file': function (context) {

            context.memo('button.vlabs-file', function () {

                return $.summernote.ui.button({
                    contents: '<i class="fa fa-file-o"></i>',
                    tooltip: 'Insérer un document',
                    click: function () {

                        var range = $('[data-editor="postContent"]').summernote('createRange');
                        $('[data-editor="postContent"]').data('range', range);
                        $.ajax({
                            url: Routing.generate('vlabs_cms_admin_summernote_modal', {
                                slug: 'file'
                            }),
                            type: 'GET',
                            success: function (data) {
                                $('body').append(data);

                                $('[data-modal="file"]').fadeIn();
                                $('.close', '[data-modal="file"]').click(function () {
                                    $('[data-modal="file"]').remove();
                                });

                                $('select', '[data-modal="file"]').select2({
                                    placeholder: 'Sélectionnez un document existant'
                                }).on('change', function () {
                                    $.ajax({
                                        url: Routing.generate('vlabs_cms_admin_summernote_block', {
                                            slug: 'file',
                                            id: $(this).val()
                                        }),
                                        type: 'GET',
                                        success: function (html) {
                                            $('[data-editor="postContent"]').data('range').pasteHTML(html);
                                            $('[data-editor="postContent"]').val($('[data-editor="postContent"]').summernote('code'));
                                            $('[data-modal="file"]').remove();
                                        }
                                    });
                                });

                                $('input[type="file"]', '[data-modal="file"]').fileupload({
                                    maxFileSize: 5000000,
                                    url: Routing.generate('vlabs_cms_admin_summernote_media')
                                }).bind('fileuploadalways', function (e, data) {
                                    if (201 === data.jqXHR.status) {
                                        $.ajax({
                                            url: Routing.generate('vlabs_cms_admin_summernote_block', {
                                                slug: 'file',
                                                id: data.result
                                            }),
                                            type: 'GET',
                                            success: function (html) {
                                                $('[data-editor="postContent"]').data('range').pasteHTML(html);
                                                $('[data-editor="postContent"]').val($('[data-editor="postContent"]').summernote('code'));
                                                $('[data-modal="file"]').remove();
                                            }
                                        });
                                    }
                                }).bind('fileuploadprocessfail', function (e, data) {
                                    if (data.files[0].error == 'File is too large') {
                                        swal('Erreur', 'Fichier inférieur à 5 Mo requis', 'error');
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
