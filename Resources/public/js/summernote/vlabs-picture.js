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
        'vlabs-picture': function (context) {

            context.memo('button.vlabs-picture', function () {

                return $.summernote.ui.button({
                    contents: '<i class="fa fa-picture-o"></i>',
                    tooltip: 'Insérer une image',
                    click: function () {

                        var range = $('[data-editor="postContent"]').summernote('createRange');
                        $('[data-editor="postContent"]').data('range', range);
                        $.ajax({
                            url: Routing.generate('vlabs_cms_admin_summernote_modal', {
                                slug: 'picture'
                            }),
                            type: 'GET',
                            success: function (data) {
                                var $modal = $(data);

                                $modal.find('.close').click(function(){
                                    $modal.fadeOut();
                                });

                                $modal.find('select').select2({
                                    placeholder: 'Sélectionnez une image existante',
                                    templateResult: function (picture) {
                                        if (typeof picture.id == 'undefined') return;
                                        var $el = $(picture.element);
                                        return $('<span><img src="' + $el.data('src') + '"> ' + $el.text() + '</span>');
                                    }
                                }).on('change', function () {
                                    $.ajax({
                                        url: Routing.generate('vlabs_cms_admin_summernote_block', {
                                            slug: 'picture',
                                            id: $(this).val()
                                        }),
                                        type: 'GET',
                                        success: function (html) {
                                            $('[data-editor="postContent"]').data('range').pasteHTML(html);
                                            $('[data-editor="postContent"]').val($('[data-editor="postContent"]').summernote('code'));
                                            $modal.fadeOut();
                                        }
                                    });
                                });

                                $modal.find('input[type="file"]').fileupload({
                                    acceptFileTypes: /(\.|\/)(jpe?g|png)$/i,
                                    maxFileSize: 5000000,
                                    url: Routing.generate('vlabs_cms_admin_summernote_media')
                                }).bind('fileuploadalways', function (e, data) {
                                    if (201 === data.jqXHR.status) {
                                        $.ajax({
                                            url: Routing.generate('vlabs_cms_admin_summernote_block', {
                                                slug: 'picture',
                                                id: data.result
                                            }),
                                            type: 'GET',
                                            success: function (html) {
                                                $('[data-editor="postContent"]').data('range').pasteHTML(html);
                                                $('[data-editor="postContent"]').val($('[data-editor="postContent"]').summernote('code'));
                                                $modal.fadeOut();
                                            }
                                        });
                                    }
                                }).bind('fileuploadprocessfail', function (e, data) {
                                    if (data.files[0].error == 'File is too large') {
                                        swal('Erreur', 'Fichier inférieur à 5 Mo requis', 'error');
                                    }
                                    if (data.files[0].error == 'File type not allowed') {
                                        swal('Erreur', 'Fichier de type image requis', 'error');
                                    }
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
