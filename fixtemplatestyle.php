<?php
/**
 * @package    FixTemplateStyle
 * @copyright  Copyright (C) 2012-2014 CMExtension Team http://www.cmext.vn/
 * @license    GNU General Public License version 2 or later
 */

defined('_JEXEC') or die();

/**
 * Fix issue in plgSocialProfilesJomsocial plugin.
 * In its constructor:
 *
 * $this->_componentFolder = JPATH_SITE . '/components/com_community';
 * $this->_componentFile = 'libraries/core.php';
 * parent::__construct($subject, $params);
 * if ($this->componentLoaded())
 *     include_once($this->_componentFolder . '/' . $this->_componentFile);
 *
 * The result of loading JomSocial's core libraries is
 * JomSocial trying to get the active menu item to get the template, however Joomla's menu is not loaded yet.
 * JomSocial needs to fallback to the default menu item.
 * So if the actual menu item uses its own template style,
 * this template is not used at all, the default template is used instead.
 *
 * This plugin is used to set the template back to the menu item's one.
 *
 * @since  0.0.1
 */
class PlgSystemFixTemplateStyle extends JPlugin
{
	/**
	 * onAfterRoute()
	 *
	 * @return   void
	 *
	 * @since    0.0.1
	 */
	public function onAfterRoute()
	{
		$app = JFactory::getApplication();

		if (!$app->isAdmin())
		{
			$currentTemplate = $app->getTemplate();
			$currentMenuItem = $app->getMenu()->getActive();

			if (!empty($currentMenuItem)
				&& (int) $currentMenuItem->template_style_id != (int) $currentMenuItem->id)
			{
				$db = JFactory::getDbo();
				$query = $db->getQuery(true)
					->select('template')
					->from('#__template_styles')
					->where('id = ' . $db->quote((int) $currentMenuItem->template_style_id));

				$db->setQuery($query);
				$template = $db->loadResult();

				if (!empty($template) && $template != $currentTemplate)
				{
					$app->setTemplate($template);
				}
			}
		}
	}
}
