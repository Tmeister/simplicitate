jQuery(document).ready(function($) {
	
	var options = '<option selected>Site Navigation..</option>';
	
	$('.main-nav').find('a').each(function () {
		$this = $(this);
	    var text = $this.text(),
	        depth = $this.parent().parents('ul').length,
	        depthChar = '',
	        i = 1;
	        link = $this.attr('href');
	    for (i; i < depth; i++) { depthChar += '&ndash;&nbsp;'; }
	    options += '<option value="'+link+'">' + depthChar + text + '</option>';
	});
	
	$('<select class="menu-mobile" />').append(options).appendTo( '.section-simplicitate-menu .content-pad' );

	$('.menu-mobile').change(function(e){
		var $this = $(this);
		console.log( $this.val() );
		location.href = $this.val();
	});


	$(".full_img a").hover(function(){
		$(this).find('img').stop().animate({opacity : '0.7'}, 200);
		$(this).append('<div class="link"></div>');
		$(this).find('.link').animate({opacity : '1', top : '0px'}, 200);

	}, function(){
		$(this).find('img').stop().animate({opacity : '1'}, 200);
		$(this).find('.link').animate({opacity : '0', top : '200px'}, 200 ,function(){ 
			$(this).remove(); 
		});
		$(this).find('.link').css({top: '-200px;'});
	});

});