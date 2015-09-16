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

$this->loadLanguageFile('languages');

/**
 * Table tl_files
 */
$GLOBALS['TL_DCA']['tl_settings']['palettes']['default'] .= ';{nc_content_meta_and_geo_legend:hide},nc_auto_meta_languages,nc_append_camera_settings';

$GLOBALS['TL_DCA']['tl_settings']['fields']['nc_auto_meta_languages'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['nc_auto_meta_languages'],
	'exclude'                 => true,
	'inputType'               => 'multiColumnWizard',
	'eval' 			=> array
	(
		'columnFields' => array
		(
			'lang' => array
			(
				'label'                 => &$GLOBALS['TL_LANG']['tl_settings']['nc_auto_meta_language'],
				'exclude'               => true,
				'inputType'             => 'select',
				'options'               => &$GLOBALS['TL_LANG']['LNG'],
				'eval'                  => array('style'=>'width:200px')
			)
		)
	)
);
$GLOBALS['TL_DCA']['tl_settings']['fields']['nc_append_camera_settings'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['nc_append_camera_settings'],
	'inputType'               => 'checkbox',
	'default'                 => true,
	'eval'                    => array('tl_class'=>'w50')
);