<?php
/**
 * @copyright	Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access.
defined('_JEXEC') or die;

// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_advportfoliopro')) {
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

// Include CSS and JS
JHtml::_('behavior.framework', true);
JHtml::_('script', 'com_advportfoliopro/admin.script.js', false, true, false, false, true);
JHtml::_('stylesheet', 'com_advportfoliopro/admin.style.css', false, true, false, false, true);

// Include dependancies
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
require_once JPATH_COMPONENT . '/helpers/factory.php';
require_once JPATH_COMPONENT . '/helpers/imagelib.php';

$controller	= JControllerLegacy::getInstance('AdvPortfolioPro');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();