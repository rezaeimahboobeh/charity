<?php
/**
 * @copyright	Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access.
defined('_JEXEC') or die;

/**
 * View class for a list of Projects.
 *
 * @package		Joomla.Administrator
 * @subpakage	ExtStore.AdvPortfolioPro
 */
class AdvPortfolioProViewProjects extends JViewLegacy {
	protected $items;
	protected $pagination;
	protected $state;

	/**
	 * Display the view.
	 */
	public function display($tpl = null) {
		$this->state		= $this->get('State');
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');

		AdvPortfolioProHelper::addSubmenu('projects');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		$this->addToolbar();
		$this->sidebar = JHtmlSidebar::render();
		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 */
	protected function addToolbar() {
		require_once JPATH_COMPONENT . '/helpers/advportfoliopro.php';

		$state	= $this->get('State');
		$canDo	= AdvPortfolioProHelper::getActions($state->get('filter.category_id'));
		$user	= JFactory::getUser();
		$bar	= JToolBar::getInstance('toolbar');

		JToolBarHelper::title(JText::_('COM_ADVPORTFOLIOPRO_PROJECTS_MANAGER'), 'projects.png');

		if (count($user->getAuthorisedCategories('com_advportfoliopro', 'core.create'))) {
			JToolBarHelper::addNew('project.add');
		}

		if ($canDo->get('core.edit')) {
			JToolBarHelper::editList('project.edit');
		}

		if ($canDo->get('core.edit.state')) {
			JToolBarHelper::publish('projects.publish', 'JTOOLBAR_PUBLISH', true);
			JToolBarHelper::unpublish('projects.unpublish', 'JTOOLBAR_UNPUBLISH', true);

			JToolBarHelper::archiveList('projects.archive');
			JToolBarHelper::checkin('projects.checkin');
		}

		if ($state->get('filter.state') == -2 && $canDo->get('core.delete')) {
			JToolBarHelper::deleteList('', 'projects.delete', 'JTOOLBAR_EMPTY_TRASH');
		} else if ($canDo->get('core.edit.state')) {
			JToolBarHelper::trash('projects.trash');
		}


		// Add a batch button
		if ($canDo->get('core.edit')) {
			JHtml::_('bootstrap.modal', 'collapseModal');
			$title = JText::_('JTOOLBAR_BATCH');
			$dhtml = "<button data-toggle=\"modal\" data-target=\"#collapseModal\" class=\"btn btn-small\">
						<i class=\"icon-checkbox-partial\" title=\"$title\"></i>
						$title</button>";
			$bar->appendButton('Custom', $dhtml, 'batch');
		}

		// Add a import data button
		if (AdvPortfolioProHelper::checkFreeProjectsTable()) {
			JToolbarHelper::custom('import.data', 'upload', 'upload', 'COM_ADVPORTFOLIOPRO_IMPORT', false);
		}

		if ($canDo->get('core.admin')) {
			JToolBarHelper::preferences('com_advportfoliopro');
		}

		JHtmlSidebar::setAction('index.php?option=com_advportfoliopro&view=projects');

		JHtmlSidebar::addFilter(
			JText::_('JOPTION_SELECT_PUBLISHED'),
			'filter_state',
			JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), 'value', 'text', $this->state->get('filter.state'), true)
		);

		JHtmlSidebar::addFilter(
			JText::_('JOPTION_SELECT_CATEGORY'),
			'filter_category_id',
			JHtml::_('select.options', JHtml::_('category.options', 'com_advportfoliopro'), 'value', 'text', $this->state->get('filter.category_id'))
		);

		JHtmlSidebar::addFilter(
			JText::_('JOPTION_SELECT_ACCESS'),
			'filter_access',
			JHtml::_('select.options', JHtml::_('access.assetgroups'), 'value', 'text', $this->state->get('filter.access'))
		);

		JHtmlSidebar::addFilter(
			JText::_('JOPTION_SELECT_LANGUAGE'),
			'filter_language',
			JHtml::_('select.options', JHtml::_('contentlanguage.existing', true, true), 'value', 'text', $this->state->get('filter.language'))
		);
	}

	/**
	 * Returns an array of fields the table can be sorted by
	 *
	 * @return  array  Array containing the field name to sort by as the key and display text as value
	 */
	protected function getSortFields() {
		return array(
			'a.ordering'	=> JText::_('JGRID_HEADING_ORDERING'),
			'a.state'		=> JText::_('JSTATUS'),
			'a.title'		=> JText::_('JGLOBAL_TITLE'),
			'a.access'		=> JText::_('JGRID_HEADING_ACCESS'),
			'a.language'	=> JText::_('JGRID_HEADING_LANGUAGE'),
			'a.id'			=> JText::_('JGRID_HEADING_ID')
		);
	}
}