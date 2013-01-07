<?php
/*
Section: Latest Blog Entries for Simplicitate
Author: Enrique Chávez	
Author URI: http://tmeister.net
Version: 1.0.5
Description: Latest Blogs Entries is a very powerful section for Pagelines which displays your recent posts with thumbnail, excerpt, title, date and read more link . It’s the perfect solution to show specific entries on the home page or in any other page. With more that 15 options in general.
Class Name: TmLatestBlog
Cloning: true
External: http://tmeister.net/themes-and-sections/latest-blog-entries/
Demo: http://pagelines.tmeister.net/latest-blog-posts/
Workswith: templates, main, morefoot

**/


class TmLatestBlog extends PageLinesSection {

	/**
	 *
	 * Section Variable Glossary (Auto Generated)
	 * ------------------------------------------------
	 *  $this->id			- The unique section slug & folder name
	 *  $this->base_url 	- The root section URL
	 *  $this->base_dir 	- The root section directory path
	 *  $this->name 		- The section UI name
	 *  $this->description	- The section description
	 *  $this->images		- The root section images URL
	 *  $this->icon 		- The section icon url
	 *  $this->screen		- The section screenshot url 
	 *  $this->oset			- Option settings array... needed for 'ploption' (contains clone_id, post_id)
	 * 
	 * 	Advanced Variables
	 * 		$this->view				- If the section is viewed on a page, archive, or single post
	 * 		$this->template_type	- The PageLines template type
	 */

	var $domain = 'tm_latest';

	function section_persistent(){
		add_image_size('latest', 180, 100, true);
	} 

	function section_scripts(){
		return array(
			'carouFredSel' => array(
				'file'       => $this->base_url . '/jquery.carouFredSel-6.1.0-packed.js',
				'dependancy' => array('jquery'),
				'location'   => 'footer',
				'version'    => '6.1.0'
			),
		);
	}

	function section_head( $clone_id = null ){
		global $pagelines_ID;
		
		$oset              = array('post_id' => $pagelines_ID, 'clone_id' => $clone_id);		
		$effect            = ( ploption('tm_latest_effect', $oset) ) ? ploption('tm_latest_effect', $oset) : 'scroll';
		$effect_duration   = ( ploption('tm_latest_effect_duration', $oset) ) ? ploption('tm_latest_effect_duration', $oset) : '1000';
		$pause_on_hover    = ( ploption('tm_latest_pause_on_hover', $oset) == 'on') ? 'true' : 'false';
		$pause_duration    = ( ploption('tm_latest_duration_pause', $oset) ) ? ploption('tm_latest_duration_pause', $oset) : '5000';
		$disable_autostart = ( ploption('tm_latest_autostart', $oset) == 'on') ? true : false;

		/*Styles*/
		$section_bg = ( ploption('tm_latest_section_title_bg', $oset) ) ? ploption('tm_latest_section_title_bg', $oset) : '#ffffff';
		$main_bg = ( ploption('tm_latest_main_bg', $oset) ) ? ploption('tm_latest_main_bg', $oset) : '#ffffff';
		$border  = ( ploption('tm_latest_menu_border', $oset) ) ? ploption('tm_latest_menu_border', $oset) : '#EEEEEE';
		$shadow  = ( ploption('tm_latest_shadow', $oset) ) ? ploption('tm_latest_shadow', $oset) : '#e4e4e4';
		$title   = ( ploption('tm_latest_title_color', $oset) ) ? ploption('tm_latest_title_color', $oset) : '#4c4c4c';
		$text    = ( ploption('tm_latest_text_color', $oset) ) ? ploption('tm_latest_text_color', $oset) : '#555555';
		
		
		
	?>
		<style>
			.latest<?php echo $clone_id?> ul.slides li{
				background:<?php echo $main_bg ?>;
				border: 1px solid <?php echo $border ?>;
			}
			.latest<?php echo $clone_id?> ul.slides li:hover {
			    -webkit-box-shadow: 3px 3px 0 <?php echo $shadow ?>;
			    -moz-box-shadow: 3px 3px 0 <?php echo $shadow ?>;
			    box-shadow: 3px 3px 0 <?php echo $shadow ?>;
			}
			.latest<?php echo $clone_id?> ul.slides li h3 a{
				color:<?php echo $title ?> !important;
			}
			.latest<?php echo $clone_id?> ul.slides li{
				color:<?php echo $text ?> !important;
			}
			.latest<?php echo $clone_id?> .block-title span{
				background:<?php echo $section_bg ?> !important;
			}
		</style>
		<script>
			jQuery(document).ready(function() {
				function renderLatest(){
					jQuery(".latest<?php echo $clone_id?> .slides").each( function(a, b){
						var highest = 0;
						$this = jQuery(this);

						$this.find('li').each(function(a, item){
							highest = ( jQuery(item).height() > highest ) ? jQuery(item).height() : highest;
						});


						$this.find('li').each(function(a, item){
							$item = jQuery(item);
							$item.css({'height':highest});
							$item.find('.read-more').each( function(a, item){
								$item = jQuery(item);
								$item.css({'position': 'absolute', 'bottom': '5px'})
							} );
						});

					});			

					jQuery(".latest<?php echo $clone_id?> .slides").carouFredSel({
						align       : "center",
						width:'100%',
						<?php echo ( $disable_autostart ) ? 'auto:false,' : 'auto: {timeoutDuration :'.$pause_duration.'},'?>
						scroll: {
							duration:<?php echo $effect_duration?>,
							fx: '<?php echo $effect?>',
							pauseOnHover: <?php echo $pause_on_hover?>
						},
						prev:'.latest<?php echo $clone_id?> #prev_pag',
						next:'.latest<?php echo $clone_id?> #next_pag'
					});
				}
				setTimeout(renderLatest,1000)
			});
		</script>
	<?php
	} 



