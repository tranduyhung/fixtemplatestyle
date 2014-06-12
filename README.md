Fix Template Style Joomla! Plug-in
================


Fix issue in plgSocialProfilesJomsocial plugin.
In its constructor:

```php
$this->_componentFolder = JPATH_SITE . '/components/com_community';
$this->_componentFile = 'libraries/core.php';

parent::__construct($subject, $params);

if ($this->componentLoaded())
    include_once($this->_componentFolder . '/' . $this->_componentFile);
```

The result of loading JomSocial's core libraries is JomSocial trying to get the active menu item to get the template, however Joomla's menu is not loaded yet. JomSocial needs to fallback to the default menu item. So if the actual menu item uses its own template style, this template is not used at all, the default template is used instead.
This plugin is used to set the template back to the menu item's one.
