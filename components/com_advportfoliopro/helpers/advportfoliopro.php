<?php
/**
 * @copyright	Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access.
defined('_JEXEC') or die;

/**
 * Advanced Portfolio Helper.
 *
 * @package		Joomla.Site
 * @subpakage	ExtStore.AdvPortfolioPro
 */
class AdvPortfolioProHelper {

	/**
	 * Get model of component
	 */
	public static function getModel($type, $config = array()) {
		return JModelLegacy::getInstance($type, 'AdvPortfolioProModel', $config);
	}

	/**
	 * Method to get value of images field.
	 *
	 * @params	string	$value	Raw data string.
	 * @return	array	Array of images.
	 */
	public static function getImages($value) {
		$items	= array();

		if (is_array($value)) {
			if (isset($value['image']) && count($value['image'])) {
				for ($i = 0, $n = count($value['image']); $i < $n; $i++) {
					$item			= new stdClass();
					$item->image	= $value['image'][$i];
					$item->title	= $value['title'][$i];

					if ($item->image) {
						$items[]		= $item;
					}
				}
			}
		} else if (is_object($value)) {
			if (isset($value->image) && count($value->image)) {
				for ($i = 0, $n = count($value->image); $i < $n; $i++) {
					$item			= new stdClass();
					$item->image	= $value->image[$i];
					$item->title	= $value->title[$i];

					if ($item->image) {
						$items[]		= $item;
					}
				}
			}
		}

		return $items;
	}

	public static function hex2RGB($hexStr, $returnAsString = false, $seperator = ',') {
		$hexStr = preg_replace("/[^0-9A-Fa-f]/", '', $hexStr); // Gets a proper hex string
		$rgbArray = array();
		if (strlen($hexStr) == 6) { //If a proper hex code, convert using bitwise operation. No overhead... faster
			$colorVal = hexdec($hexStr);
			$rgbArray['red'] = 0xFF & ($colorVal >> 0x10);
			$rgbArray['green'] = 0xFF & ($colorVal >> 0x8);
			$rgbArray['blue'] = 0xFF & $colorVal;
		} elseif (strlen($hexStr) == 3) { //if shorthand notation, need some string manipulations
			$rgbArray['red'] = hexdec(str_repeat(substr($hexStr, 0, 1), 2));
			$rgbArray['green'] = hexdec(str_repeat(substr($hexStr, 1, 1), 2));
			$rgbArray['blue'] = hexdec(str_repeat(substr($hexStr, 2, 1), 2));
		} else {
			return false; //Invalid hex color code
		}
		return $returnAsString ? implode($seperator, $rgbArray) : $rgbArray; // returns the rgb string or the associative array
	}

	public static function processTags($items, $type) {
		if ($type == 0) {
			return array();
		}

		$tags		= array();
		$objects	= array();

		foreach ($items as $item) {
			if ($type == 1) {	// tag
				$objects		= array_merge($objects, $item->tags);
			} else {			// category
				$tmp			= new stdClass();
				$tmp->title		= $item->category_title;
				$tmp->alias		= $item->category_alias;
				$tmp->ordering	= $item->category_ordering;

				$objects[]		= $tmp;
			}
		}

		//
		$n		= count($objects);
		for ($i = 0; $i < $n - 1; $i++) {
			for ($j = $i + 1; $j < $n; $j++) {
				if ($objects[$i]->ordering > $objects[$j]->ordering) {
					$tmp			= $objects[$i];
					$objects[$i]	= $objects[$j];
					$objects[$j]	= $tmp;
				}
			}
		}

		foreach ($objects as $object) {
			$tags[$object->alias]	= $object->title;
		}

		return array_unique($tags);
	}
}