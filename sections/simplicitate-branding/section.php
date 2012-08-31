<?php
/*
	Section: Simplicitate Branding
	Author: Enrique Chavez
	Author URI: http://tmeister.net
	Description: Shows the main site logo or the site title and description adapted by Simplicitate Theme.
	Version: 1.0
	Class Name: TmSimplicitateBranding
	Workswith: header 
*/

/**
 * Branding Section
 *
 * @package PageLines Framework
 * @author PageLines
 */
class TmSimplicitateBranding extends PageLinesSection {

	/**
	* Section template.
	*/
   function section_template() { 
   ?>
		<div class="branding_wrap">
			<?php pagelines_main_logo();  ?>
			<div class="icons simplicitate">
				<?php if (ploption('twitterlink')): ?>
					<div title="Twitter" class="iconfont"><a href="<?php echo ploption('twitterlink') ?>">l</a></div>
				<?php endif ?>
				<?php if (ploption('facebooklink')): ?>
					<div title="Facebook" class="iconfont"><a href="<?php echo ploption('facebooklink') ?>">f</a></div>
				<?php endif ?>
				<?php if (ploption('linkedinlink')): ?>
					<div title="Linkedin" class="iconfont"><a href="<?php echo ploption('linkedinlink') ?>">i</a></div>
				<?php endif ?>
				<?php if (ploption('youtubelink')): ?>
					<div title="YouTube" class="iconfont"><a href="<?php echo ploption('youtubelink') ?>">x</a></div>
				<?php endif ?>
				<?php if (ploption('gpluslink')): ?>
					<div title="Google Plus" class="iconfont"><a href="<?php echo ploption('gpluslink') ?>">g</a></div>
				<?php endif ?>
				<?php if (ploption('pinterestlink')): ?>
					<div title="Pinterest" class="iconfont"><a href="<?php echo ploption('pinterestlink') ?>">&amp;</a></div>
				<?php endif ?>
				<?php if (ploption('rsslink')): ?>
					<div title="RSS" class="iconfont"><a href="<?php echo apply_filters( 'pagelines_branding_rssurl', get_bloginfo('rss2_url') ) ?>">r</a></div>
				<?php endif ?>
			</div>
		</div>
	
	<?php 	
	}
}
