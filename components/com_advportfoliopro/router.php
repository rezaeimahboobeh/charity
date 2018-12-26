<?php
/**
 * @copyright	Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access.
defined('_JEXEC') or die;

/**
 * Build the route for the AdvPortfolioPro component.
 *
 * @params	 array	An array of URL arguments
 * @return
 */
function AdvPortfolioProBuildRoute(&$query) {
	$segments	= array();

	// Get a menu item based on Itemid or currently active
	$app		= JFactory::getApplication();
	$menu		= $app->getMenu();
	$params		= JComponentHelper::getParams('com_advportfoliopro');
	$advanced	= $params->get('sef_advanced_link', 0);

	// We need a menu item. Either the one specified in the query, or the current active one if none specified
	if (empty($query['Itemid'])) {
		$menuItem		= $menu->getActive();
		$menuItemGiven	= false;
	} else {
		$menuItem		= $menu->getItem($query['Itemid']);
		$menuItemGiven	= true;
	}

	if (isset($query['view'])) {
		$view	= $query['view'];
	} else {
		// We need to have a view in the query or it is an invalid URL
		return $segments;
	}

	// Are we dealing with an article or category that is attached to a menu item?
	if (($menuItem instanceof stdClass) && $menuItem->query['view'] == $query['view']) {
		$isActive = false;

		if ($query['view'] == 'projects' && isset($query['catid'])) {
			$catids	= $menuItem->params->get('catids');

			if (count($catids) == 1 && $catids[0] == $query['catid']) {
				$isActive = true;
			}
		} else if (isset($query['id']) && @$menuItem->query['id'] == (int) $query['id']) {
			$isActive = true;
		}

		if ($isActive) {
			unset($query['view']);

			if (isset($query['catid'])) {
				unset($query['catid']);
			}

			if	(isset($query['layout'])) {
				unset($query['layout']);
			}

			unset($query['id']);

			return $segments;
		}
	}

	if ($view == 'projects' || $view == 'project') {
		if (!$menuItemGiven) {
			$segments[]	= $view;
		}

		unset($query['view']);

		if ($view == 'project') {
			if (isset($query['id']) && isset($query['catid']) && $query['catid']) {
				$catid	= $query['catid'];

				// Make sure we have the id and the alias
				if (strpos($query['id'], ':') === false) {
					$db		= JFactory::getDbo();
					$sql	= $db->getQuery(true);
					$sql->select('alias')
						->from('#__advportfoliopro_projects')
						->where('id = ' . (int) $query['id'])
					;
					$db->setQuery($sql);

					$alias			= $db->loadResult();
					$query['id']	= $query['id'] . ':' . $alias;
				}
			} else {
				// We should have these two set for this view. If we don't, it is an error
				return $segments;
			}
		} else {
			if (isset($query['catid'])) {
				$catid	= $query['catid'];
			} else {
				// We should have id set for this view. If we don't, it is an error
				return $segments;
			}
		}

		if ($menuItemGiven && isset($menuItem->query['id'])) {
			$mCatid	= $menuItem->query['id'];
		} else {
			$mCatid	= 0;
		}

		$categories	= JCategories::getInstance('AdvPortfolioPro');
		$category	= $categories->get($catid);

		if (!$category) {
			// We couldn't find the category we were given. Bail.
			return $segments;
		}

		if ($view == 'project' && $menuItem instanceof stdClass && $menuItem->query['view'] == 'projects') {
			$catids	= $menuItem->params->get('catids');

			if (count($catids) == 1 && $catids[0] == (int) $query['catid']) {
				unset($query['catid']);
			}
		}

		if (isset($query['catid'])) {
			$path	= array_reverse($category->getPath());
			$array	= array();

			foreach ($path as $id) {
				if ((int) $id == (int) $mCatid) {
					break;
				}

				list($tmp, $id) = explode(':', $id, 2);
				$array[]		= $id;
			}

			$array	= array_reverse($array);

			if (!$advanced && count($array)) {
				$array[0]	= (int) $catid . ':' . end($array);
			}

			$segments	= array_merge($segments, explode(' ',$array[0]));
		}

		if ($view == 'project') {
			if ($advanced) {
				list($tmp, $id)	= explode(':', $query['id'], 2);
			} else {
				$id	= $query['id'];
			}

			$segments[]	= $id;
		}

		unset($query['id']);
		unset($query['catid']);

		// If the layout is specified and it is the same as the layout in the menu item, we unset it so it doesn't go into the query string
		if (isset($query['layout'])) {
			if ($menuItemGiven && isset($menuItem->query['layout'])) {
				if ($query['layout'] == $menuItem->query['layout']) {
					unset($query['layout']);
				} else if ($query['layout'] == 'default') {
					unset($query['layout']);
				}
			}
		}

		return $segments;
	}

	unset($query['view']);

	return $segments;
}

