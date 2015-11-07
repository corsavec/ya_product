jQuery(function($) {

    var file_frame;

    $(document).on('click', '#image-uploader-meta-box a.yaprod-add', function(e) {

        e.preventDefault();

        if (file_frame) file_frame.close();

        file_frame = wp.media.frames.file_frame = wp.media({
            title: $(this).data('uploader-title'),
            // Tell the modal to show only images.
            library: {
                type: 'image'
            },
            button: {
                text: $(this).data('uploader-button-text'),
            },
            multiple: false
        });

        file_frame.on('select', function() {
            var listIndex = $('#image-uploader-meta-box-list li').index($('#image-uploader-meta-box-list li:last')),
                selection = file_frame.state().get('selection');

            selection.map(function(attachment) {
                attachment = attachment.toJSON(),

                    index      = listIndex;

                $('#image-uploader-meta-box-list').append('<li><input type="hidden" name="yaprod" value="' + attachment.id + '"><img class="image-preview" src="' + attachment.sizes.thumbnail.url + '"></li>');

                $('input[name="_yaprod"]').val(attachment.url);

                $('#image-uploader-meta-box a.yaprod-add').addClass('none');
                $('a.change-image').removeClass('none').show();
                $('a.remove-image').removeClass('none').show();

            });
        });

        makeSortable();

        file_frame.open();

    });

    $(document).on('click', '#image-uploader-meta-box a.change-image', function(e) {

        e.preventDefault();

        var that = $(this);

        if (file_frame) file_frame.close();

        file_frame = wp.media.frames.file_frame = wp.media({
            title: $(this).data('uploader-title'),
            button: {
                text: $(this).data('uploader-button-text'),
            },
            multiple: false
        });

        file_frame.on( 'select', function() {
            attachment = file_frame.state().get('selection').first().toJSON();

            that.parent().find('input:hidden').attr('value', attachment.id);
            that.parent().find('img.image-preview').attr('src', attachment.sizes.thumbnail.url);

        });

        file_frame.open();

    });


    function resetIndex() {
        $('#image-uploader-meta-box-list li').each(function(i) {
            $(this).find('input:hidden').attr('name', 'yaprod');
        });
    }

    function makeSortable() {
        $('#image-uploader-meta-box-list').sortable({
            opacity: 0.6,
            stop: function() {
                resetIndex();
            }
        });
    }

    $(document).on('click', '#image-uploader-meta-box a.remove-image', function(e) {


        $('#image-uploader-meta-box a.yaprod-add').removeClass('none');
        $('a.change-image').hide();
        $(this).hide();

        $('input[name="yaprod"]').val('');
        $('input[name="_yaprod"]').val('');

        $('#image-uploader-meta-box-list li').animate({ opacity: 0 }, 200, function() {
            $(this).remove();
            resetIndex();
        });

        e.preventDefault();

    });

    makeSortable();

});