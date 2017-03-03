<?php
/**
* @version 1.0.0
* @package RSFPImageResize 1.0.0
* @copyright (C) 2017 www.renekreijveld.nl
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

class plgSystemRSFPImageResizeInstallerScript
{
	public function preflight($type, $parent) {
		if ($type == 'uninstall') {
			return true;
		}

		$app = JFactory::getApplication();

		if (!file_exists(JPATH_ADMINISTRATOR.'/components/com_rsform/helpers/rsform.php')) {
			$app->enqueueMessage('Please install the RSForm! Pro component before continuing.', 'error');
			return false;
		}

		if (!file_exists(JPATH_ADMINISTRATOR.'/components/com_rsform/helpers/assets.php')) {
			$app->enqueueMessage('Please upgrade RSForm! Pro to at least version 1.51.0 before continuing!', 'error');
			return false;
		}

		$jversion = new JVersion();
		if (!$jversion->isCompatible('3.5.0')) {
			$app->enqueueMessage('Please upgrade to at least Joomla! 3.5.0 before continuing!', 'error');
			return false;
		}

		return true;
	}
}