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
 * Table tl_content
 */
$GLOBALS['TL_DCA']['tl_content']['palettes']['nc_content_geotag_map'] = '{type_legend},type,headline;{config_legend},nc_content_map_source,nc_content_map_template,nc_content_map_news_page_selector;{image_legend},size,fullsize;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space';

$GLOBALS['TL_DCA']['tl_content']['palettes']['__selector__'][] = 'nc_content_map_news_page_selector';

$GLOBALS['TL_DCA']['tl_content']['subpalettes']['nc_content_map_news_page_selector'] = 'nc_content_map_news_page';

$GLOBALS['TL_DCA']['tl_content']['fields']['nc_content_map_news_page_selector'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_content']['nc_content_map_news_page_selector'],
	'default'                 => 'default',
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'eval'                    => array('submitOnChange'=>true, 'tl_class'=>'w50 clr'),
	'sql'                     => "char(1) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_content']['fields']['nc_content_map_news_page'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_content']['nc_content_map_news_page'],
	'inputType'               => 'pageTree',
	'eval'                    => array('fieldType' => 'radio', 'tl_class'=>'w50'),
	'sql'                     => 'int(10) unsigned NOT NULL default \'0\''
);
$GLOBALS['TL_DCA']['tl_content']['fields']['nc_content_map_source'] = array(
	'label'                   => &$GLOBALS['TL_LANG']['tl_content']['nc_content_map_source'],
	'exclude'                 => true,
	'inputType'               => 'checkboxWizard',
	'options'                 => array('tl_news', 'tl_article', 'tl_files'),
	'eval'                    => array('multiple'=>true),
	'reference'               => &$GLOBALS['TL_LANG']['tl_content']['nc_content_map_sources'],
	'sql'                     => 'text NULL'
);	
$GLOBALS['TL_DCA']['tl_content']['fields']['nc_content_map_template'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_content']['nc_content_map_template'],
	'exclude'                 => true,
	'inputType'               => 'select',
	'options_callback'        => array('NC\NcContentMap', 'getTemplates'),
	'sql'                     => 'varchar(64) NOT NULL default \'\''
);