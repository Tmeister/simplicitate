<?php
/*
Section: Kenburner Slider for Simplicitate
Author: Enrique Chavez
Author URI: http://tmeister.net
Version: 1.2.3
External: http://tmeister.net/themes-and-sections/kenburner-slider/
Demo: http://pagelines.tmeister.net/kenburner-slider/
Description: The Kenburner Slider gives you the chance to display rich content in a simple, elegant and colorful way, thanks to Kenburn effect and text animations. The slider also supports YouTube and Vimeo video embedding. With Kenburner Slider you can forget the usual slider and replace it with a powerful presentation tool.
Class Name: TmKenBurnSlider
Cloning: false
Workswith: header, templates
*/


class TmKenBurnSlider extends PageLinesSection {

	var $domain               = 'tm_kenburner';
	/**************************************************************************
	* SLIDES
	**************************************************************************/
	var $tax_id               = 'tm_kenburner_sets';
	var $custom_post_type     = 'tm_ken_slide';
	/**************************************************************************
	* CAPTIONS
	**************************************************************************/
	var $tax_cap_id           = 'tm_ken_cap_sets';
	var $custom_cap_post_type = 'tm_ken_cap';

	var $slides = null;

	var $effects = array(
		'wipeup'    => 'Wipe Up',
		'wiperight' => 'Wipe Right',
		'wipedown'  => 'Wipe Down',
		'wipeleft'  => 'Wipe Left',
		'fade'      => 'Fade',
		'fadeup'    => 'Fade Up',
		'faderight' => 'Fade Right',
		'fadedown'  => 'Fade Down',
		'fadeleft'  => 'Fade Left'
	);

	var $styles = array(
		'caption_black'       => 'Black',
		'caption_blue'        => 'Blue',
		'caption_green'       => 'Green',
		'caption_orange'      => 'Orange',
		'caption_red'         => 'Red',
		'caption_white'       => 'White',
		'caption_grey'        => 'Grey',
		'caption_transparent' => 'Transparent',
	);

	function section_persistent(){
		$this->post_type_setup();
		$this->post_meta_setup();

		$this->cap_post_type_setup();

		add_filter('wp_generate_attachment_metadata', array($this, 'create_grayscale_image'));
		add_image_size('thumbnav', 75, 75, true);
	}

	function section_scripts() {
		return array(
			'jquery.easing' => array(
				'file'       => $this->base_url . '/js/jquery.easing.1.3.js',
				'dependancy' => array('jquery'),
				'location'   => 'footer',
				'version'    => '1.3'
			),
			'cssAnimate' => array(
				'file'       => $this->base_url . '/js/jquery.cssAnimate.mini.js',
				'dependancy' => array('jquery'),
				'location'   => 'footer',
				'version'    => '1.1.1'
			),
			'waitforimages' => array(
				'file'       => $this->base_url . '/js/jquery.waitforimages.js',
				'dependancy' => array('jquery'),
				'location'   => 'footer',
				'version'    => '1.3.3'
			),
			'touchwipe' => array(
				'file'       => $this->base_url . '/js/jquery.touchwipe.min.js',
				'dependancy' => array('jquery'),
				'location'   => 'footer',
				'version'    => '1.2.5'
			),
			'kslider' => array(
				'file'       => $this->base_url . '/js/jquery.kslider.js',
				'dependancy' => array('jquery'),
				'location'   => 'footer',
				'version'    => '1.2.1'
			)
		);
	}


