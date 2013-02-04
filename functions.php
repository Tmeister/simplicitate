<?php
require_once( dirname(__FILE__) . '/setup.php' );

/******************************************************************************
*	Simplicitate
******************************************************************************/

class Simplicitate
{

	var $domain = 'sp_theme';

	function __construct()
	{
		add_action('wp_enqueue_scripts', array($this, 'add_custom_js'));

		add_filter('pagelines_excerpt', array($this, 'loop_before'));
	
		add_filter('pless_vars', array($this, 'add_less_variables'));

		add_action('pagelines_loop_after_post_content', array( $this, 'add_update_tag' ) );

		add_filter('pagelines_options_color_control', array($this, 'color_filter'));
		add_filter('pagelines_options_header_footer', array($this, 'header_filter'));
		$this->set_default_values();

	}

	function add_update_tag(){
		global $post;
		$time = get_the_date( 'c' );
	?>
		<time class="entry-date updated hidden" datetime="<?php echo esc_attr( $time )?>" pubdate=""><?php echo $time  ?></time>
	<?php
	}

	function loop_before($excerpt){
		global $post;
		return '<time class="entry-date updated hidden" datetime="'.esc_attr( get_the_date( 'c' ) ).'" pubdate>'.esc_attr( get_the_date() ).'</time><div class="row"><div class="span2 the-date"><span class="the-day">'.get_the_date('d').'</span><span class="the-month">'.get_the_date('M').'</span></div><div class="span10">'.$excerpt.'</div></div>';
	}


	function add_custom_js(){
		wp_register_script( 'simplicitate-script', get_stylesheet_directory_uri() . '/js/sp.js', array( 'jquery' ) );  
		wp_enqueue_script( 'simplicitate-script' );
	}
	
	function color_filter($color){
		
		$link       = $color['text_colors']['selectvalues']['linkcolor'];
		$shadow     = $color['canvas_shadow'];
		$back_image = $color['page_background_image'];
		$supersize  = $color['supersize_bg'];
		
		$link['inputlabel'] = __('Complementary color', $this->domain) ;
		
		unset( $color['text_colors']['selectvalues']['linkcolor'] );
		unset( $color['page_background_image'] );
		unset( $color['supersize_bg'] );
		unset( $color['canvas_shadow']);
		

		//$color['page_colors']['selectvalues'] = array_merge( $color['page_colors']['selectvalues'], array(  ) );
		
		$color = array_merge( $color, array(
			'sp_back_color' =>	array(
				'type'         => 'color_multi',
				'title'        => __('Simplicite primary colors', $this->domain),
				'shortexp'     => __('Configure the colors for Simplicitate.', $this->domain),
				'exp'          => 'Simplicitate theme adds more options for colors, the main option is the complementary color, this color is used for  highlight the important content, links, hover, etc.',
				'selectvalues' => array(
					'linkcolor' => $link,
					'sp_header_footer_color'	=> array(				
						'inputlabel' 	=> __( 'Footer Background', $this->domain ),
					),
					'sp_morefooter_color'	=> array(				
						'inputlabel' 	=> __( 'Morefooter Background', $this->domain ),
					),
					'sp_morefooter_color_text'	=> array(				
						'inputlabel' 	=> __( 'Morefooter text', $this->domain ),
					),
				),
			),
			'sp_site_pattern'	 => array(
				'type'         => 'select',
				'title'        => __('Background Site Pattern', $this->domain),
				'shortexp'     => __('Configure the pattern to use in site.', $this->domain),
				'exp'          => __('Simplicitate theme unique option with a predefined patterns who works great with the minimal design', $this->domain),
				'selectvalues' => array(
					'pat1.png'  => array('name' => __( 'Gray Sketch', $this->domain) ),
					'pat3.png'  => array('name' => __( 'Light Gray Sketch', $this->domain) ),
					'pat5.png'  => array('name' => __( 'Wood', $this->domain) ),
					'pat2.png'  => array('name' => __( 'Gray Wood', $this->domain) ),
					'pat4.png'  => array('name' => __( 'Granite', $this->domain) ),
					'blank.png' => array('name' => __( 'No Pattern', $this->domain) ),
				),
				'inputlabel' 	=> __( 'Background Site Pattern', $this->domain ),
			),
			'sp_menu_background' =>	array(
				'type'         => 'color_multi',
				'layout'       => 'full',
				'title'        => __('Simplicitate Menu Colors', $this->domain),
				'shortexp'     => __('Configure the colors to use in the menu.', $this->domain),
				'exp'          => __('This options only works with the Simplicitate Menu', $this->domain),
				'selectvalues' => array(
					'sp_menu_main_bg'	=> array(				
						'inputlabel' 	=> __( 'Menu gradient top ', $this->domain ),
					),
					'sp_menu_main_bg_bottom'	=> array(				
						'inputlabel' 	=> __( 'Menu gradient bottom', $this->domain ),
					),
					'sp_menu_text'	=> array(				
						'inputlabel' 	=> __( 'Link Text', $this->domain ),
					),
					'sp_menu_link_one'	=> array(				
						'inputlabel' 	=> __( 'Over gradient top', $this->domain ),
					),
					'sp_menu_link_two'	=> array(				
						'inputlabel' 	=> __( 'Over gradient bottom', $this->domain ),
					),
					'sp_menu_text_hover'	=> array(				
						'inputlabel' 	=> __( 'Link Text Over', $this->domain ),
					),
				),
			),
			'page_background_image' => $back_image,
			'supersize_bg'          => $supersize,
			'canvas_shadow'         => $shadow
		));

		return $color;
	}