 	function section_template( $clone_id = null ) {
 		global $post, $pagelines_ID;

		$current_page_post = $post;
		$oset              = array('post_id' => $pagelines_ID, 'clone_id' => $clone_id);
		$limit             = ( ploption('tm_latest_items', $oset) ) ? ploption('tm_latest_items', $oset) : '8';
		$set               = ( ploption('tm_latest_set', $oset) ) ? ploption('tm_latest_set', $oset) : null;
		$title             = ( ploption('tm_latest_title', $oset) ) ? ploption('tm_latest_title', $oset) : 'Latest from the Blog';
		
		$show_thumb        =  ( ploption('tm_latest_thumb', $oset) == 'on') ? false : true;
		$show_title        =  ( ploption('tm_latest_show_title', $oset) == 'on') ? false : true;

		$use_wp_excerpt 	=  ( ploption('tm_use_wp_excerpt', $oset) == 'on') ? false : true;
 		
		$show_date         =  ( ploption('tm_latest_date', $oset) == 'on') ? false : true;
		$show_excerpt      =  ( ploption('tm_latest_excerpt', $oset) == 'on') ? false : true;
		$show_read_more    =  ( ploption('tm_latest_read_more', $oset) == 'on') ? false : true;
		$read_more_text    =  ( ploption('tm_latest_read_more_text', $oset ) ) ? ploption('tm_latest_read_more_text', $oset )  : 'Read More';
		
		$limit_excerpt     = ( ploption('tm_limit_excerpt', $oset) ) ? ploption('tm_limit_excerpt', $oset) : '20';

		$descriptive_text = ( ploption('tm_latest_descriptive_text', $oset) ) ? ploption('tm_latest_descriptive_text', $oset) : 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. In at nunc lacus. Nulla at nunc enim, blandit lacinia ante. Aenean odio mauris, fringilla vitae scelerisque sit amet, tempor et neque.';

		$posts = $this->get_posts( $set, $limit );
		if( !count($posts) ){
			echo setup_section_notify($this, __('Sorry, there are no posts to display.', 'post'), get_admin_url().'edit.php?post_type=post', 'Please create some posts' );
			return;
		}


	

 	?>

 		
		<div class="latest-slider latest<?php echo $clone_id?>">
			<h1 class="block-title">
				<span> <?php echo $title?> </span>
			</h1>
			<div class="row">
				<div class="intro-latest span3">
					<div>
						<?php echo nl2br( $descriptive_text) ?>
					</div>

				</div>
				<div class="span9">
					<ul class="slides">
						<?php foreach ($posts as $post): global $post; setup_postdata( $post ); $img = wp_get_attachment_image_src( $post->ID ); ?>
							<li class="post">
								<?php if ($show_thumb): ?>
									<div class="blog-thumb">
										<a href="<?php the_permalink() ?>" title="<?php the_title() ?>">
											<?php echo the_post_thumbnail( 'latest' ) ?>
										</a>
									</div>	
								<?php endif ?>
								
								<div class="blog-title">
									<?php if ($show_title): ?>
										<div class="big">
											<h3><a href="<?php the_permalink()?>"><?php the_title()?></a></h3>
										</div>	
									<?php endif ?>
								</div>
								<?php if ($show_date): ?>
									<p class="post-meta"><?php echo get_the_date(); ?></p>	
								<?php endif ?>
								
								<div class="clear"></div>
								
								<?php if ($show_excerpt): ?>
									<div class="excerpt">
										<?php if ($use_wp_excerpt): ?>
											<p><?php echo $this->latest_excerpt( get_the_excerpt(), $limit_excerpt ); ?></p>
										<?php else: ?>	
											<p><?php echo $this->latest_excerpt( get_the_content(), $limit_excerpt ); ?></p>
										<?php endif ?>
										
									</div>	
									<div class="clear"></div>
								<?php endif ?>
								
								<?php if ($show_read_more): ?>
									<div class="read-more">
										<a href="<?php the_permalink() ?>"><?php echo $read_more_text ?></a>
									</div>	
								<?php endif ?>
								
							</li>	
						<?php endforeach; $post = $current_page_post; ?>
						
					</ul>
					<div class="clearfix"></div>
					<div class="latest_pagination">
						<div class="nav" id="prev_pag"></div>
				    	<div class="nav" id="next_pag"></div>	
					</div>
				</div>
			</div>
			
		</div>

 	<?php
	}

