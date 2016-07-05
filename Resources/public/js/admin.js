
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
            ['misc', ['fullscreen', 'codeview']],
            ['layout', ['ul', 'ol', 'paragraph']],
            ['vlabs', ['vlabs-mailto', 'vlabs-url', 'vlabs-style', 'vlabs-link', 'vlabs-pdf', 'vlabs-picture', 'vlabs-template', 'vlabs-video']]
        ],
        popover: {
            image: [
                ['imagesize', ['imageSize100', 'imageSize50', 'imageSize25']],
                ['float', ['floatLeft', 'floatCenter', 'floatRight', 'floatNone']],
                ['remove', ['removeMedia']]
            ]
        },
        hint: {
            match: /:(\w*)$/,
            search: function (keyword, callback) {
                var range = $('[data-editor="postContent"]').summernote('createRange');

                var actions = [];

                var $thead = $(range.nodes()[0]).closest('thead');
                if ($thead.length > 0) {
                    actions.push('Supprimer l\'entête');
                }

                var $tbody = $(range.nodes()[0]).closest('tbody');
                if ($tbody.length > 0) {
                    actions.push('Ajouter une ligne');
                    actions.push('Supprimer cette ligne');
                }

                var $block = $(range.nodes()[0]).closest('p');
                if ($block.length > 0) {
                    actions.push('Supprimer le bloc');
                }

                var $block = $(range.nodes()[0]).closest('.block');
                if ($block.length > 0) {
                    actions.push('Ajouter un bloc au dessus');
                    actions.push('Ajouter un bloc en dessous');
                } else {
                    var $list = $(range.nodes()[0]).closest('ul,ol');
                    if ($list.length > 0) {
                        actions.push('Ajouter un bloc au dessus');
                        actions.push('Ajouter un bloc en dessous');
                    }
                }

                callback($.grep(actions, function (item) {
                    $('[data-editor="postContent"]').data('range', range);
                    return item.indexOf(keyword) == 0;
                }));
            },
            content: function (item) {
                var range = $('[data-editor="postContent"]').data('range');
                if (item == 'Supprimer le bloc') {
                    var $block = $(range.nodes()[0]).closest('p');
                    if ($block.length > 0) {
                        $block.remove();
                    }
                }
                if (item == 'Ajouter un bloc au dessus') {
                    var $block = $(range.nodes()[0]).closest('.block');
                    if ($block.length > 0) {
                        $('<p><br></p>').insertBefore($block);
                    } else {
                        var $list = $(range.nodes()[0]).closest('ul,ol');
                        if ($list.length > 0) {
                            $('<p><br></p>').insertBefore($list);
                        }
                    }
                }
                if (item == 'Ajouter un bloc en dessous') {
                    var $block = $(range.nodes()[0]).closest('.block');
                    if ($block.length > 0) {
                        $('<p><br></p>').insertAfter($block);
                    } else {
                        var $list = $(range.nodes()[0]).closest('ul,ol');
                        if ($list.length > 0) {
                            $('<p><br></p>').insertAfter($list);
                        }
                    }
                }
                if (item == 'Supprimer l\'entête') {
                    var $tr = $(range.nodes()[0]).closest('tr');
                    if ($tr.length > 0) {
                        $tr.remove();
                    }
                }
                if (item == 'Ajouter une ligne') {
                    var $tr = $(range.nodes()[0]).closest('tr');
                    if ($tr.length > 0) {
                        var $trClone = $tr.clone();
                        $trClone.find('td').html('<p><br></p>');
                        $trClone.insertAfter($tr);
                    }
                }
                if (item == 'Supprimer cette ligne') {
                    var $tr = $(range.nodes()[0]).closest('tr');
                    if ($tr.length > 0) {
                        $tr.remove();
                    }
                }
                $('[data-editor="postContent"]').val($('[data-editor="postContent"]').summernote('code'));
                return $('<br>')[0];
            }
        }
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