<?php
/**
 * @copyright	Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access.
defined('_JEXEC') or die;

JHtml::_('jquery.framework');
JHtml::_('stylesheet', 'com_advportfoliopro/style.css', array(), true);

$hasMedia	= ($this->item->type == 0 && count($this->item->images)) || ($this->item->type == 1 && $this->item->video_link);
$hasContent	= $this->item->description != '' && $this->item->params->def('show_description', 1);
$this->item->params->def('description_position', 'right');
$this->item->params->def('description_width', '4');

$this->document->addScriptDeclaration("
jQuery(document).ready(function() {
	if (parseInt(jQuery('#project-wrapper [class*=\"col-sm-\"]').css('padding-left')) == 0) {
		jQuery('#project-wrapper').removeClass('row');
	}
});
");
?>

<div class="item-page<?php echo $this->pageclass_sfx; ?>">
	<?php if ($this->item->params->get('show_page_heading')) : ?>
		<div class="page-header">
			<h1>
				<?php echo $this->escape($this->item->params->get('page_heading')); ?>
			</h1>

			<?php if (!$this->item->params->get('show_title')) : ?>
				<div class="project-nav">
					<a class="prev-project<?php echo $this->prevItem ? '' : ' disable'; ?>" href="<?php echo $this->prevItem ? AdvPortfolioProHelperRoute::getProjectRoute($this->prevItem->slug, $this->prevItem->catslug) : 'javascript:void(0);'; ?>"></a>
					<a class="next-project<?php echo $this->nextItem ? '' : ' disable'; ?>" href="<?php echo $this->nextItem ? AdvPortfolioProHelperRoute::getProjectRoute($this->nextItem->slug, $this->nextItem->catslug) : 'javascript:void(0);'; ?>"></a>
				</div>
			<?php endif; ?>
		</div>
	<?php endif; ?>

	<?php if ($this->item->params->get('show_title', true)) : ?>
	<div class="page-header">
		<h2 itemprop="name">
			<?php echo $this->escape($this->item->title); ?>
		</h2>

		<div class="project-nav">
			<a class="prev-project<?php echo $this->prevItem ? '' : ' disable'; ?>" href="<?php echo $this->prevItem ? AdvPortfolioProHelperRoute::getProjectRoute($this->prevItem->slug, $this->prevItem->catslug) : 'javascript:void(0);'; ?>"></a>
			<a class="next-project<?php echo $this->nextItem ? '' : ' disable'; ?>" href="<?php echo $this->nextItem ? AdvPortfolioProHelperRoute::getProjectRoute($this->nextItem->slug, $this->nextItem->catslug) : 'javascript:void(0);'; ?>"></a>
		</div>
	</div>
	<?php endif; ?>

	<div id="project-wrapper" class="project-wrapper clearfix">
		<?php if ($hasMedia && $hasContent) : ?>

			<?php if ($this->item->params->get('description_position') == 'top' || $this->item->params->get('description_position') == 'bottom') : ?>
				<?php if ($this->item->params->get('description_position') == 'top') : ?>
					<div>
						<?php echo $this->loadTemplate('description'); ?>
					</div>
				<?php endif; ?>

				<div>
					<?php echo $this->loadTemplate('media'); ?>
				</div>

				<?php if ($this->item->params->get('description_position') == 'bottom') : ?>
					<div>
						<?php echo $this->loadTemplate('description'); ?>
					</div>
				<?php endif; ?>

			<?php else : ?>

				<?php if ($this->item->params->get('description_position') == 'left') : ?>
					<div class="col<?php echo $this->item->params->get('description_width'); ?>">
						<?php echo $this->loadTemplate('description'); ?>
					</div>
				<?php endif; ?>

				<div class="col<?php echo (12 - $this->item->params->get('description_width')); ?>">
					<?php echo $this->loadTemplate('media'); ?>
				</div>

				<?php if ($this->item->params->get('description_position') == 'right') : ?>
					<div class="col<?php echo $this->item->params->get('description_width'); ?>">
						<?php echo $this->loadTemplate('description'); ?>
					</div>
				<?php endif; ?>

			<?php endif; ?>

		<?php else : ?>

			<?php if ($hasMedia) : ?>
				<div>
					<?php echo $this->loadTemplate('media'); ?>
				</div>
			<?php else : ?>
				<?php echo $this->loadTemplate('description'); ?>
			<?php endif; ?>

		<?php endif; ?>
	</div>
</div>