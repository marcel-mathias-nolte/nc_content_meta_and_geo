<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (C) 2005-2012 Leo Feyer
 *
 * @package   NC Content Meta And Geotag
 * @author    Marcel Mathias Nolte
 * @copyright Marcel Mathias Nolte 2015
 * @website   https://www.noltecomputer.com
 * @license   <marcel.nolte@noltecomputer.de> wrote this file. As long as you retain this notice you
 *            can do whatever you want with this stuff. If we meet some day, and you think this stuff
 *            is worth it, you can buy me a beer in return. Meanwhile you can provide a link to my
 *            homepage, if you want, or send me a postcard. Be creative! Marcel Mathias Nolte
 */
 
/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace NC;

/**
 * Class ModuleNcContentMap 
 *
 * Front end module "contact form".
 */
class ModuleNcContentMap extends \Module
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'nc_content_map_default';


	/**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new \BackendTemplate('be_wildcard');
			$objTemplate->wildcard = '### CONTENT GEOTAG MAP ###';
			return $objTemplate->parse();
		}
		if ($this->nc_content_map_template)
		{
			$this->strTemplate = $this->nc_content_map_template;
		}
		return parent::generate();
	}
	
	/**
	 * Generate the module
	 */
	protected function compile()
	{
		$this->import('NcContentMap');
		$this->Template->items = $this->NcContentMap->getMapItems(deserialize($this->nc_content_map_sources));
	}
}