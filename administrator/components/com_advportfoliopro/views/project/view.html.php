<?php
/**
 * @copyright	Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * View to edit a Project.
 *
 * @package		Joomla.Administrator
 * @subpakage	ExtStore.AdvPortfolioPro
 */
class AdvPortfolioProViewProject extends JViewLegacy {

	protected $state;
	protected $item;
	protected $form;

	/**
	 * Display the view.
	 */
	public function display($tpl = null) {
		$this->state	= $this->get('State');
		$this->item		= $this->get('Item');
		$this->form		= $this->get('Form');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		$this->addToolbar();
		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 */
	protected function addToolbar() {
		JFactory::getApplication()->input->set('hidemainmenu', true);

		$user		= JFactory::getUser();
		$isNew		= $this->item->id == 0;
		$checkedOut	= !($this->item->checked_out == 0 || $this->item->checked_out == $user->get('id'));
		$canDo		= AdvPortfolioProHelper::getActions($this->state->get('filter.category_id'), $this->item->id);

		JToolBarHelper::title(JText::_('COM_ADVPORTFOLIOPRO_PROJECT_MANAGER'), 'project.png');

		// If not checked out, can save the item.
		if (!$checkedOut && ($canDo->get('core.edit') || (count($user->getAuthorisedCategories('com_advportfoliopro', 'core.create'))))) {
			JToolBarHelper::apply('project.apply');
			JToolBarHelper::save('project.save');
		}

		if (!$checkedOut && (count($user->getAuthorisedCategories('com_advportfoliopro', 'core.create')))) {
			JToolBarHelper::save2new('project.save2new');
		}

		// If an existing item, can save to a copy.
		if (!$isNew && (count($user->getAuthorisedCategories('com_advportfoliopro', 'core.create')))) {
			JToolBarHelper::save2copy('project.save2copy');
		}

		if (empty($this->item->id)) {
			JToolBarHelper::cancel('project.cancel');
		} else {
			JToolBarHelper::cancel('project.cancel', 'JTOOLBAR_CLOSE');
		}
	}
}