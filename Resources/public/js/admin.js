
// summernote: disable table resizing on firefox
if(navigator.userAgent.toLowerCase().indexOf('firefox') > -1) {
    document.designMode = 'on';
    document.execCommand('enableObjectResizing', false, 'false');
    document.execCommand('enableInlineTableEditing', false, 'false');
    document.designMode = 'off';
}

$(function () {

    var locale = $('html').attr('lang');

    $.fn.select2.defaults.set('language', locale);

    bootbox.setDefaults({ locale: "fr" });

    $('[data-toggle="tooltip"]').tooltip();

    if (location.hash) {
        $(location.hash).addClass('in');
        $('[href="' + location.hash + '"]').attr('aria-expanded', true);
    }
    $('[data-toggle="collapse"]').click(function () {
        location.hash = $(this).attr('href');
    });

    $('[data-new="category"]').click(function () {
        var $this = $(this);
        bootbox.prompt({
            title: $this.data('title'),
            placeholder: $this.data('placeholder'),
            callback: function(name) {
                if (!name || name == $this.data('name')) return;
                $.ajax({
                    url: Routing.generate('vlabs_cms_admin_category_new'),
                    type: 'POST',
                    data: {
                        name: name,
                        parent: $this.data('id')
                    },
                    success: function () {
                        if ($this.data('id')) {
                            location.hash = 'category-' + $this.data('id');
                        }
                        location.reload();
                    }
                });
            }
        });
    });

    $('[data-delete="category"]').confirmation({
        onConfirm: function (event, element) {
            $.ajax({
                url: Routing.generate('vlabs_cms_admin_category_delete', {
                    id: element.data('id')
                }),
                type: 'DELETE',
                success: function () {
                    location.reload();
                }
            });
        }
    });

    $('[data-sort="category"]').sortable({
        cursor: 'move',
        axis: 'y',
        update: function () {
            $.ajax({
                url: Routing.generate('vlabs_cms_admin_category_sort'),
                type: 'PUT',
                data: $(this).sortable('toArray').toString()
            });
        }
    });

    $('[data-select="categoryParent"]').select2();

    $('[data-editor="postContent"]').summernote({
        lang: locale,
        disableDragAndDrop: true,
        toolbar: [
            ['style', ['bold', 'italic', 'superscript', 'subscript', 'clear']],
            ['history', ['undo', 'redo']],
            ['misc', ['fullscreen']],
            ['layout', ['ul', 'ol']]
        ]
    });

    $('[data-select="postRelatedPosts"]').select2({
        allowClear: true
    });

    $('[data-select="postTags"]').select2({
        allowClear: true,
        tags: true
    });

    $('[data-select="postCategory"]').select2({
        allowClear: true
    });

    $('[data-publish="post"]').confirmation({
        onConfirm: function (event, element) {
            $.ajax({
                url: Routing.generate('vlabs_cms_admin_post_publish', {
                    id: element.data('id')
                }),
                type: 'PUT',
                success: function () {
                    location.reload();
                }
            });
        }
    });

    $('[data-delete="post"]').confirmation({
        onConfirm: function (event, element) {
            $.ajax({
                url: Routing.generate('vlabs_cms_admin_post_delete', {
                    id: element.data('id')
                }),
                type: 'DELETE',
                success: function () {
                    location.reload();
                }
            });
        }
    });

    $('[data-sort="post"]').sortable({
        cursor: 'move',
        axis: 'y',
        update: function () {
            $.ajax({
                url: Routing.generate('vlabs_cms_admin_post_sort'),
                type: 'PUT',
                data: $(this).sortable('toArray').toString()
            });
        }
    });


});