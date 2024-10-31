jQuery(document).ready(function($) {
    var ucl_listing_images;
     
    jQuery('.uclwp-images-field').on('click', '.upload_image_button', function( event ){
     
        event.preventDefault();
     
        var parentid = jQuery(this).closest('.uclwp-images-field').attr('id');
        var fieldname = jQuery(this).data('fieldname');
        // Create the media frame.
        ucl_listing_images = wp.media.frames.ucl_listing_images = wp.media({
          title: jQuery( this ).data( 'title' ),
          button: {
            text: jQuery( this ).data( 'btntext' ),
          },
          library: {
                type: [ 'image' ]
          },
          multiple: true
        });
     
        // When an image is selected, run a callback.
        ucl_listing_images.on( 'select', function() {
            // We set multiple to false so only get one image from the uploader
            var selection = ucl_listing_images.state().get('selection');
            selection.map( function( attachment ) {
                attachment = attachment.toJSON();
                var thumb_box = ucl_upload_file_preview(attachment, fieldname);
                jQuery('#'+parentid).find('.thumbs-prev').append(thumb_box);
            });  
        });
     
        // Finally, open the modal
        ucl_listing_images.open();
    });

    jQuery(".thumbs-prev").sortable({
        start: function(e, ui){
            ui.placeholder.height(ui.item.find('.ucl-preview-image').innerHeight()-10);
            ui.placeholder.width(ui.item.find('.ucl-preview-image').innerWidth()-10);
        },
        placeholder: "drag-placeholder col-sm-3"
    });
    jQuery('.thumbs-prev').on('click', '.remove-image', function(e) {
        e.preventDefault();
        jQuery(this).closest('.col-sm-3').remove();
    });
});

function ucl_upload_file_preview(attachment, fieldname){
    var html = '<div class="col-sm-3">';
            html += '<div class="ucl-preview-image">';
                html += '<input type="hidden" name="'+fieldname+'['+attachment.id+']" value="'+attachment.id+'">';
                html += '<div class="ucl-image-wrap">';
                	html += '<img src="'+attachment.url+'">';
                html += '</div>';
                html += '<div class="ucl-actions-wrap">';
                    html += '<a href="javascript:void(0)" class="btn remove-image btn-sm">';
                        html += '<i class="bi bi-trash3"></i>';
                    html += '</a>';
                html += '</div>';
            html += '</div>';
        html += '</div>';

    return html;
}