	function section_head($clone_id){
		global $post, $pagelines_ID;
		$oset             = array('post_id' => $pagelines_ID, 'clone_id' => $clone_id);
		$tm_sf_bg         = ( ploption('tm_kb_background', $oset) ) ? ploption('tm_kb_background', $oset) : '#f1f1f1';
		$tm_sf_v_bg       = ( ploption('tm_kb_video_bg', $oset) ) ? ploption('tm_kb_video_bg', $oset) : '#f7f7f7';
		$tm_kb_width      = ( ploption('tm_kb_width', $oset) ) ? ploption('tm_kb_width', $oset) : '900';
		$tm_kb_height     = ( ploption('tm_kb_height', $oset) ) ? ploption('tm_kb_height', $oset) : '350';
		$tm_kb_time_bwt   = ( ploption('tm_kb_time_bwt', $oset) ) ? ploption('tm_kb_time_bwt', $oset) : '10';
		$tm_kb_shadow     = ( ploption('tm_kb_shadow', $oset) == 'on' ) ? false : true;
		$tm_kb_touch      = ( ploption('tm_kb_touch', $oset) == 'on' ) ? 'off' : 'on';
		$tm_kb_pause_over = ( ploption('tm_kb_pause_over', $oset) == 'on' ) ? 'off' : 'on';
		$tm_kb_v_title    = ( ploption('tm_kb_video_title', $oset) ) ? ploption('tm_kb_video_title', $oset) : '#333333';
		$tm_kb_v_text     = ( ploption('tm_kb_video_text', $oset) ) ? ploption('tm_kb_video_text', $oset) : '#000000';
		$tm_kb_font_size  = ( ploption('tm_kb_font_size', $oset) ) ? ploption('tm_kb_font_size', $oset) : '25';
		$tm_kb_items      = ( ploption('tm_kb_items', $oset) ) ? ploption('tm_kb_items', $oset) : '10';
		$tm_kb_set        = ( ploption('tm_kb_set', $oset) ) ? ploption('tm_kb_set', $oset) : '';
		$this->slides     = $this->get_posts($this->custom_post_type, $this->tax_id, $tm_kb_set, $tm_kb_items);
		echo load_custom_font( ploption('tm_kb_fonts', $oset), '.text_overlay .kcaption' );

	?>
		<!--[if lt IE 9]><style>.kb_container img{max-width:none !important;}.simple  .kslider-bg {
    border: 1px solid #dddddd;}</style><![endif]-->
		<!-- Dynamic styles for Kenburn -->
		<style>
			#<?php echo $this->id ?> .content{width:100% !important;}
			.text_overlay .kcaption{min-height:<?php echo (int) $tm_kb_font_size -5  ?>px; font-size:<?php echo $tm_kb_font_size ?>px;}
			.kslider_video{background:<?php echo $tm_sf_v_bg ?>;}
			.kslider_video h2{color:<?php echo $tm_kb_v_title ?>;}
			.kslider_video p{color:<?php echo $tm_kb_v_text ?>;}
			.kslider-bg{background:<?php echo $tm_sf_bg ?>;}
			<?php if( ! $tm_kb_shadow ) :?>
			#<?php echo $this->id ?> .texture{background:none;}
			<?php endif; ?>
		</style>
		<script>
			jQuery(function(){
				jQuery('.burner-holder').kslider({
					width:<?php echo $tm_kb_width ?>,
					height:<?php echo $tm_kb_height ?>,
					touchenabled:'<?php echo $tm_kb_touch ?>',
					pauseOnRollOverMain:'<?php echo $tm_kb_pause_over ?>',
					timer:<?php echo $tm_kb_time_bwt; ?>,
					thumbStyle:"bullet",
					bgMargin:0,
					thumbAmount: <?php echo count( $this->slides ) ?>,
					thumbWidth:75,
					thumbHeight:75,
					thumbSpaces:10,
					thumbPadding:0,
					padtop:50,
					bulletXOffset:0,
					bulletYOffset:0,
					shadow:'false',
					repairChromeBug:"on",

				});
			});
		</script>
	<?php
	}

	function section_template( $clone_id = null ) {
		global $post, $pagelines_ID;
		$oset              = array('post_id' => $pagelines_ID, 'clone_id' => $clone_id);
		$tm_kb_shadow      = ( ploption('tm_kb_shadow', $oset) == 'on' ) ? false : true;
		$tm_kb_set         = ( ploption('tm_kb_set', $oset) ) ? ploption('tm_kb_set', $oset) : '';
		$tm_kb_items       = ( ploption('tm_kb_items', $oset) ) ? ploption('tm_kb_items', $oset) : '10';
		
		$slides = ( $this->slides == null ) ? $this->get_posts($this->custom_post_type, $this->tax_id, $tm_kb_set, $tm_kb_items) : $this->slides;
		$current_page_post = $post;

		if( !count($slides) ){
			echo setup_section_notify($this, __('Sorry,there are no slides to display.', $this->domain), get_admin_url().'edit.php?post_type='.$this->custom_post_type, __('Please create some slides', $this->domain));
			return;
		}
		/**********************************************************************
		* We have slider, but check for images
		***********************************************************************/
		$found = false;
		foreach ($slides as $post){
			$inner_oset = array('post_id' => $post->ID);
			$image = plmeta('tm_kbs_image', $inner_oset);
			if( strlen($image) ){$found = true;}
		}
		if( ! $found ){
			echo setup_section_notify($this, __('There are KenBurner Slides, but no one had images.', $this->domain), get_admin_url().'edit.php?post_type='.$this->custom_post_type, 'Please upload images');
			return;
		}
	?>
		<div class="burner-wrapper simple">
			<div class="burner-holder">
				<ul>
					<?php
						foreach ($slides as $post):
							$io = array('post_id' => $post->ID);
							$image = plmeta('tm_kbs_image', $io);
							if ( ! strlen( $image )) {continue;}

							/**************************************************
							* SLIDE NORMAL SETTINGS
							**************************************************/
							$image_bw        = preg_replace('/(\.gif|\.jpg|\.png)/', '_bw$1', $image);
							$image_thumb     = preg_replace('/(\.gif|\.jpg|\.png)/', '-75x75$1', $image);
							$image_thumb_bw        = preg_replace('/(\.gif|\.jpg|\.png)/', '_bw$1', $image_thumb);
							$transition      = ( plmeta('tm_kbs_transition', $io) ) ? plmeta('tm_kbs_transition', $io) : 'fade';
							$startfrom       = ( plmeta('tm_kbs_startfrom', $io) ) ? plmeta('tm_kbs_startfrom', $io) : 'random';
							$endto           = ( plmeta('tm_kbs_endto', $io) ) ? plmeta('tm_kbs_endto', $io) : 'random';
							$zoom            = ( plmeta('tm_kbs_zoom', $io) ) ? plmeta('tm_kbs_zoom', $io) : 'random';
							$zoomfact        = ( plmeta('tm_kbs_zoomfact', $io) ) ? plmeta('tm_kbs_zoomfact', $io) : '1';
							$panduration     = ( plmeta('tm_kbs_panduration', $io) ) ? plmeta('tm_kbs_panduration', $io) : '10';
							$colortransition = ( plmeta('tm_kbs_color', $io) ) ? plmeta('tm_kbs_color', $io) : '2';

							/**************************************************
							* VIDEO SETTINGS
							**************************************************/
							$video_type   = plmeta('tm_kb_ut_type', $io);
							$video_id     = plmeta('tm_kb_ut_id', $io);
							$video_title  = plmeta('tm_kb_ut_title', $io);
							$video_text   = plmeta('tm_kb_ut_text', $io);
							$video_width  = ( plmeta('tm_kb_ut_width', $io) ) ? plmeta('tm_kb_ut_width', $io) : '600';
							$video_height = ( plmeta('tm_kb_ut_height', $io) ) ? plmeta('tm_kb_ut_height', $io) : '300';
							$video_url = ( $video_type == 'youtube' ) ? 'http://www.youtube.com/embed/'.$video_id : 'http://player.vimeo.com/video/'.$video_id;

							/**************************************************
							* CAPTIONS
							**************************************************/

							$caption_set = strlen( trim( plmeta('tm_kbs_caption_set', $io)) ) ? plmeta('tm_kbs_caption_set', $io) : 'null';
							$captions = $this->get_posts($this->custom_cap_post_type, $this->tax_cap_id, $caption_set);


					?>
						<li data-transition="<?php echo $transition; ?>" data-startfrom="<?php echo $startfrom; ?>" data-endto="<?php echo $endto ?>" data-zoom="<?php echo $zoom ?>" data-zoomfact="<?php echo $zoomfact ?>" data-panduration="<?php echo $panduration ?>" data-colortransition="<?php echo $colortransition ?>">
								<img alt="" src="<?php echo $image ?>" data-bw="<?php echo $image_bw ?>" data-thumb="<?php echo $image_thumb; ?>" data-thumb_bw="<?php echo $image_thumb_bw; ?>" />
								<?php if (strlen( $video_type && $video_id )): ?>
									<div class="video_wrapper">
										<div class="video_kenburn_wrap">
											<iframe class="video_clip" src="<?php echo $video_url ?>" width="<?php echo $video_width ?>" height="<?php echo $video_height ?>"></iframe>
											<h2><?php echo $video_title ?></h2>
											<p><?php echo nl2br($video_text) ?></p>
											<div class="close"></div>
										</div>
									</div>
								<?php endif ?>
								<?php if ( count( $captions ) ): ?>
									<div  class="text_overlay">
										<?php
											$current_inner_page_post = $post;
											foreach (array_reverse( $captions ) as $post):
												$ioc = array('post_id' => $post->ID);
												$tm_cp_x         = ( plmeta('tm_kbc_x', $ioc) ) ? plmeta('tm_kbc_x', $ioc) : '25';
												$tm_cp_y         = ( plmeta('tm_kbc_y', $ioc) ) ? plmeta('tm_kbc_y', $ioc) : '25';
												$tm_cp_style     = ( plmeta('tm_kbc_style', $ioc) ) ? plmeta('tm_kbc_style', $ioc) : 'caption_black';
												$tm_cp_animation = ( plmeta('tm_kbc_animation', $ioc) ) ? plmeta('tm_kbc_animation', $ioc) : 'wipeup';
												$tm_cp_animation = ( $tm_cp_animation == 'fade' ) ? 'kfade' : $tm_cp_animation;

										?>
											<div id="caption-id-<?php echo $post->ID ?>" class="kcaption <?php echo $tm_cp_style ?> <?php echo $tm_cp_animation ?>" style="top:<?php echo $tm_cp_y ?>px;left:<?php echo $tm_cp_x ?>px;">
												<?php echo $post->post_content ?>
											</div>
											<!-- END INTERNAL FOREACH -->
										<?php endforeach;$post = $current_inner_page_post; ?>
									</div>
								<?php endif ?>
						</li>
					<?php endforeach;$post = $current_page_post; ?>
				</ul>
				<!-- END MAIN FOREACH -->
			</div>
		</div>
	<?php
	}



	function before_section_template( $clone_id = null ){

	}
	function after_section_template( $clone_id = null ){

	}

	/**************************************************************************
	* CAPTIONS
	**************************************************************************/

	function cap_post_type_setup(){
		$args = array(
			'label'          => __('Kenburner captions', $this->domain),
			'singular_label' => __('Caption', $this->domain),
			'description'    => __('', $this->domain),
			'taxonomies'     => array( $this->tax_cap_id ),
			'menu_icon'      => $this->icon,
		);
		$taxonomies = array(
			$this->tax_cap_id => array(
				'label'          => __('Caption Sets', $this->domain),
				'singular_label' => __('Caption Set', $this->domain),
			)
		);
		$columns = array(
			"cb"              => "<input type=\"checkbox\" />",
			"title"           => "Title",
			$this->tax_cap_id => "Caption Set",
			'style'           => __('Style', $this->domain),
			'effect'          => __('Effect', $this->domain)
		);
		$this->post_type_cap = new PageLinesPostType( $this->custom_cap_post_type, $args, $taxonomies, $columns, array(&$this, 'column_cap_display') );
	}

	function column_cap_display($column){
		global $post;
		switch ($column){
			case $this->tax_cap_id:
				echo get_the_term_list($post->ID, $this->tax_cap_id, '', ', ','');
				break;
			case 'style':

				$style_index = ( m_pagelines('tm_kbc_style', $post->ID) ) ? m_pagelines('tm_kbc_style', $post->ID) : 'caption_black';
				echo $this->styles[$style_index];
				break;
			case 'effect':
				$effect_index = ( m_pagelines('tm_kbc_animation', $post->ID) ) ? m_pagelines('tm_kbc_animation', $post->ID) : 'wipeup';
				echo $this->effects[$effect_index];
				break;
		}
	}

	/**************************************************************************
	* SLIDES
	**************************************************************************/

	function post_type_setup(){
		$args = array(
			'label'          => __('Kenburner slides', $this->domain),
			'singular_label' => __('Slide', $this->domain),
			'description'    => __('', $this->domain),
			'taxonomies'     => array( $this->tax_id ),
			'menu_icon'      => $this->icon,
			'supports'       => 'title'
		);
		$taxonomies = array(
			$this->tax_id => array(
				'label'          => __('Kenburner Sets', $this->domain),
				'singular_label' => __('Kenburner Set', $this->domain),
			)
		);
		$columns = array(
			"cb"              => "<input type=\"checkbox\" />",
			"title"           => "Title",
			"kenburner_media" => "Media",
			$this->tax_id     => "Kenburner Set"
		);
		$this->post_type = new PageLinesPostType( $this->custom_post_type, $args, $taxonomies, $columns, array(&$this, 'column_display') );
	}

	function column_display($column){
		global $post;
		switch ($column){
			case $this->tax_id:
				echo get_the_term_list($post->ID, $this->tax_id, '', ', ','');
				break;
			case 'kenburner_media':
				echo '<img src="'.m_pagelines('tm_kbs_image', $post->ID).'" style="max-width: 300px; max-height: 100px" />';
				break;
		}
	}

	/**************************************************************************
	** SLIDE OPTIONS ( POST META )
	**************************************************************************/

	function post_meta_setup(){
		/**********************************************************************
		** MAIN OPTIONS
		**********************************************************************/
		$pt_tab_options = array(
			'tm_kbs_image' 	=> array(
				'type'       => 'image_upload',
				'inputlabel' => __('Select image', $this->domain),
				'title'      => __('Image', $this->domain) ,
				'shortexp'   => __('Select the image to use.', $this->domain) ,
				'exp'        => __('The image cannot be resized automatically because the slider size is dynamic. The images have to be uploaded in the right size according to the configuration of the slider.<br/>The proper size of the image should be: the slider size plus 100 pixels width and height.<br/>For example, if your slider is 900x400 pixels the image should be 1000x500 pixels.', $this->domain)
			),
			'tm_kbs_transition' 	=> array(
				'type'       => 'select',
				'inputlabel' => __('Transition', $this->domain),
				'title'      => __('Transition', $this->domain) ,
				'shortexp'   => __('Select the transition to use. Default Fade', $this->domain) ,
				'exp'        => __('This transition is unique for each slide and can choose between face or slide.', $this->domain),
				'selectvalues' 	=> array(
					'fade'      => array('name' => __( 'Fade', $this->domain) ),
					'slide'     => array('name' => __( 'Slide', $this->domain) ),
				),
			),
			'tm_kbs_startfrom' 	=> array(
				'type'       => 'select',
				'inputlabel' => __('Starting position effect', $this->domain),
				'title'      => __('Starting position effect', $this->domain) ,
				'shortexp'   => __('Select the starting position. Default: Random', $this->domain) ,
				'exp'        => __('This is the point where the animation begins.', $this->domain),
				'selectvalues' 	=> array(
					'left,top'      => array('name' => __( 'Left - Top', $this->domain) ),
					'left,center'   => array('name' => __( 'Left - Center', $this->domain) ),
					'left.bottom'   => array('name' => __( 'Left - Bottom', $this->domain) ),
					'center,top'    => array('name' => __( 'Center - Top', $this->domain) ),
					'center,center' => array('name' => __( 'Center - Center', $this->domain) ),
					'center,bottom' => array('name' => __( 'Center - Bottom', $this->domain) ),
					'right,top'     => array('name' => __( 'Right - Top', $this->domain) ),
					'right,center'  => array('name' => __( 'Right - Center', $this->domain) ),
					'right,bottom'  => array('name' => __( 'Right - Bottom', $this->domain) ),
					'random' => array('name' => __( 'Random', $this->domain) ),
				),
			),
			'tm_kbs_endto' 	=> array(
				'type'       => 'select',
				'inputlabel' => __('Ending position effect', $this->domain),
				'title'      => __('Ending position effect', $this->domain) ,
				'shortexp'   => __('Select the ending position.', $this->domain) ,
				'exp'        => __('This is the point where the animation ends. Default: Random', $this->domain),
				'selectvalues' 	=> array(
					'left,top'      => array('name' => __( 'Left - Top', $this->domain) ),
					'left,center'   => array('name' => __( 'Left - Center', $this->domain) ),
					'left.bottom'   => array('name' => __( 'Left - Bottom', $this->domain) ),
					'center,top'    => array('name' => __( 'Center - Top', $this->domain) ),
					'center,center' => array('name' => __( 'Center - Center', $this->domain) ),
					'center,bottom' => array('name' => __( 'Center - Bottom', $this->domain) ),
					'right,top'     => array('name' => __( 'Right - Top', $this->domain) ),
					'right,center'  => array('name' => __( 'Right - Center', $this->domain) ),
					'right,bottom'  => array('name' => __( 'Right - Bottom', $this->domain) ),
					'random' => array('name' => __( 'Random', $this->domain) ),
				),
			),
			'tm_kbs_zoom' 	=> array(
				'type'       => 'select',
				'inputlabel' => __('Zoom', $this->domain),
				'title'      => __('Zoom Effect', $this->domain) ,
				'shortexp'   => __('The way to create the zooming effect. Default: Random', $this->domain) ,
				'exp'        => __('You can choose between in, out or random.', $this->domain),
				'selectvalues' 	=> array(
					'in'     => array('name' => __( 'In', $this->domain) ),
					'out'    => array('name' => __( 'Out', $this->domain) ),
					'random' => array('name' => __( 'Random', $this->domain) ),
				),
			),
			'tm_kbs_zoomfact' 	=> array(
				'type' 			=> 'count_select',
				'inputlabel' => __('Zoom Factor', $this->domain),
				'title'      => __('Zoom Factor', $this->domain) ,
				'shortexp'   => __('Default: 1', $this->domain) ,
				'exp'        => __('The amount of zoom applied to the image. 1 = 100%, 2 = 200% etc.', $this->domain),
				'count_start'	=> 1,
 				'count_number'	=> 4,
			),

			'tm_kbs_panduration' 	=> array(
				'type' 			=> 'count_select',
				'inputlabel' => __('Time', $this->domain),
				'title'      => __('Movement Duration', $this->domain) ,
				'shortexp'   => __('Default: 10', $this->domain) ,
				'exp'        => __('The animation motion duration in seconds..', $this->domain),
				'count_start'	=> 5,
 				'count_number'	=> 20,
			),
			'tm_kbs_color' 	=> array(
				'type' 			=> 'count_select',
				'inputlabel' => __('Time', $this->domain),
				'title'      => __('Color Transition Duration', $this->domain) ,
				'shortexp'   => __('Default: 2', $this->domain) ,
				'exp'        => __('Transition color duration between BW & Color image.', $this->domain),
				'count_start'	=> 2,
 				'count_number'	=> 5,
			),
			'tm_kbs_caption_set' 	=> array(
				'type' 			=> 'select_taxonomy',
				'taxonomy_id'	=> $this->tax_cap_id,
				'title' 		=> __('Caption Set', $this->domain),
				'shortexp'		=> __('Select which <strong>caption set</strong> you want to show over the image.', $this->domain),
				'inputlabel'	=> __('Caption Set', $this->domain),
				'exp' 			=> __('Each slide can have several captions on it, choose a caption set to show on this slide.', $this->domain)
			),
		);
		$pt_panel = array(
			'id' 		=> $this->id . '-metapanel',
			'name' 		=> __('Kenburner Main Options', $this->domain),
			'posttype' 	=> array( $this->custom_post_type ),
		);
		$pt_panel =  new PageLinesMetaPanel( $pt_panel );
		$pt_tab = array(
			'id' 		=> $this->id . '-metatab',
			'name' 		=> "Kenburner Main Options",
			'icon' 		=> $this->icon,
		);
		$pt_panel->register_tab( $pt_tab, $pt_tab_options );

		/**********************************************************************
		** VIDEO OPTIONS
		**********************************************************************/
		$pt_tab_options_utube = array(
			'tm_kb_ut_type' 	=> array(
				'type'       => 'select',
				'inputlabel' => __('Service', $this->domain),
				'title'      => __('Video Service', $this->domain) ,
				'shortexp'   => __('What service do you want to use?', $this->domain) ,
				'exp'        => __('You can choose between Youtube or Vimeo Service.', $this->domain),
				'selectvalues' 	=> array(
					'youtube'     => array('name' => __( 'Youtube', $this->domain) ),
					'vimeo'    => array('name' => __( 'Vimeo', $this->domain) ),
				),
			),
			'tm_kb_ut_id' 	=> array(
				'type' 			=> 'text',
				'inputlabel' => __('Video ID', $this->domain),
				'title'      => __('Youtube/Vimeo Video ID', $this->domain) ,
				'shortexp'   => __('Please enter the Youtube/Vimeo Video ID to show.', $this->domain) ,
				'exp'        => __('Just set the video ID for Youtube (http://www.youtube.com/watch?v=<strong>V1bFr2SWP1I</strong>) and for Vimeo (http://vimeo.com/<strong>32527249</strong>)' , $this->domain)
			),
			'tm_kb_ut_size' 	=> array(
				'type'         => 'text_multi',
				'inputlabel'   => '',
				'title'        => __('Size', $this->domain) ,
				'shortexp'     => __('Configure the size for your slider.', $this->domain) ,
				'exp'          => __('Fully resizable, you can set any size.', $this->domain) ,
				'selectvalues' => array(
					'tm_kb_ut_width'  => array('inputlabel'=> __( 'Width', $this->domain )),
					'tm_kb_ut_height' => array('inputlabel'=> __( 'Height', $this->domain ))
				),
			),
			'tm_kb_ut_title' 	=> array(
				'type' 			=> 'text',
				'inputlabel' => __('Title', $this->domain),
				'title'      => __('Title', $this->domain) ,
				'shortexp'   => __('Please set title for the video.', $this->domain) ,
				'exp'        => __('If this option is set, will show at the right side.', $this->domain)
			),
			'tm_kb_ut_text' 	=> array(
				'type' 			=> 'textarea',
				'inputlabel' => __('Description', $this->domain),
				'title'      => __('Description', $this->domain) ,
				'shortexp'   => __('Please set description for the video.', $this->domain) ,
				'exp'        => __('If this option is set, will show at the right side.', $this->domain)
			),
		);
		$pt_panel_utube = array(
			'id' 		=> $this->id . 'utube-metapanel',
			'name' 		=> __('Kenburner Video Options', $this->domain),
			'posttype' 	=> array( $this->custom_post_type ),
		);
		$pt_panel_utube =  new PageLinesMetaPanel( $pt_panel_utube );
		$pt_tab_utube = array(
			'id' 		=> $this->id . 'utube-metatab',
			'name' 		=> "Kenburner Video Options",
			'icon' 		=> $this->base_url . '/icon_video.png',
		);
		$pt_panel_utube->register_tab( $pt_tab_utube, $pt_tab_options_utube );

		/**********************************************************************
		** CAPTIONS OPTIONS
		**********************************************************************/
		$pt_tab_options_captions = array(
			'tm_kbc_x' 	=> array(
				'type' 			=> 'text',
				'inputlabel' => __('Left Position', $this->domain),
				'title'      => __('Left Position', $this->domain) ,
				'shortexp'   => __('Set the absolute left position where the caption will show.', $this->domain) ,
				'exp'        => __('The caption position is relative to the slide area, be careful with these values ​​that the caption could not appear on the image.', $this->domain)
			),
			'tm_kbc_y' 	=> array(
				'type' 			=> 'text',
				'inputlabel' => __('Top Position', $this->domain),
				'title'      => __('Top Position', $this->domain) ,
				'shortexp'   => __('Set the absolute top position where the caption will show.', $this->domain) ,
				'exp'        => __('The caption position is relative to the slide area, be careful with these values ​​that the caption could not appear on the image.', $this->domain)
			),
			'tm_kbc_style'	=> array(
				'type'       => 'select',
				'inputlabel' => __('Style', $this->domain),
				'title'      => __('Style', $this->domain) ,
				'shortexp'   => __('The style to use with the caption.', $this->domain) ,
				'exp'        => __('Choose the caption style, you can always override this style using CSS on your theme.', $this->domain),
				'selectvalues' 	=> array(
					'caption_black'  => array('name' => __( 'Black', $this->domain) ),
					'caption_blue'   => array('name' => __( 'Blue', $this->domain) ),
					'caption_green'  => array('name' => __( 'Green', $this->domain) ),
					'caption_orange' => array('name' => __( 'Orange', $this->domain) ),
					'caption_red'    => array('name' => __( 'Red', $this->domain) ),
					'caption_white'  => array('name' => __( 'White', $this->domain) ),
					'caption_grey'   => array('name' => __( 'Grey', $this->domain) ),
					'caption_transparent'   => array('name' => __( 'Transparent', $this->domain) ),
				),
			),

			'tm_kbc_animation'	=> array(
				'type'       => 'select',
				'inputlabel' => __('Animation Effect', $this->domain),
				'title'      => __('Animation', $this->domain) ,
				'shortexp'   => __('The animation effect to show the caption.', $this->domain) ,
				'exp'        => __('Choose the caption animation type to show up the caption', $this->domain),
				'selectvalues' 	=> array(
					'wipeup'            => array('name' => __( 'Wipe Up', $this->domain) ),
					'wiperight'         => array('name' => __( 'Wipe Right', $this->domain) ),
					'wipedown'          => array('name' => __( 'Wipe Down', $this->domain) ),
					'wipeleft'          => array('name' => __( 'Wipe Left', $this->domain) ),
					'fade'              => array('name' => __( 'Fade', $this->domain) ),
					'fadeup'            => array('name' => __( 'Fade Up', $this->domain) ),
					'faderight'         => array('name' => __( 'Fade Right', $this->domain) ),
					'fadedown'          => array('name' => __( 'Fade Down', $this->domain) ),
					'fadeleft'          => array('name' => __( 'Fade Left', $this->domain) ),
				),
			),
		);



		$pt_panel_cap = array(
			'id' 		=> $this->id . 'cap-metapanel',
			'name' 		=> __('Kenburner Caption Options', $this->domain),
			'posttype' 	=> array( $this->custom_cap_post_type ),
		);
		$pt_panel_cap =  new PageLinesMetaPanel( $pt_panel_cap );
		$pt_tab_cap = array(
			'id' 		=> $this->id . 'cap-metatab',
			'name' 		=> "Kenburner Caption Options",
			'icon' 		=> $this->icon,
		);
		$pt_panel_cap->register_tab( $pt_tab_cap, $pt_tab_options_captions );

	}

	/**************************************************************************
	** SECTION OPTIONS SLIDER ( TEMPLATES UNDER META )
	**************************************************************************/
	function section_optionator( $settings ){

		$settings = wp_parse_args($settings, $this->optionator_default);
		$opt_array = array(
			'tm_kb_size' 	=> array(
				'type'         => 'text_multi',
				'inputlabel'   => '',
				'title'        => __('Size', $this->domain) ,
				'shortexp'     => __('Configure the size for your slider.', $this->domain) ,
				'exp'          => __('Fully resizable, you can set any size.', $this->domain) ,
				'selectvalues' => array(
					'tm_kb_width'  => array('inputlabel'=> __( 'Width', $this->domain )),
					'tm_kb_height' => array('inputlabel'=> __( 'Height', $this->domain ))
				),
			),
			'tm_kb_backgrounds' 	=> array(
				'type'       => 'color_multi',
				'inputlabel' => __('Colors', $this->domain),
				'title'      => __('Color', $this->domain) ,
				'shortexp'   => __('Configure the background color for your slider.', $this->domain) ,
				'exp'        => __('Choose any color from the palette for a full customization.', $this->domain),
				'selectvalues' => array(
					'tm_kb_background'  => array('inputlabel' => __( 'Slider Background', $this->domain) ),
					'tm_kb_video_bg'    => array('inputlabel' => __( 'Video Background', $this->domain) ),
					'tm_kb_video_title' => array('inputlabel' => __( 'Video Title', $this->domain) ),
					'tm_kb_video_text'  => array('inputlabel' => __( 'Video Text', $this->domain) ),
				)
			),
			'tm_kb_fonts' => array(
				'type'		=> 'fonts',
				'title'		=> 'Captions Font',
				'shortexp'	=> 'Select the font.',
				'exp'		=> 'This font will be used on all the captions for the slides.'
			),
			'tm_kb_font_size' => array(
				'type' 			=> 'count_select',
				'inputlabel'	=> __('Font Size', $this->domain),
				'title' 		=> __('Font Size', $this->domain),
				'shortexp'		=> __('Default value is 25px', $this->domain),
				'exp'			=> __('This font size will be used on all the captions for the slides.', $this->domain),
				'count_start'	=> 10,
 				'count_number'	=> 40,
			),
			'tm_kb_set' 	=> array(
				'type' 			=> 'select_taxonomy',
				'taxonomy_id'	=> $this->tax_id,
				'title' 		=> __('Set', $this->domain),
				'shortexp'		=> __('Select which set you want to show.', $this->domain),
				'inputlabel'	=> __('Set', $this->domain),
				'exp' 			=> __('If don\'t select a set or you have not created a set, the slider will show all slides', $this->domain)
			),
			'tm_kb_items' => array(
				'type' 			=> 'count_select',
				'inputlabel'	=> __('Number of Slides', $this->domain),
				'title' 		=> __('Number of Slides', $this->domain),
				'shortexp'		=> __('Default value is 10', $this->domain),
				'exp'			=> __('The amount of slides to show.', $this->domain),
				'count_start'	=> 2,
 				'count_number'	=> 20,
			),
			'tm_kb_time_bwt' => array(
				'type' 			=> 'count_select',
				'inputlabel'	=> __('Time between transitions', $this->domain),
				'title' 		=> __('Transition time', $this->domain),
				'shortexp'		=> __('Default value is 10', $this->domain),
				'exp'			=> __('How long to wait between transitions in seconds.', $this->domain),
				'count_start'	=> 5,
 				'count_number'	=> 20,
			),
			'tm_kb_shadow' 	=> array(
				'type'       => 'check',
				'inputlabel' => __('Disable shadow?', $this->domain),
				'title'      => __('Shadow', $this->domain) ,
				'shortexp'   => __('Set whether to use the shadow of the slider', $this->domain) ,
				'exp'        => __('You can disable the use of shadows in the slider if desired.', $this->domain)
			),
			'tm_kb_touch' 	=> array(
				'type'       => 'check',
				'inputlabel' => __('Disable touch support for mobiles?', $this->domain),
				'title'      => __('Touch Wipe', $this->domain) ,
				'shortexp'   => __('Set whether to use the touch support for mobiles', $this->domain) ,
				'exp'        => __('You can disable the use of touch wipe support for mobiles in the slider if desired.', $this->domain)
			),
			'tm_kb_pause_over' 	=> array(
				'type'       => 'check',
				'inputlabel' => __('Disable Pause on hover?', $this->domain),
				'title'      => __('Pause on hover', $this->domain) ,
				'shortexp'   => __('Set whether to use the pause on hover feature', $this->domain) ,
				'exp'        => __('You can disable the pause on hover if desired.', $this->domain)
			),
		);

		$settings = array(
			'id' 		=> $this->id.'_meta',
			'name' 		=> $this->name,
			'icon' 		=> $this->icon,
			'clone_id'	=> $settings['clone_id'],
			'active'	=> $settings['active']
		);

		register_metatab($settings, $opt_array);
	}

	function get_posts( $custom_post, $tax_id, $set = null, $limit = null){
		$query                 = array();
		$query['orderby']      = 'ID';
		$query['post_type']    = $custom_post;
		$query[ $tax_id ] = $set;

		if(isset($limit)){
			$query['showposts'] = $limit;
		}

		$q = new WP_Query($query);

		if(is_array($q->posts))
			return $q->posts;
		else
			return array();
	}

	function create_grayscale_image($meta){
		
		if( !isset( $meta['file'] ) ){
			return $meta;
		} 

		$dir = wp_upload_dir();
		$fullpath = explode('/', $meta['file']);
		$filename = $fullpath[count( $fullpath ) - 1];
		$filethumb = preg_replace('/(\.gif|\.jpg|\.png)/', '-75x75$1', $filename);

        

		#$files = array($filename, $meta['sizes']['thumbnail']['file']);

		$files = array($filename, $filethumb);

		foreach ($files as $key => $the_file) {
			$file = trailingslashit($dir['path']).$the_file;
			$new_file = preg_replace('/(\.gif|\.jpg|\.png)/', '_bw$1', $file);
			if( !file_exists($file) ){return;}
			list($orig_w, $orig_h, $orig_type) = @getimagesize($file);
			$image = wp_load_image($file);
			$gaussian = array(array(1.0, 2.0, 1.0), array(2.0, 4.0, 2.0), array(1.0, 2.0, 1.0));
			imageconvolution($image, $gaussian, 16, 0);
			imagefilter($image, IMG_FILTER_GRAYSCALE);
			imagefilter($image, IMG_FILTER_GAUSSIAN_BLUR);
			switch ($orig_type) {
				case IMAGETYPE_GIF:
					imagegif( $image, $new_file );
					break;
				case IMAGETYPE_PNG:
					imagepng( $image, $new_file );
					break;
				case IMAGETYPE_JPEG:
					imagejpeg( $image, $new_file );
					break;
			}

		}
		return $meta;
	}
}

