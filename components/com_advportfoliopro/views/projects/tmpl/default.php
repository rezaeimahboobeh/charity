<?php
/**
 * @copyright	Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access.
defined('_JEXEC') or die;

// Include CSS and JS
JHtml::_('jquery.framework');
JHtml::_('advportfoliopro.modal');
JHtml::_('script', 'com_advportfoliopro/modernizr.min.js', false, true, false, false, true);
JHtml::_('script', 'com_advportfoliopro/isotope.pkgd.min.js', false, true, false, false, true);
JHtml::_('stylesheet', 'com_advportfoliopro/style.css', false, true, false, false, true);

$overlayEffect = $this->params->get('overlay_effect', 'none');

if ($overlayEffect == 'hoverdir') {
	JHtml::_('script', 'com_advportfoliopro/jquery.hoverdir.js', false, true, false, false, true);
}

JHtml::_('script', 'com_advportfoliopro/script.js', false, true, false, false, true);

$show_filter	 	 = $this->params->get('show_filter', 1);
$columns		 	 = (int) $this->params->def('num_columns', 3);
$item_class		 	 = ' column-' . $columns;
$image_width	 	 = $this->params->get('image_width', 1200 / $columns);
$image_height	 	 = (int) $this->params->get('image_height', 0);
$gutter_width	 	 = $this->params->get('gutter_width', 10);

$bg_info_icon		 = $this->params->get('bg_icon', '#b1b1b1');
$bg_info_hover_icon	 = $this->params->get('bg_icon_hover', '');
$overlay_color1	 	 = $this->params->get('overlay_color1', '#5aabd6');
$overlay_color2	 	 = $this->params->get('overlay_color2', '');
$overlay_opacity 	 = $this->params->get('overlay_opacity', 100) / 100;

$hoverEasing 	 	 = $this->params->get('hover_easing', 'ease');
$hoverDuration	 	 = $this->params->get('hover_duration', 0.45) . 's';
$hoverdirSpeed 	 	 = $this->params->get('hoverdir_speed', 300);
$hoverDelay 	 	 = $this->params->get('hover_delay', 0);
$hoverDelayCss	 	 = $hoverDelay . 's';
$hoverdirInverse 	 = $this->params->get('hoverdir_inverse', false);

$scale				 = $this->params->get('scale', 0);
$translate			 = $this->params->get('translate', 0);
$rotatez			 = $this->params->get('rotatez', 0);
$rotatex			 = $this->params->get('rotatex', 0);
$rotatey			 = $this->params->get('rotatey', 0);
$skew				 = $this->params->get('skew', 0);

$scale_x			 = $this->params->get('scale_x', '');
$scale_y			 = $this->params->get('scale_y', '');
$translate_x		 = $this->params->get('translate_x', '');
$translate_y		 = $this->params->get('translate_y', '');
$rotate_angle_z		 = $this->params->get('rotate_angle_z', 20);
$rotate_angle_x		 = $this->params->get('rotate_angle_x', 20);
$rotate_angle_y		 = $this->params->get('rotate_angle_y', 20);
$skew_angle_x		 = $this->params->get('skew_angle_x', '');
$skew_angle_y		 = $this->params->get('skew_angle_y', '');
$link_target_blank	 = $this->params->get('link_to_target_blank', 0);
$str_target			 = '';
if ($link_target_blank == 1) {
	$str_target	= 'target=_blank';
}

if ($bg_info_hover_icon) {
	$bg_iconCSs = <<<BGICONCSS
.projects-wrapper .project-img .project-img-extra .project-icon {
	background-color: $bg_info_icon;
	transition: all 0.3s ease-in-out 0s;
	-webkit-transition: all 0.3s ease-in-out 0s;
	-moz-transition: all 0.3s ease-in-out 0s;
	-ms-transition: all 0.3s ease-in-out 0s;
}

.projects-wrapper .project-img .project-img-extra .project-icon:hover {
	background-color: $bg_info_hover_icon;
}
BGICONCSS;

} else {
	$bg_iconCSs	= "
.projects-wrapper .project-img .project-img-extra .project-icon {
	background-color: $bg_info_icon;
}
";
}

$this->document->addStyleDeclaration($bg_iconCSs);

if ($overlay_color1 == '#5aabd6' && !$overlay_color2) {
	$overlay_color2	= '#90c9e8';
}

$rgba_color1 = AdvPortfolioProHelper::hex2RGB($overlay_color1, true) . ',' . $overlay_opacity;
$rgba_color2 = AdvPortfolioProHelper::hex2RGB($overlay_color2, true) . ',' . $overlay_opacity;

if ($overlay_color2) {
	$css	= <<<CSS
.projects-wrapper .project-img .project-img-extra {
	background-image: -webkit-linear-gradient(top , rgba($rgba_color2) 0%, rgba($rgba_color1) 100%);
	background-image: -moz-linear-gradient(top , rgba($rgba_color2) 0%, rgba($rgba_color1) 100%);
	background-image: -o-linear-gradient(top , rgba($rgba_color2) 0%, rgba($rgba_color1) 100%);
	background-image: -ms-linear-gradient(top , rgba($rgba_color2) 0%, rgba($rgba_color1) 100%);
	background-image: linear-gradient(top , rgba($rgba_color2) 0%, rgba($rgba_color1) 100%);
}
CSS;

} else {
	$css	= "
.projects-wrapper .project-img .project-img-extra {
	background-color: rgba($rgba_color1);
	background-image: none;
}
";
}

$this->document->addStyleDeclaration($css);

$defaultCss = <<<DEFAULTCSS
.projects-wrapper .project-img img {
	transition-property: all;
	transition-duration: $hoverDuration;
	transition-timing-function: $hoverEasing;
	transition-delay: $hoverDelayCss;

	-moz-transition-property: all;
	-moz-transition-duration: $hoverDuration;
	-moz-transition-timing-function: $hoverEasing;
	-moz-transition-delay: $hoverDelayCss;

	-webkit-transition-property: all;
	-webkit-transition-duration: $hoverDuration;
	-webkit-transition-timing-function: $hoverEasing;
	-webkit-transition-delay: $hoverDelayCss;

	-ms-transition-property: all;
	-ms-transition-duration: $hoverDuration;
	-ms-transition-timing-function: $hoverEasing;
	-ms-transition-delay: $hoverDelayCss;
}

.projects-wrapper .project-img .project-img-extra {
	transition-property: all;
	transition-duration: $hoverDuration;
	transition-timing-function: $hoverEasing;
	transition-delay: $hoverDelayCss;

	-webkit-transition-property: all;
	-webkit-transition-duration: $hoverDuration;
	-webkit-transition-timing-function: $hoverEasing;
	-webkit-transition-delay: $hoverDelayCss;

	-moz-transition-property: all;
	-moz-transition-duration: $hoverDuration;
	-moz-transition-timing-function: $hoverEasing;
	-moz-transition-delay: $hoverDelayCss;

	-ms-transition-property: all;
	-ms-transition-duration: $hoverDuration;
	-ms-transition-timing-function: $hoverEasing;
	-ms-transition-delay: $hoverDelayCss;

}
DEFAULTCSS;
$this->document->addStyleDeclaration($defaultCss);

if ($overlayEffect == 'none') {
	$noneCss = <<<NONE

.projects-wrapper .project-img .project-img-extra {
	transform: translateX(0%);
	-webkit-transform: translateX(0%);
	-moz-transform: translateX(0%);
	-ms-transform: translateX(0%);
}
NONE;
	$this->document->addStyleDeclaration($noneCss);
}

if ($overlayEffect == 'hoverdir') {
	$hoverdirCss = <<<HOVERDIR
.projects-wrapper .project-img .project-img-extra {
	display: block;
	left: -100%;
	top: 0;
	position: absolute;
	text-align: center;
	width: 100%;
	height: 100%;
	transform: none;
	transition: initial;
}

.projects-wrapper .project-img:hover .project-img-extra {
	opacity: 1;
	transform: none;
}
HOVERDIR;

} else {
$hoverdirCss	= "
.projects-wrapper .project-img .project-img-extra {
	left: 0;
}
";

}

$this->document->addStyleDeclaration($hoverdirCss);

$str_transform = '';

if ($scale) {
	if ($scale_x && !$scale_y) {
		$str_transform .= 'scaleX(' . $scale_x . ') ';
	}
	if (!$scale_x && $scale_y) {
		$str_transform .= 'scaleY(' . $scale_y . ') ';
	}
	if ($scale_x && $scale_y) {
		$str_transform .= 'scale(' . $scale_x . ',' . $scale_y . ') ';
	}
}

if ($translate) {
	if ($translate_x && !$translate_y) {
		$str_transform .= 'translateX(' . $translate_x . 'px' . ') ';
	}
	if (!$translate_x && $translate_y) {
		$str_transform .= 'translateY(' . $translate_y . 'px' . ') ';
	}
	if ($translate_x && $translate_y) {
		$str_transform .= 'translate(' . $translate_x . 'px' . ',' . $translate_y . 'px' . ') ';
	}
}

if ($rotatex) {
	$str_transform .= 'rotateX('. $rotate_angle_x . 'deg' . ') ';
}
if ($rotatey) {
	$str_transform .= 'rotateY('. $rotate_angle_y . 'deg' . ') ';
}
if ($rotatez) {
	$str_transform .= 'rotateZ('. $rotate_angle_z . 'deg' . ') ';
}

if ($skew) {
	if ($skew_angle_x && !$skew_angle_y) {
		$str_transform .= 'skewX('. $skew_angle_x . 'deg' . ') ';
	}
	if (!$skew_angle_x && $skew_angle_y) {
		$str_transform .= 'skewY('. $skew_angle_y . 'deg' . ') ';
	}
	if ($skew_angle_x && $skew_angle_y) {
		$str_transform .= 'skew('. $skew_angle_x . 'deg' . ',' . $skew_angle_y . 'deg' . ') ';
	}
}

if ($str_transform != '') {
	$thumbTransCss = <<<THUMBTRANS
.projects-wrapper .project-img:hover img {
	transform: $str_transform;
	transition-property: all;
	transition-duration: $hoverDuration;
	transition-timing-function: $hoverEasing;
	transition-delay: $hoverDelayCss;

	-webkit-transform: $str_transform;
	-webkit-transition-property: all;
	-webkit-transition-duration: $hoverDuration;
	-webkit-transition-timing-function: $hoverEasing;
	-webkit-transition-delay: $hoverDelayCss;

	-moz-transform: $str_transform;
	-moz-transition-property: all;
	-moz-transition-duration: $hoverDuration;
	-moz-transition-timing-function: $hoverEasing;
	-moz-transition-delay: $hoverDelayCss;

	-ms-transform: $str_transform;
	-ms-transition-property: all;
	-ms-transition-duration: $hoverDuration;
	-ms-transition-timing-function: $hoverEasing;
	-ms-transition-delay: $hoverDelayCss;
}
THUMBTRANS;
} else {
	$thumbTransCss	= "
.projects-wrapper .project-img:hover img {
	transform: none;
}
";

}
$this->document->addStyleDeclaration($thumbTransCss);

$this->document->addScriptDeclaration('ExtStore.AdvPortfolioPro.live_site = \'' . JUri::base(true) . '\';');

?>

<div class="portfolio-list portfolio-list<?php echo $this->pageclass_sfx; ?>"
	 data-columns="<?php echo $columns; ?>"
	 data-gutter-width="<?php echo $gutter_width; ?>"
	 data-overlay_effect="<?php echo $overlayEffect; ?>"
	 data-hoverdir_easing="<?php echo $hoverEasing; ?>"
	 data-hoverdir_speed="<?php echo $hoverdirSpeed ?>"
	 data-hoverdir_hover_delay="<?php echo $hoverDelay ?>"
	 data-hoverdir_inverse="<?php echo $hoverdirInverse; ?>"
	 >
	<?php if ($this->params->get('show_page_heading', 1)) : ?>
		<div class="page-header">
			<h1><?php echo $this->escape($this->params->get('page_heading')); ?></h1>
		</div>
	<?php endif; ?>

	<?php if ($show_filter) : ?>
	<div class="projects-filter" id="projects-filter">
		<ul class="option-set" data-option-key="filter">
			<li>
				<a href="#filter" class="selected" data-option-value="*">
					<?php echo JText::_('COM_ADVPORTFOLIOPRO_FILTER_ALL'); ?>
				</a>
			</li>
			<?php foreach ($this->tags as $alias => $tag) : ?>
			<li>
				<a href="#filter" data-option-value=".<?php echo $alias; ?>"><?php echo $tag; ?></a>
			</li>
			<?php endforeach; ?>
		</ul>
	</div>
	<?php endif; ?>

	<div class="clearfix container-isotop projects-wrapper" id="projects-wrapper">
		<?php foreach ($this->items as $item) :
			$link			= AdvPortfolioProHelperRoute::getProjectRoute($item->slug, $item->catslug);
			$cat_link		= AdvPortfolioProHelperRoute::getCategoryRoute($item->catslug);
			$class			= '';

			if ($item->featured) {
				$class = ' featured';
			}

			if ($show_filter == 1) {
				foreach ($item->tags as $tag) {
					$class .= ' ' . $tag->alias;
				}
			} elseif ($show_filter != 0) {
				$class	.= ' ' . $item->category_alias;
			}

			$registry = new JRegistry();
			$registry->loadString($item->params);
			$thumb_width = $registry->get('image_width');
			$thumb_height = $registry->get('image_height');

			if ($thumb_width != '' || $thumb_height != '') {
				$img_html = JHtml::_('advportfoliopro.image', $item->thumbnail, $thumb_width, $thumb_height, $item->thumbnail, false);
			} else {
				$img_html = JHtml::_('advportfoliopro.image', $item->thumbnail, $item->featured ? $image_width * 2 : $image_width, $item->featured ? $image_height * 2 : $image_height, $item->thumbnail, false);
			}

			?>

		<div class="isotope-item project-<?php echo $item->id . $class . $item_class; ?>">
			<?php if ($item->thumbnail) : ?>
				<div class="project-img">
					<a href="<?php echo $link; ?>">
						<?php echo $img_html; ?>
					</a>

					<?php if ($this->params->get('show_info', 1)) : ?>

					<div class="project-img-extra">
						<?php if ($this->params->get('click_thumbnail_to', 1)) : ?>
							<a class="link-detail" href="<?php echo $link; ?>" <?php echo $str_target; ?> ></a>
						<?php else: ?>
							<a class="link-detail gallery-popup" data-project-id="<?php echo $item->id; ?>" href="<?php echo $link; ?>"></a>
						<?php endif; ?>
						<div class="project-img-extra-content">
							<?php if ($this->params->get('show_info_project_details', 1)) : ?>
							<a class="project-icon" href="<?php echo $link; ?>" title="<?php echo JText::_('COM_ADVPORTFOLIOPRO_DETAILS'); ?>">
								<?php echo JText::_('COM_ADVPORTFOLIOPRO_DETAILS'); ?>
							</a>
							<?php endif; ?>

							<?php if ($item->link && $this->params->get('show_info_project_link', 1)) : ?>
							<a class="project-icon link-icon" href="<?php echo $item->link; ?>" <?php echo $str_target; ?> title="<?php echo JText::_('COM_ADVPORTFOLIOPRO_LINK'); ?>">
								<?php echo JText::_('COM_ADVPORTFOLIOPRO_LINK'); ?>
							</a>
							<?php endif; ?>

							<?php if ($this->params->get('show_info_project_gallery', 1)) : ?>
							<a class="project-icon gallery-icon" data-project-id="<?php echo $item->id; ?>" href="<?php echo $link; ?>" title="<?php echo JText::_('COM_ADVPORTFOLIOPRO_GALLERY'); ?>">
								<?php echo JText::_('COM_ADVPORTFOLIOPRO_GALLERY'); ?>
							</a>
							<?php endif; ?>

							<?php if ($this->params->get('show_info_title', 1)) : ?>
							<h4><?php echo $item->title; ?></h4>
							<?php endif; ?>

							<?php if ($this->params->get('show_info_category', 1)) : ?>
							<h5><a href="<?php echo $cat_link ?>" <?php echo $str_target; ?> ><?php echo $item->category_title; ?></a></h5>
							<?php endif; ?>
						</div>
					</div>

					<?php endif; ?>
				</div>
			<?php endif; ?>

			<?php if ($this->params->get('show_title_list', 1) || $this->params->get('show_category') || $this->params->get('show_short_description', 1)) : ?>

			<div class="project-item-meta">
				<?php if ($this->params->get('show_title_list', 1)) : ?>
					<h4>
						<a rel="bookmark" title="<?php echo $item->title; ?>" href="<?php echo $link; ?>" <?php echo $str_target; ?>>
							<?php echo $item->title; ?>
						</a>
					</h4>
				<?php endif; ?>

				<?php if ($this->params->get('show_category')) : ?>
					<h5>
						<a href="<?php echo $cat_link ?>">
							<?php echo $item->category_title; ?>
						</a>
					</h5>
				<?php endif; ?>

				<?php if ($this->params->get('show_short_description', 1)) : ?>
					<?php echo $item->short_description; ?>
				<?php endif; ?>
			</div>

			<?php endif; ?>
		</div>

		<?php endforeach; ?>
	</div>

	<?php if (($this->params->def('show_pagination', 1) == 1  || ($this->params->get('show_pagination') == 2)) && ($this->pagination->get('pages.total') > 1)) : ?>
	<div class="pagination">
		<?php  if ($this->params->def('show_pagination_results', 1)) : ?>
		<p class="counter pull-right"><?php echo $this->pagination->getPagesCounter(); ?></p>
		<?php endif; ?>
		<?php echo $this->pagination->getPagesLinks(); ?> </div>
	<?php  endif; ?>
</div>