	function header_filter($options){
		$social_plus = array(
			'pinterestlink' => array(
				'inputlabel' => 'Your Pinterest Profile URL - Just for Simplicitate', 
				'default'    => ''
			)
		);
		$icon_social = $options['icon_social']['selectvalues'];
		$options['icon_social']['selectvalues'] = array_merge( $icon_social , $social_plus );		
		return $options;
	}

	function add_less_variables($less){
		
		$site_pattern     = ploption('sp_site_pattern') ? ploption('sp_site_pattern') : 'pat2.png';
		$footer           = ploption('sp_header_footer_color') ? ploption('sp_header_footer_color') : '#2f2f2f';
		$back_menu        = ploption('sp_menu_main_bg') ? ploption('sp_menu_main_bg') : '#ffffff';
		$back_menu_bottom = ploption('sp_menu_main_bg_bottom') ? ploption('sp_menu_main_bg_bottom') : '#ffffff';
		$link1            = ploption('sp_menu_link_one') ? ploption('sp_menu_link_one') : '#fbfbfb';
		$link2            = ploption('sp_menu_link_two') ? ploption('sp_menu_link_two') : '#f1f1f1';
		$text             = ploption('sp_menu_text') ? ploption('sp_menu_text') : '#9c9c9c';
		$text_hover       = ploption('sp_menu_text_hover') ? ploption('sp_menu_text_hover') : '#9c9c9c';
		$more_back        = ploption('sp_morefooter_color') ? ploption('sp_morefooter_color') : '#2f2f2f';
		$more_text        = ploption('sp_morefooter_color_text') ? ploption('sp_morefooter_color_text') : '#9c9c9c';
		
		$less['spMenuBackground']       = $back_menu;
		$less['spMenuBackgroundBottom'] = $back_menu_bottom;
		$less['spMenuGradiantTop']      = $link1;
		$less['spMenuGradiantBottom']   = $link2;
		$less['spMenuText']             = $text;
		$less['spMenuTextOver']         = $text_hover;
		$less['spFooterBack']           = $footer;
		$less['spMoreBack']             = $more_back;
		$less['spMoreText']             = $more_text;

		
		$less['spBackPattern']          = '"/images/patterns/' . $site_pattern.'"';
		return $less;
	}

	function set_default_values(){
		pl_default_setting( 
		 	array( 
			    'key' => 'pagelines_custom_logo', 
			    'value' => 'wp-content/themes/simplicitate/images/logo.png'
		    ) 
		);
		
		pl_default_setting( 
		 	array( 
			    'key' => 'site_design_mode', 
			    'value' => 'fixed_width'
		    ) 
		);

		pl_default_setting( 
		 	array( 
			    'key' => 'bodybg', 
			    'value' => '#FFFFFF'
		    ) 
		);

		pl_default_setting( 
		 	array( 
			    'key' => 'pagebg', 
			    'value' => '#FFFFFF'
		    ) 
		);

		pl_default_setting( 
		 	array( 
			    'key' => 'text_primary', 
			    'value' => '#8f8f8f'
		    ) 
		);

		pl_default_setting( 
		 	array( 
			    'key' => 'headercolor', 
			    'value' => '#8f8f8f'
		    ) 
		);

		pl_default_setting( 
		 	array( 
			    'key' => 'footer_text', 
			    'value' => '#8f8f8f'
		    ) 
		);

		pl_default_setting( 
		 	array( 
			    'key' => 'linkcolor', 
			    'value' => '#1191d6'
		    ) 
		);

		pl_default_setting( 
		 	array( 
			    'key' => 'sp_morefooter_color', 
			    'value' => '#2f2f2f'
		    ) 
		);

		pl_default_setting( 
		 	array( 
			    'key' => 'sp_morefooter_color', 
			    'value' => '#2f2f2f'
		    ) 
		);

		pl_default_setting( 
		 	array( 
			    'key' => 'sp_morefooter_color_text', 
			    'value' => '#8f8f8f'
		    ) 
		);

		pl_default_setting(
			array(
				'key' => 'blog_layout_mode',
				'value' => 'blog'
			)
		);
		
		pl_default_setting(
			array(
				'key' => 'excerpt_mode_full',
				'value' => 'top'
			)
		);

		pl_default_setting(
			array(
				'key' => 'metabar_standard',
				'value' => 'By [post_author_posts_link] &middot; [post_comments] [post_edit]'
			)
		);
		

	}
}

new Simplicitate();
 