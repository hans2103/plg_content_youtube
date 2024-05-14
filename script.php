<?php

/**
 * @package         Joomla.Plugin
 * @subpackage      Content.youtube
 *
 * @copyright   (C) 2024 HKweb <https://hkweb.nl>
 * @license         GNU General Public License version 2 or later; see LICENSE.txt
 */


// No direct access
defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Log\Log;
use Joomla\CMS\Installer\InstallerScript;

class plgContentYoutubeInstallerScript extends InstallerScript
{
	protected $minimumPHPVersion = '8.0.0';
	protected $minimumJoomlaVersion = '5.0.0';

	public function install($parent)
	{
		// Enable the extension
		$this->enablePlugin();

		return true;
	}

	public function uninstall($parent)
	{
	}

	public function update($parent)
	{
	}

	public function preflight($type, $parent)
	{
		// Check the minimum PHP version
		if (!version_compare(PHP_VERSION, $this->minimumPHPVersion, '>='))
		{
			$msg = '<p>You need PHP ' . $this->minimumPHPVersion
				. ' or later to install this plugin.</p>';
			Log::add($msg, Log::WARNING, 'jerror');

			return false;
		}

		// Check the minimum Joomla! version
		if (!version_compare(JVERSION, $this->minimumJoomlaVersion, '>='))
		{
			$msg = '<p>You need Joomla! ' . $this->minimumJoomlaVersion
				. ' or later to install this plugin.</p>';
			Log::add($msg, Log::WARNING, 'jerror');

			return false;
		}

		return true;
	}

	public function postflight($type, $parent)
	{
	}

	private function enablePlugin()
	{
		try
		{
			$db = Factory::getDbo();
			$query = $db->getQuery(true)
				->update($db->quoteName('#__extensions'))
				->set($db->quoteName('enabled') . ' = 1')
				->where($db->quoteName('type') . ' = ' . $db->quote('plugin'))
				->where(
					$db->quoteName('folder') . ' = ' . $db->quote('content')
				)
				->where(
					$db->quoteName('element') . ' = ' . $db->quote('youtube')
				);

			$db->setQuery($query)->execute();
		}
		catch (\Exception $e)
		{
			// Handle any errors here, e.g., log the error message.
			Factory::getApplication()->enqueueMessage(
				'Error enabling the plugin: ' . $e->getMessage(), 'error'
			);
		}
	}
}
