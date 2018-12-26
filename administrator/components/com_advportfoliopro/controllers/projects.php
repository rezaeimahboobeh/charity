<?php
/**
 * @copyright	Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access.
defined('_JEXEC') or die;

/**
 * Project List Controller Class.
 *
 * @package		Joomla.Administrator
 * @subpakage	ExtStore.AdvPortfolioPro
 */
class AdvPortfolioProControllerProjects extends JControllerAdmin {
	/** @var string		The prefix to use with controller messages. */
	protected $text_prefix	= 'COM_ADVPORTFOLIOPRO_PROJECTS';

	/**
	 * Constructor.
	 */
	public function __construct($config = array()) {
		parent::__construct($config);

		$this->registerTask('unfeatured',	'featured');
	}

	/**
	 * Proxy for getModel.
	 */
	public function getModel($name = 'Project', $prefix = 'AdvPortfolioProModel', $config = array('ignore_request' => true)) {
		$model	= parent::getModel($name, $prefix, $config);
		return $model;
	}

	/**
	 * Method to save the submitted ordering values for records via AJAX.
	 */
	public function saveOrderAjax()
	{
		$pks	= $this->input->post->get('cid', array(), 'array');
		$order	= $this->input->post->get('order', array(), 'array');

		// Sanitize the input
		JArrayHelper::toInteger($pks);
		JArrayHelper::toInteger($order);

		// Get the model
		$model	= $this->getModel();

		// Save the ordering
		$return	= $model->saveorder($pks, $order);

		if ($return) {
			echo "1";
		}

		// Close the application
		JFactory::getApplication()->close();
	}

	/**
	 * Method to toggle the featured setting of a list of projects.
	 */
	public function featured() {
		// Check for request forgeries
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$user	= JFactory::getUser();
		$ids	= $this->input->get('cid', array(), 'array');
		$values	= array('featured' => 1, 'unfeatured' => 0);
		$task	= $this->getTask();
		$value	= JArrayHelper::getValue($values, $task, 0, 'int');

		if (empty($ids)) {
			JError::raiseWarning(500, JText::_('JERROR_NO_ITEMS_SELECTED'));
		} else {
			$table	= $this->getModel()->getTable();

			foreach ($ids as $id) {
				if ($table->load($id)) {
					$table->featured	= $value;
					$table->store();
				}
			}
		}

		$this->setRedirect('index.php?option=com_advportfoliopro&view=projects');
	}
}