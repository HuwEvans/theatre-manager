jQuery(document).ready(function($) {
    // General media selector for multiple images/videos
    $('#tm_media_button').click(function(e) {
        e.preventDefault();

        var frame = wp.media({
            title: 'Select Media',
            button: {
                text: 'Use selected media'
            },
            multiple: true
        });

        frame.on('select', function() {
            var selection = frame.state().get('selection');
            var urls = [];
            var previewContainer = $('#tm_media_preview');
            previewContainer.empty();

            selection.each(function(attachment) {
                attachment = attachment.toJSON();
                urls.push(attachment.url);

                if (attachment.type === 'image') {
                    previewContainer.append('<img src="' + attachment.url + '" style="max-width:150px; margin:5px;" />');
                } else if (attachment.type === 'video') {
                    previewContainer.append('<video src="' + attachment.url + '" controls style="max-width:150px; margin:5px;"></video>');
                }
            });

            $('#tm_media_urls').val(urls.join(','));
        });

        frame.open();
    });

    // Logo selector
    $('#tm_logo_button').click(function(e) {
        e.preventDefault();

        var frame = wp.media({
            title: 'Select Logo Image',
            button: {
                text: 'Use this logo'
            },
            multiple: false
        });

        frame.on('select', function() {
            var attachment = frame.state().get('selection').first().toJSON();
            $('#tm_logo').val(attachment.url);
            $('#tm_logo_preview').attr('src', attachment.url).show();
        });

        frame.open();
    });

    // Banner selector
    $('#tm_banner_button').click(function(e) {
        e.preventDefault();

        var frame = wp.media({
            title: 'Select Banner Image',
            button: {
                text: 'Use this banner'
            },
            multiple: false
        });

        frame.on('select', function() {
            var attachment = frame.state().get('selection').first().toJSON();
            $('#tm_banner').val(attachment.url);
            $('#tm_banner_preview').attr('src', attachment.url).show();
        });

        frame.open();
    });
	
    function initMediaSelector() {
        $('.tm-media-selector').off('click').on('click', function(e) {
            e.preventDefault();
            const input = $(this);
            const previewId = input.data('preview');
            const customUploader = wp.media({
                title: 'Select Image',
                button: { text: 'Use this image' },
                multiple: false
            }).on('select', function() {
                const attachment = customUploader.state().get('selection').first().toJSON();
                input.val(attachment.url);
                if (previewId) {
                    $('#' + previewId).attr('src', attachment.url);
                }
            }).open();
        });
    }

    initMediaSelector();

    // Media selector for image fields
    $('.tm-media-selector').on('click', function(e) {
        e.preventDefault();
        var input = $(this);
        var previewId = input.data('preview');
        var customUploader = wp.media({
            title: 'Select Image',
            button: { text: 'Use this image' },
            multiple: false
        }).on('select', function() {
            var attachment = customUploader.state().get('selection').first().toJSON();
            input.val(attachment.url);
            if (previewId) {
                $('#' + previewId).attr('src', attachment.url);
            }
        }).open();
    });

    // Initialize color picker
    if ($('.tm-color-picker').length > 0) {
        $('.tm-color-picker').wpColorPicker();
    }

    // Initialize date picker
    if ($('.tm-datepicker').length > 0) {
        $('.tm-datepicker').datepicker({
            dateFormat: 'yy-mm-dd'
        });
    }


    // Initialize WordPress color pickers
    $('.tm-color-picker').wpColorPicker();

});

jQuery(document).ready(function($) {
    $('.tm-media-button').on('click', function (e) {
        e.preventDefault();
        const button = $(this);
        const targetId = button.data('target');
        const previewId = button.data('preview');

        const customUploader = wp.media({
            title: 'Select Image',
            button: { text: 'Use this image' },
            multiple: false
        });

        customUploader.on('select', function () {
            const attachment = customUploader.state().get('selection').first().toJSON();
            $('#' + targetId).val(attachment.url);
            if (previewId) {
                $('#' + previewId).attr('src', attachment.url).show();
            }
        });

        customUploader.open();
    });
});
