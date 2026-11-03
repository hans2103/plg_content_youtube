<?php

/**
 * @package     Joomla.Plugin
 * @subpackage  Content.youtube
 *
 * @copyright   (C) 2024 HKweb <https://hkweb.nl>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 *
 * Privacy-friendly YouTube embed without JavaScript.
 *
 * A closed <details> shows only the thumbnail and a play button. The <iframe> lives inside the
 * <details> with loading="lazy": a closed <details> is not rendered, so the browser does not load
 * anything from YouTube until the visitor opens it. The click on <summary> counts as user
 * activation, so autoplay=1 starts the video right away. youtube-nocookie.com is used for the player.
 *
 * Override: copy this file to templates/<template>/html/layouts/plugins/content/youtube/embed.php
 *
 * @var  array                      $displayData
 * @var  string|null                $id      YouTube video id
 * @var  string                     $title   Optional caption / accessible title
 * @var  \Joomla\Registry\Registry  $params  Plugin params
 */

\defined('JPATH_BASE') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;

extract($displayData);

$id    = $id ?? null;
$title = trim((string) ($title ?? ''));

if (!$id) {
	return;
}

Factory::getApplication()->getDocument()->getWebAssetManager()
	->registerAndUseStyle('plg_content_youtube', 'plg_content_youtube/youtube.css', ['relative' => true, 'version' => 'auto']);

$escape = static fn (string $value): string => htmlspecialchars($value, ENT_QUOTES, 'UTF-8');

$thumbnailBase = 'https://img.youtube.com/vi/' . rawurlencode($id) . '/';
$srcset        = [
	$thumbnailBase . 'mqdefault.jpg 320w',
	$thumbnailBase . 'hqdefault.jpg 480w',
];

// maxresdefault does not exist for every (older) video; only offer it when the site chose it
if ($params->get('thumbnail_quality', 'hqdefault') === 'maxresdefault') {
	$srcset[] = $thumbnailBase . 'maxresdefault.jpg 1280w';
}

$playLabel   = $title !== '' ? Text::sprintf('PLG_CONTENT_YOUTUBE_PLAY_VIDEO_TITLE', $title) : Text::_('PLG_CONTENT_YOUTUBE_PLAY_VIDEO');
$iframeTitle = $title !== '' ? $title : Text::_('PLG_CONTENT_YOUTUBE_IFRAME_TITLE');
$playerUrl   = 'https://www.youtube-nocookie.com/embed/' . rawurlencode($id) . '?' . http_build_query(['rel' => 0, 'autoplay' => 1]);
$wrapper     = trim('youtube ' . $params->get('wrapper_class', ''));
?>
<figure class="<?php echo $escape($wrapper); ?>">
	<details class="youtube__details">
		<summary class="youtube__summary">
			<img class="youtube__thumbnail"
				src="<?php echo $escape($thumbnailBase . 'hqdefault.jpg'); ?>"
				srcset="<?php echo $escape(implode(', ', $srcset)); ?>"
				sizes="(min-width: 80rem) 80rem, 100vw"
				width="1280" height="720" alt="" loading="lazy" decoding="async">
			<span class="youtube__play" aria-hidden="true">
				<svg viewBox="0 0 32 32" focusable="false"><path d="m11.17 5.61 16.28 8.47a2.17 2.17 0 0 1 0 3.84L11.17 26.4A2.17 2.17 0 0 1 8 24.47V7.53a2.17 2.17 0 0 1 3.17-1.92z"/></svg>
			</span>
			<span class="youtube__label"><?php echo $escape($playLabel); ?></span>
		</summary>
		<div class="youtube__player">
			<iframe src="<?php echo $escape($playerUrl); ?>"
				title="<?php echo $escape($iframeTitle); ?>"
				allow="autoplay; clipboard-write; encrypted-media; picture-in-picture"
				allowfullscreen
				loading="lazy"
				referrerpolicy="strict-origin-when-cross-origin"></iframe>
		</div>
	</details>
	<?php if ($title !== '') : ?>
		<figcaption class="youtube__caption"><?php echo $escape($title); ?></figcaption>
	<?php endif; ?>
</figure>
