jQuery(document).ready(function($){
 
 
    $('.button-uploader').click(function(e) {
        var $buttonClicked = $(e.currentTarget);
        e.preventDefault();
        var custom_uploader;
 
        //Extend the wp.media object
        custom_uploader = wp.media.frames.file_frame = wp.media({
            title: 'Choose Image',
            button: {
                text: 'Choose Image'
            },
            multiple: false
        });
 
        //When a file is selected, grab the URL and set it as the text field's value
        custom_uploader.on('select', function() {
            attachment = custom_uploader.state().get('selection').first().toJSON();
            $buttonClicked.prev().val(attachment.url);
        });
 
        //Open the uploader dialog
        custom_uploader.open();
 
    });
 
 
});