/**
 * Parse the segments of a URL.
 *
 * @param	array	The segments of the URL to parse.
 * @return	array	The URl attributes to bee usedd by the application.
 */
function AdvPortfolioProParseRoute($segments) {
	$vars	= array();

	// Get the active menu item.
	$app		= JFactory::getApplication();
	$menu		= $app->getMenu();
	$item		= $menu->getActive();
	$params		= JComponentHelper::getParams('com_advportfoliopro');
	$advanced	= $params->get('sef_advanced_link', 0);
	$db			= JFactory::getDbo();

	// Count route segments
	$count		= count($segments);

	// If there is only one segment, then it points to either a project or a category
	// we test it first to see if it is a category. If the id and alias match a category
	// the we assume it is a category. if they don't we assume it is a project.
	if ($count == 1) {
		$array					= explode(':', $segments[0], 2);

		// We check to see if an alias is given. If not, we assume it is a project
		if (strpos($segments[0], ':') === false) {
			$vars['view']	= 'project';
			$vars['id']		= (int) $segments[0];

			return $vars;
		}

		list($id, $alias)	= explode(':', $segments[0], 2);

		// First we check if it is a category
		$category	= JCategories::getInstance('AdvPortfolioPro')->get($id);

		if ($category && $category->alias == $alias) {
			$vars['view']	= 'projects';
			$vars['catid']		= $id;

			return $vars;
		} else {
			$query	= $db->getQuery(true);
			$query->select('a.alias, a.catid')
				->from('#__advportfoliopro_projects AS a')
				->join('LEFT', '#__categories AS c ON c.id = a.catid')
				->where('a.id = ' . (int) $id)
			;
			$db->setQuery($query);

			$project	= $db->loadObject();

			if ($project) {
				if ($project->alias == $alias) {

					if (isset($item->query['option']) && $item->query['option'] == 'com_advportfoliopro' && isset($item->query['view']) && $item->query['view'] == 'projects' && isset($item->query['catid'])) {
						$catid	= $item->query['catid'];
					}

					$vars['catid']	= isset($catid) ? $catid : $project->catid;
					$vars['view']	= 'project';
					$vars['id']		= (int) $id;

					return $vars;
				}
			}
		}
	}

	// Standard routing for projects. If we don't pick up an Itemid then we get the view front the segments
	// the first segment is the view and the last segment is the id of the project or category.
	if (!isset($item)) {
		$vars['view']	= $segments[0];
		$vars['id']		= $segments[$count - 1];

		return $vars;
	}

	// If there was more than one segment then we can determine where the URL points to
	// because the frist segment will have the target category id prepended to it. If the
	// last segment has a number prepended, it is a project, otherwise, it is a category.
	if (!$advanced) {
		$cat_id		= (int) $segments[0];
		$project_id	= (int) $segments[$count - 1];

		if ($project_id > 0) {
			$vars['view']	= 'project';
			$vars['catid']	= $cat_id;
			$vars['id']		= $project_id;
		} else {
			$vars['view']	= 'category';
			$vars['id']		= $cat_id;
		}

		return $vars;
	}

	// We get the category front the menu item and search from there
	$id			= $item->query['id'];
	$category	= JCategories::getInstance('MediaStore')->get('id');

	if (!$category) {
		JError::raiseError(404, JText::_('COM_MEDIASTORE_ERROR_PARENT_CATEGORY_NOT_FOUND'));
		return $vars;
	}

	$categories		= $category->getChildren();
	$vars['catid']	= $id;
	$vars['id']		= $id;
	$found			= 0;

	foreach ($segments as $seegment) {
		$seegment	= str_replace(':', '-', $seegment);

		foreach ($categories as $category) {
			if ($category->alias == $seegment) {
				$vars['id']		= $category->id;
				$vars['catid']	= $category->id;
				$vars['view']	= 'category';
				$categories		= $category->getChildren();
				$found			= 1;
				break;
			}
		}

		if ($found == 0) {
			if ($advanced) {
				$query	= $db->getQuery(true);
				$query->select('id')
					->from('#__mediastore_projects AS a')
					->innerJoin('#__mediastore_projectcat_map AS pc ON a.id = pc.project_id')
					->where('pc.cat_id = ' . $vars['catid'])
					->where('a.alias = ' . $db->quote($seegment))
				;
				$db->setQuery($query);

				$cid	= $db->loadResult();
			} else {
				$cid	= $seegment;
			}

			$vars['id']	= $cid;

			$vars['view']	= 'project';
		}

		$found = 0;
	}

	return $vars;
}