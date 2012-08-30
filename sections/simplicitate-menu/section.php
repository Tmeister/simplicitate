<?php
/*
Section: Simplicitate Menu
Author: Enrique Chavez
Author URI: http://tmeister.net
Version: 1.0
Description: Simplicitate Menu
Class Name: SimplicitateMenu
Cloning: false
Workswith: header
*/

/*
 * PageLines Headers API
 * 
 *  Sections support standard WP file headers (http://codex.wordpress.org/File_Header) with these additions:
 *  -----------------------------------
 * 	 - Section: The name of your section.
 *	 - Class Name: Name of the section class goes here, has to match the class extending PageLinesSection.
 *	 - Cloning: (bool) Enable cloning features.
 *	 - Depends: If your section needs another section loaded first set its classname here.
 *	 - Workswith: Comma seperated list of template areas the section is allowed in.
 *	 - Failswith: Comma seperated list of template areas the section is NOT allowed in.
 *	 - Demo: Use this to point to a demo for this product.
 *	 - External: Use this to point to an external overview of the product
 *	 - Long: Add a full description, used on the actual store page on http://www.pagelines.com/store/
 *
 */

/**
 *
 * Section Class Setup
 * 
 * Name your section class whatever you want, just make sure it matches the 
 * "Class Name" in the section meta (above)
 * 
 * File Naming Conventions
 * -------------------------------------
 *  section.php 		- The primary php loader for the section.
 *  style.css 			- Basic CSS styles contains all structural information, no color (autoloaded)
 *  images/				- Image folder. 
 *  thumb.png			- Primary branding graphic (300px by 225px - autoloaded)
 *  screenshot.png		- Primary Screenshot (300px by 225px)
 *  screenshot-1.png 	- Additional screenshots: (screenshot-1.png -2 -3 etc...optional).
 *  icon.png			- Portable icon format (16px by 16px)
 *	color.less			- Computed color control file (autoloaded)
 *
 */
class SimplicitateMenu extends PageLinesSection {

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

	/**
	 *
	 * Section API - Methods & Functions
	 * 
	 * Below we'll give you a listing of all the available 
	 * Section 'methods' or functions, and other techniques.
	 * 
	 * Please reference other PageLines sections for ideas/tips on how
	 * to use more advanced functionality.
	 *
	 */

	var $domain = 'sp_menu';

		/**
		 *
		 * Persistent Section Code 
		 * 
		 * Use the 'section_persistent()' function to add code that will run on every page in your site & admin
		 * Code here will run ALL the time, and is useful for adding post types, options etc.. 
		 *
		 */
		function section_persistent(){
		} 

		/**
		 *
		 * Site Head Section Code 
		 * 
		 * Code added in the 'section_head()' function will be run during the <head> element of your site's
		 * 'front-end' pages. You can use this to add custom Javascript, or manually add scripts & meta information
		 * It will *only* be loaded if the section is present on the page template.
		 *
		 */
		function section_head(){
			
			
		} 

		/**
		 *
		 * Section Template
		 * 
		 * The 'section_template()' function is the most important section function. 
		 * Use this function to output all the HTML for the section on pages/locations where it's placed.
		 * 
		 * Here we've included some example processing and output for a 'Pull Quote' section.
		 *
		 */
	 	function section_template( $clone_id = null ) { 
		 	if( has_nav_menu('primary') ){
	 			wp_nav_menu( array('menu_class'  => "main-nav".pagelines_nav_classes(), 'container' => null, 'container_class' => '', 'depth' => 3, 'theme_location'=>'primary') );
		 	}else{
		 		pagelines_nav_fallback();
		 	}		
		}
	
		/** 
		 * For template code that should show before the standard section markup
		 */
		function before_section_template( $clone_id = null ){}

		/** 
		 * For template code that should show after the standard section markup
		 */
		function after_section_template( $clone_id = null ){}
	
	/**
	 *
	 * Using PageLines Options
	 * -----------------------------------------------------------
	 * PageLines options revolve around the ploption function. 
	 * To use this function you provide two arguments as follows.
	 * 
	 * 	Usage: ploption( 'key', $args );
	 * 		'key' - The key for the PageLines option 
	 *  	$args - An array of variables for handling the option: 
	 * 
	 *			-  $args() list (optional): 
	 * 				- 	'post_id'	- The global post or page id (required for page by page meta control)
	 *				- 	'clone_id'	- The clone idea for the section (required to enable cloning)
	 * 				Advanced
	 *					- 	'setting'	- The WP setting field to pull the option from. 
	 * 					- 	'subkey'	- For nested options
	 * 
	 * 
	 */
		
		/**
		 *
		 * Section Page Options
		 * 
		 * Section optionator is designed to handle section options.
		 */
		function section_optionator( $settings ){
		}
	} /* End of section class - No closing php tag needed */