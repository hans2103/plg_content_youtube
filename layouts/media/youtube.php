<?php

/**
 * @package     Joomla.Plugin
 * @subpackage  Layout.media.youtube
 *
 * @copyright   (C) 2024 HKweb <https://hkweb.nl>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\WebAsset\WebAssetManager;
use Joomla\Utilities\ArrayHelper;

/**
 *
 * @param string $videoId The YouTube video ID
 * @param object $params The plugin params
 */

/** @var array $displayData */
extract($displayData);

$videoId = $videoId ?? false;

if ($videoId) {
	/** @var WebAssetManager $wa */
	$wa = Factory::getApplication()->getDocument()->getWebAssetManager();
	$wa->registerAndUseScript('youtubeJS', 'media/plg_content_youtube/js/YouTubeContainer.js', ['relative' => true, 'version' => 'auto'], ['type' => 'module'])
		->registerAndUseStyle('youtubeCSS', 'media/plg_content_youtube/css/YouTubeContainer.css');

	$wrapperAttribs = [];
	$wrapperAttribs['class'] = 'frame__wrapper ' . $params->get('wrapper_class', '');

	$videoAttribs = [];
	$videoAttribs['class'] = 'embed-youtube ratio:16:9';
	$videoAttribs['data-video-id'] = $videoId;
	$videoAttribs['data-video-src'] = 'https://img.youtube.com/vi/' . $videoId . '/maxresdefault.jpg';
	$videoAttribs['data-video-srcset'] = '';

	echo '<figure ' . ArrayHelper::toString($wrapperAttribs) . '>';
	echo '<div class="frame__media ratio:16:9">';
	echo '<div ' . ArrayHelper::toString($videoAttribs) . '>';
	echo '<button type="button" class="embed-youtube-play">';
	echo '<span class="visually:hidden">Click to load video</span>';
	echo '</button>';
	echo '</div>';
	echo '</div>';
	echo '</figure>';
}
