<?php

/**
 * @package     Joomla.Plugin
 * @subpackage  Content.youtube
 *
 * @copyright   (C) 2024 HKweb <https://hkweb.nl>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace HKweb\Plugin\Content\YouTube\Extension;

use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\CMS\Plugin\PluginHelper;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * Plugin to enable loading YouTube embed into content (e.g. articles)
 * This uses the {youtube https://www.youtube.com/watch?v=CnvmK6OMJV0} syntax
 *
 * @since  1.5
 */
final class YouTube extends CMSPlugin
{
	/**
	 * Plugin that loads YouTube embed within content
	 *
	 * @param string $context The context of the content being passed to the plugin.
	 * @param object   &$article The article object.  Note $article->text is also available
	 * @param mixed    &$params The article params
	 * @param integer $page The 'page' number
	 *
	 * @return  boolean
	 *
	 * @since   1.6
	 */
	public function onContentPrepare(string $context, object &$article, mixed &$params, int $page = 0): bool
	{
		// Only execute if $article is an object and has a text property
		if (!\is_object($article) || !property_exists($article, 'text') || \is_null($article->text)) {
			return false;
		}

		// Expression to search for (youtube)
		$regex = '/{youtube\s(.*?)}/i';

		// Do not continue when article does not contain '{youtube }'
		if (!str_contains($article->text, '{youtube ')) {
			return false;
		}

		// Remove macros and don't run this plugin when the content is being indexed
		if ($context === 'com_finder.indexer') {
			$article->text = preg_replace($regex, '', $article->text);

			return false;
		}

		// Find all instances of plugin and put in $matches.
		$matches = [];
		preg_match_all($regex, $article->text, $matches, PREG_SET_ORDER);

		if (!$matches) {
			return false;
		}

		$basePath = JPATH_PLUGINS . '/content/youtube/layouts';

		foreach ($matches as $match) {
			if (($start = strpos($article->text, $match[0])) !== false) {
				$youtubeVideoId = self::getYoutubeVideoId($match[1]);

				$output = LayoutHelper::render(
					'media.youtube',
					[
						'videoId' => $youtubeVideoId,
						'params' => $this->params
					],
					$basePath
				);

				$article->text = substr_replace($article->text, $output, $start, strlen($match[0]));
			}
		}

		return true;

	}

	/**
	 * Convert the YouTube URL to embed code
	 *
	 * @param string $url The YouTube URL
	 *
	 * @return string
	 *
	 * @since 1.0.0
	 */
	private function getYoutubeVideoId(string $url): ?string
	{
		$youtubeRegex = '%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i';

		if (preg_match($youtubeRegex, $url, $match)) {
			$youtubeId = $match[1];
		}

		return $youtubeId ?? null;
	}
}
