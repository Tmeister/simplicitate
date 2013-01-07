jQuery(document).ready(function() {
	
	var options = '<option selected>Site Navigation..</option>';
	
	jQuery('.main-nav').find('a').each(function () {
		$this = jQuery(this);
	    var text = $this.text(),
	        depth = $this.parent().parents('ul').length,
	        depthChar = '',
	        i = 1;
	        link = $this.attr('href');
	    for (i; i < depth; i++) { depthChar += '&ndash;&nbsp;'; }
	    options += '<option value="'+link+'">' + depthChar + text + '</option>';
	});
	
	jQuery('<select class="menu-mobile" />').append(options).appendTo( '.section-simplicitate-menu .content-pad' );

	jQuery('.menu-mobile').change(function(e){
		var $this = jQuery(this);
		console.log( $this.val() );
		location.href = $this.val();
	});


	jQuery(".full_img a").hover(function(){
		jQuery(this).find('img').stop().animate({opacity : '0.7'}, 200);
		jQuery(this).append('<div class="link"></div>');
		jQuery(this).find('.link').animate({opacity : '1', top : '0px'}, 200);

	}, function(){
		jQuery(this).find('img').stop().animate({opacity : '1'}, 200);
		jQuery(this).find('.link').animate({opacity : '0', top : '200px'}, 200 ,function(){ 
			jQuery(this).remove(); 
		});
		jQuery(this).find('.link').css({top: '-200px;'});
	});

});