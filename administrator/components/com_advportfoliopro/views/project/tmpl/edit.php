<?php
/**
 * @copyright	Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access.
defined('_JEXEC') or die;

JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');
?>

<script type="text/javascript">
	(function($) {
		function showMedia(video) {
			if (video != 0) {
				$('#images-container').hide('slide');
				$('#video_link-container').show('slide');
			} else {
				$('#images-container').show('slide');
				$('#video_link-container').hide('slide');
			}
		}

		$(document).ready(function() {
			showMedia($('#jform_type input:checked').val());

			$('#jform_type label').click(function() {
				showMedia($('#' + $(this).attr('for')).val());
			});
		});
	})(jQuery);


	Joomla.submitbutton	= function(task) {
		if (task == 'project.cancel' || document.formvalidator.isValid(document.id('project-form'))) {
			<?php echo $this->form->getField('description')->save(); ?>
			Joomla.submitform(task, document.getElementById('project-form'));
		} else {
			alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
		}
	}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_advportfoliopro&layout=edit&id=' . (int) $this->item->id); ?>" method="post" name="adminForm" id="project-form" class="form-validate">
	<div class="row-fluid">

		<div class="span10 form-horizontal">
			<fieldset>
				<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'details')); ?>
					<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'details', empty($this->item->id) ? JText::_('COM_ADVPORTFOLIOPRO_PROJECT_NEW') : JText::sprintf('COM_ADVPORTFOLIOPRO_PROJECT_EDIT', $this->item->id)); ?>

						<div class="control-group">
							<?php echo $this->form->getLabel('title'); ?>
							<div class="controls"><?php echo $this->form->getInput('title'); ?></div>
						</div>
						<div class="control-group">
							<?php echo $this->form->getLabel('link'); ?>
							<div class="controls"><?php echo $this->form->getInput('link'); ?></div>
						</div>
						<div class="control-group">
							<?php echo $this->form->getLabel('ordering'); ?>
							<div class="controls"><?php echo $this->form->getInput('ordering'); ?></div>
						</div>
						<div class="control-group">
							<?php echo $this->form->getLabel('short_description'); ?>
							<div class="controls"><?php echo $this->form->getInput('short_description'); ?></div>
						</div>
						<div class="control-group">
							<?php echo $this->form->getLabel('description'); ?>
							<div class="controls"><?php echo $this->form->getInput('description'); ?></div>
						</div>

					<?php echo JHtml::_('bootstrap.endTab'); ?>


					<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'images', JText::_('COM_ADVPORTFOLIOPRO_PROJECT_FIELDSET_MEDIA', true)); ?>

						<div class="control-group">
							<?php echo $this->form->getLabel('thumbnail'); ?>
							<div class="controls"><?php echo $this->form->getInput('thumbnail'); ?></div>
						</div>
						<div class="control-group">
							<?php echo $this->form->getLabel('type'); ?>
							<div class="controls"><?php echo $this->form->getInput('type'); ?></div>
						</div>
						<div class="control-group" id="video_link-container">
							<?php echo $this->form->getLabel('video_link'); ?>
							<div class="controls"><?php echo $this->form->getInput('video_link'); ?></div>
						</div>
						<div class="control-group" id="images-container">
							<?php echo $this->form->getLabel('images'); ?>
							<div class="controls"><?php echo $this->form->getInput('images'); ?></div>
						</div>

					<?php echo JHtml::_('bootstrap.endTab'); ?>

					<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'publishing', JText::_('JGLOBAL_FIELDSET_PUBLISHING', true)); ?>
						<div class="control-group">
							<?php echo $this->form->getLabel('alias'); ?>
							<div class="controls"><?php echo $this->form->getInput('alias'); ?></div>
						</div>
						<div class="control-group">
							<?php echo $this->form->getLabel('id'); ?>
							<div class="controls"><?php echo $this->form->getInput('id'); ?></div>
						</div>
						<div class="control-group">
							<?php echo $this->form->getLabel('created_by'); ?>
							<div class="controls"><?php echo $this->form->getInput('created_by'); ?></div>
						</div>
						<div class="control-group">
							<?php echo $this->form->getLabel('created_by_alias'); ?>
							<div class="controls"><?php echo $this->form->getInput('created_by_alias'); ?></div>
						</div>
						<div class="control-group">
							<?php echo $this->form->getLabel('created'); ?>
							<div class="controls"><?php echo $this->form->getInput('created'); ?></div>
						</div>
						<div class="control-group">
							<?php echo $this->form->getLabel('modified_by'); ?>
							<div class="controls"><?php echo $this->form->getInput('modified_by'); ?></div>
						</div>
						<div class="control-group">
							<?php echo $this->form->getLabel('modified'); ?>
							<div class="controls"><?php echo $this->form->getInput('modified'); ?></div>
						</div>
					<?php echo JHtml::_('bootstrap.endTab'); ?>

					<?php $fieldSets = $this->form->getFieldsets('params'); ?>
					<?php foreach ($fieldSets as $name => $fieldSet) : ?>
						<?php $paramstabs = 'params-' . $name; ?>
						<?php echo JHtml::_('bootstrap.addTab', 'myTab', $paramstabs, JText::_($fieldSet->label, true)); ?>
							<?php echo $this->loadTemplate('params'); ?>
						<?php echo JHtml::_('bootstrap.endTab'); ?>
					<?php endforeach; ?>

					<?php $fieldSets = $this->form->getFieldsets('metadata'); ?>
					<?php foreach ($fieldSets as $name => $fieldSet) : ?>
						<?php $metadatatabs = 'metadata-' . $name; ?>
						<?php echo JHtml::_('bootstrap.addTab', 'myTab', $metadatatabs, JText::_($fieldSet->label, true)); ?>
							<?php echo $this->loadTemplate('metadata'); ?>
						<?php echo JHtml::_('bootstrap.endTab'); ?>
					<?php endforeach; ?>
				<?php echo JHtml::_('bootstrap.endTabSet'); ?>
			</fieldset>
		</div>

		<div class="span2">
			<?php echo JLayoutHelper::render('joomla.edit.global', $this); ?>
		</div>
	</div>

	<input type="hidden" name="task" value="" />
	<input type="hidden" name="return" value="<?php echo JFactory::getApplication()->input->getCmd('return');?>" />
	<?php echo JHtml::_('form.token'); ?>
</form>

<?php
echo AdvPortfolioProFactory::getFooter();