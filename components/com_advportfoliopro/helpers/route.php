<?php
/**
 * @copyright	Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access.
defined('_JEXEC') or die;

/**
 * Advanced Portfolio Pro Component Route Helper.
 *
 * @package		Joomla.Site
 * @subpakage	ExtStore.AdvPortfolioPro
 */
abstract class AdvPortfolioProHelperRoute {
	protected static $lookup = array();

	/**
	 * @param    int    The route of the project item
	 */
	public static function getProjectRoute($id, $catid = 0, $language = 0) {
		$needles = array(
			'project' => array((int) $id)
		);
		//Create the link
		$link = 'index.php?option=com_advportfoliopro&view=project&id=' . $id;

		if ((int) $catid > 1) {
			$categories	= JCategories::getInstance('AdvPortfolioPro');
			$category	= $categories->get((int) $catid);

			if ($category) {
				$needles['category']	= array_reverse($category->getPath());
				$needles['categories']	= $needles['category'];
				$link					.= '&catid=' . $catid;
			}
		}

		if ($language && $language != "*" && JLanguageMultilang::isEnabled()) {
			$db = JFactory::getDBO();
			$query = $db->getQuery(true);
			$query->select('a.sef AS sef');
			$query->select('a.lang_code AS lang_code');
			$query->from('#__languages AS a');

			$db->setQuery($query);
			$langs = $db->loadObjectList();
			foreach ($langs as $lang) {
				if ($language == $lang->lang_code) {
					$link .= '&lang=' . $lang->sef;
					$needles['language'] = $language;
				}
			}
		}

		if ($item = self::_findItem($needles)) {
			$link .= '&Itemid=' . $item;
		} elseif ($item = self::_findItem()) {
			$link .= '&Itemid=' . $item;
		}

		return $link;
	}


	public static function getCategoryRoute($catid, $language = 0) {
		if ($catid instanceof JCategoryNode) {
			$id			= $catid->id;
			$category	= $catid;
		} else {
			$id			= (int)$catid;
			$category	= JCategories::getInstance('AdvPortfolioPro')->get($id);
		}

		if ($id < 1 || !($category instanceof JCategoryNode)) {
			$link = '';
		} else {
			$needles = array();

			$link	= 'index.php?option=com_advportfoliopro&view=projects&catid=' . $id;

			$catids	= array_reverse($category->getPath());
			$needles['category']	= $catids;
			$needles['categories']	= $catids;

			if ($language && $language != "*" && JLanguageMultilang::isEnabled()) {
				self::buildLanguageLookup();

				if (isset(self::$lang_lookup[$language])) {
					$link					.= '&lang=' . self::$lang_lookup[$language];
					$needles['language']	= $language;
				}
			}

			if ($item = self::_findItem($needles)) {
				$link	.= '&Itemid=' . $item;
			}
		}

		return $link;
	}


	protected static function _findItem($needles = null) {
		$app = JFactory::getApplication();
		$menus = $app->getMenu('site');
		$language = isset($needles['language']) ? $needles['language'] : '*';

		// Prepare the reverse lookup array.
		if (!isset(self::$lookup[$language])) {
			self::$lookup[$language] = array();

			$component = JComponentHelper::getComponent('com_advportfoliopro');

			$attributes = array('component_id');
			$values = array($component->id);

			if ($language != '*') {
				$attributes[] = 'language';
				$values[] = array($needles['language'], '*');
			}

			$items = $menus->getItems($attributes, $values);

			foreach ($items as $item) {
				if (isset($item->query) && isset($item->query['view'])) {
					$view = $item->query['view'];
					if (!isset(self::$lookup[$language][$view])) {
						self::$lookup[$language][$view] = array();
					}
					if (isset($item->query['id'])) {

						// here it will become a bit tricky
						// language != * can override existing entries
						// language == * cannot override existing entries
						if (!isset(self::$lookup[$language][$view][$item->query['id']]) || $item->language != '*') {
							self::$lookup[$language][$view][$item->query['id']] = $item->id;
						}
					}
				}
			}
		}

		if ($needles) {
			foreach ($needles as $view => $ids) {
				if (isset(self::$lookup[$language][$view])) {
					foreach ($ids as $id) {
						if (isset(self::$lookup[$language][$view][(int)$id])) {
							return self::$lookup[$language][$view][(int)$id];
						}
					}
				}
			}
		} else {
			$active = $menus->getActive();
			if ($active && $active->component == 'com_advportfoliopro' && ($active->language == '*' || !JLanguageMultilang::isEnabled())) {
				return $active->id;
			}
		}

		return null;
	}
}
