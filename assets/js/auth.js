jQuery(document).ready(function($) {
	$('.ucl-login-form').submit(function(event) {
		event.preventDefault();
		Swal.fire('', ucl_auth_vars.wait_text, 'info');
		var loginData = $(this).serialize();

		$.post(ucl_auth_vars.ajaxurl, loginData, function(resp) {
			Swal.fire('', resp.message, resp.status);

			if (resp.status == 'success') {
				window.location.reload();
			};

		}, "json");
	});

	$('.ucl-register-form').submit(function(event) {
		event.preventDefault();

		if ($('input[name="seller_password"]').val() == $('input[name="seller_repassword"]').val()) {
		    Swal.fire('', ucl_auth_vars.wait_text, 'info');
		    var registerData = new FormData(this);
		    registerData.append("action", 'uclwp_seller_register');

		    $.ajax({
		        url: ucl_auth_vars.ajaxurl,
		        data: registerData,
		        processData: false,
		        contentType: false,
		        type: 'POST',
		        success: function(resp){
        			var resp = $.parseJSON(resp);
        			Swal.fire('', resp.message, resp.status);
        	    	if (resp.status == 'success') {
        	    		setTimeout(function() {
        	    			window.location.reload();
        	    		}, 2000);
        	    	};
		        }
		    });

		} else {
		    Swal.fire('', ucl_auth_vars.mismatch_text, "error");
		}
	});

	$("#uclwp_seller_image").change(function(){
	    if (this.files && this.files[0]) {
	        var allowedTypes = ['jpg', 'jpeg', 'png'];
	        var allowedSize = 5;
	        var fileSize = ((this.files[0].size/1024)/1024).toFixed(4); // MB
	        if (fileSize <= allowedSize) {

	            console.log($(this).val().split('.').pop().toLowerCase());
	            if ($.inArray($(this).val().split('.').pop().toLowerCase(), allowedTypes) == -1) {
	                var types = allowedTypes.map(function(type){
	                    return "<code>" + type + "</code>";
	                }).join(",");                    
	                $('.ucl-status').html(ucl_auth_vars.file_format_error+' '+types);
	                $('.ucl-status').show();
	                $('.seller-dp-prev img').attr('src', '');
	                $(this).val('');
	            } else {
	                
	                $('.ucl-status').hide();
	                $('.ucl-status').html('');
	                var reader = new FileReader();
	                reader.onload = function (e) {
	                    $('.seller-dp-prev img').attr('src', e.target.result);
	                }

	                reader.readAsDataURL(this.files[0]);
	            }
	        } else{
	            $('.ucl-status').html(ucl_auth_vars.file_size_error+' '+allowedSize+'MB');
	            $('.seller-dp-prev img').attr('src', '');
	            $(this).val('');
	        }
	    }
	});
});