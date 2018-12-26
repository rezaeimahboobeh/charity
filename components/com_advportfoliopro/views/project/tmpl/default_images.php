<?php
/**
 * @copyright	Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access.
defined('_JEXEC') or die;


JHtml::addIncludePath(JPATH_ROOT . '/administrator/components/com_advportfoliopro/helpers/html/');
JHtml::_('advportfoliopro.modal');
JHtml::_('script', 'com_advportfoliopro/jquery.flexslider.js', array(), true);

$this->document->addScriptDeclaration("
(function($) {
	$(document).ready(function() {
		$('.flexslider').flexslider({
			controlNav: false,
			smoothHeight: true
		});
	});
})(jQuery);
");
?>

<div class="flexslider clearfix">
	<ul class="slides">
		<?php foreach ($this->item->images as $image) : ?>
			<li>
				<div class="project-img">
					<a rel="gallery" class="exmodal" title="<?php echo $this->escape($image->title); ?>" href="<?php echo JHtml::_('advportfoliopro.image', $image->image); ?>">
						<?php echo JHtml::_('advportfoliopro.image', $image->image, null, null, $this->escape($image->title ? $image->title : $this->item->title)); ?>
					</a>
				</div>
			</li>
		<?php endforeach; ?>
	</ul>
</div>