	/**
	 *
	 * Section Page Options
	 * 
	 * Section optionator is designed to handle section options.
	 */
	function section_optionator( $settings ){

		$settings = wp_parse_args($settings, $this->optionator_default);
		$opt_array = array(
			'tm_latest_title' 	=> array(
				'type'			=> 'text',
				'inputlabel'	=> __('Title', $this->domain),
				'title' 		=> __('Section Title', $this->domain),
				'shortexp'		=> __('Default: "Latest from the Blog"', $this->domain),
				'exp'			=> __('If set the title will show on the top of the section', $this->domain),
			),
			'tm_latest_set' 	=> array(
				'type' 			=> 'select_taxonomy',
				'taxonomy_id'	=> 'category',
				'title' 		=> __('Select the category to show', $this->domain),
				'shortexp'		=> __('The category to show', $this->domain),
				'inputlabel'	=> __('Select a category', $this->domain),
				'exp' 			=> __('Select the category you would like to show on this page. if don\'t select a set the slider will show the last entries for all the categories', $this->domain)
			),
			'tm_latest_items' => array(
				'type' 			=> 'count_select',
				'inputlabel'	=> __('Number of post to show', $this->domain),
				'title' 		=> __('Number of post', $this->domain),
				'shortexp'		=> __('Default value is 8', $this->domain),
				'exp'			=> __('The amount of post to show.', $this->domain),
				'count_start'	=> 1, 
 				'count_number'	=> 20,
			),
			'tm_latest_descriptive_text' => array(
				'type' 			=> 'textarea',
				'inputlabel'	=> __('Description', $this->domain),
				'title' 		=> __('Description', $this->domain),
				'shortexp'		=> __('Write a little description', $this->domain),
				'exp'			=> __('This description will show in the left size of the slider.', $this->domain),
				'count_start'	=> 1, 
 				'count_number'	=> 20,
			),

			'tm_latest_background' =>	array(
				'type'			=> 'color_multi',
				'layout'		=> 'full',
				'title'			=> __('Items Colors', $this->domain),
				'shortexp'		=> __('Configure the colors to use.', $this->domain),
				'selectvalues'	=> array(
					'tm_latest_section_title_bg'	=> array(				
						'inputlabel' 	=> __( 'Section title Background', $this->domain ),
					),
					'tm_latest_main_bg'	=> array(				
						'inputlabel' 	=> __( 'Background', $this->domain ),
					),
					'tm_latest_menu_border'	=> array(				
						'inputlabel' 	=> __( 'Border', $this->domain ),
					),
					'tm_latest_shadow'	=> array(				
						'inputlabel' 	=> __( 'Shadow', $this->domain ),
					),
					'tm_latest_title_color'	=> array(				
						'inputlabel' 	=> __( 'Title', $this->domain ),
					)
					,
					'tm_latest_text_color'	=> array(				
						'inputlabel' 	=> __( 'Text', $this->domain ),
					)
				),
			),

			'tm_latest_thumb' => array(
				'type'			=> 'check',
				'title'			=> __('Show Thumbnail image', $this->domain),
				'inputlabel'	=> __('Don\'t show the thumbnail image ', $this->domain),
				'shortexp'		=> __('Default: Visible', $this->domain),
				'exp'			=> __('Determines whether to show the thumbnail image.', $this->domain)
			),
			'tm_latest_show_title' => array(
				'type'			=> 'check',
				'title'			=> __('Show title', $this->domain),
				'inputlabel'	=> __('Don\'t show the title', $this->domain),
				'shortexp'		=> __('Default: Visible', $this->domain),
				'exp'			=> __('Determines whether to show the post title.', $this->domain)
			),
			'tm_latest_date' => array(
				'type'			=> 'check',
				'title'			=> __('Show Date', $this->domain),
				'inputlabel'	=> __('Don\'t show the date', $this->domain),
				'shortexp'		=> __('Default: Visible', $this->domain),
				'exp'			=> __('Determines whether to show the post date.', $this->domain)
			),
			'tm_latest_excerpt' => array(
				'type'			=> 'check',
				'title'			=> __('Show Excerpt', $this->domain),
				'inputlabel'	=> __('Don\'t show the excerpt', $this->domain),
				'shortexp'		=> __('Default: Visible', $this->domain),
				'exp'			=> __('Determines whether to show the post excerpt.', $this->domain)
			),
			'tm_use_wp_excerpt' => array(
				'type'			=> 'check',
				'title'			=> __('WP Excerpt', $this->domain),
				'inputlabel'	=> __('Don\'t use the WP excerpt', $this->domain),
				'shortexp'		=> __('Default: WP Excerpt', $this->domain),
				'exp'			=> __('Determines whether to use the WP excerpt text or use the post content instead. to show the post excerpt.', $this->domain)
			),
			'tm_limit_excerpt' => array(
				'type' 			=> 'count_select',
				'title'			=> __('Excerpt - Words to show', $this->domain),
				'inputlabel'	=> __('Words', $this->domain),
				'shortexp'		=> __('Default: 20', $this->domain),
				'exp'			=> __('In any case, whether you use wp excerpt or the post content, determine how many words you want to display in each box.', $this->domain),
				'count_start'	=> 5,
 				'count_number'	=> 50,
			),
			'tm_latest_read_more' => array(
				'type'			=> 'check',
				'title'			=> __('Show read more', $this->domain),
				'inputlabel'	=> __('Don\'t show the read more', $this->domain),
				'shortexp'		=> __('Default: Visible', $this->domain),
				'exp'			=> __('Determines whether to show the post read more.', $this->domain)
			),

			'tm_latest_read_more_text' => array(
				'type'			=> 'text',
				'title'			=> __('"Read More" text', $this->domain),
				'inputlabel'	=> __('Text to show', $this->domain),
				'shortexp'		=> __('Default: Read More', $this->domain),
				'exp'			=> __('Change the "Read More" text to show in the link.', $this->domain)
			),


			'tm_latest_autostart' => array(
				'type'			=> 'check',
				'title'			=> __('Disable autostart scroll', $this->domain),
				'inputlabel'	=> __('Disable Autostart scroll', $this->domain),
				'shortexp'		=> __('', $this->domain),
				'exp'			=> __('Determines whether the slider auto scroll.', $this->domain)
			),
			'tm_latest_effect' => array(
				'title'			=> 'Transition Effect',
				'type'         	=> 'select',
				'selectvalues' 	=> array(
					'scroll'    => array('name' => __( 'Scroll', $this->domain) ),
					'fade'      => array('name' => __( 'Fade', $this->domain) ),
					'crossfade' => array('name' => __( 'CrossFade', $this->domain) ),
					'cover'     => array('name' => __( 'Cover', $this->domain) ),
					'uncover'   => array('name' => __( 'UnCover', $this->domain) ),
				),
				'inputlabel'   	=> __( 'Select the transition effect', $this->domain ),
				'shortexp' 		=> 'Default value: Scroll',
				'exp'      		=> 'Indicates which effect to use for the transition.'
			),
			'tm_latest_effect_duration' 	=> array(
				'type'			=> 'text',
				'inputlabel'	=> 'Transition Time',
				'title' 		=> 'Transition Time',
				'shortexp'		=> 'Default value: 4 seconds.',
				'exp'			=> 'Determines the duration of the transition in milliseconds. 1000 = 1 second',
			),
			'tm_latest_pause_on_hover' => array(
				'type'			=> 'check',
				'title'			=> __('Pause on hover', $this->domain),
				'inputlabel'	=> __('Pause on hover', $this->domain),
				'shortexp'		=> __('', $this->domain),
				'exp'			=> __('Determines whether the timeout between transitions should be paused "onMouseOver"', $this->domain)
			),
			'tm_latest_duration_pause' 	=> array(
				'type'			=> 'text',
				'inputlabel'	=> 'Pause Duration',
				'title' 		=> 'Pause Duration',
				'shortexp'		=> '',
				'exp'			=> 'The amount of milliseconds the carousel will pause. 1000 = 1 second',
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

	function get_posts( $set = null, $limit = null){
		$query                  = array();
		$query['category_name'] = $set;
		
		if(isset($limit)){
			$query['showposts'] = $limit;
		}
		
		$q = new WP_Query($query);

		if(is_array($q->posts))
			return $q->posts;
		else
			return array();
	}


	function latest_excerpt($text, $limit) {
		$text = strip_shortcodes( $text );
		$text = apply_filters('the_content', $text);
		$text = str_replace(']]>', ']]&gt;', $text);
		$excerpt_more = apply_filters('excerpt_more', ' ' . '[...]');
		$text = wp_trim_words( $text, $limit, $excerpt_more );	
		return $text;
	}

} /* End of section class - No closing php tag needed */