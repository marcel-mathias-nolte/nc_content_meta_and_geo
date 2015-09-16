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
 * Table tl_files
 */
$GLOBALS['TL_DCA']['tl_files']['palettes']['default'] = strtr(
	$GLOBALS['TL_DCA']['tl_files']['palettes']['default'], 
	array(
		';meta' => ';meta;nc_geotagging_hide,nc_geotagging_location;exif'
	)
);

$GLOBALS['TL_DCA']['tl_files']['fields']['nc_geotagging_location'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_files']['nc_geotagging_location'],
	'inputType'               => 'nc_geo_wizard',
	'eval'                    => array('autoGPS'=>true, 'tl_class'=>'w50'),
	'sql'                     => "text NULL"
);
$GLOBALS['TL_DCA']['tl_files']['fields']['nc_geotagging_hide'] = array(
	'label'                   => &$GLOBALS['TL_LANG']['tl_files']['nc_geotagging_hide'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'sql'                     => 'char(1) NOT NULL default \'\''
);
$GLOBALS['TL_DCA']['tl_files']['fields']['exif'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_files']['exif'],
	'exclude'                 => true,
	'inputType'               => 'multiColumnWizard',
	'sql'                     => "blob NULL",
	'eval' 			=> array
	(
		'columnFields' => array
		(
			'group' => array
			(
				'label'                 => &$GLOBALS['TL_LANG']['tl_files']['exif_group'],
				'exclude'               => true,
				'inputType'             => 'text',
				'eval'                  => array('style'=>'width:100px')
			),
			'key' => array
			(
				'label'                 => &$GLOBALS['TL_LANG']['tl_files']['exif_key'],
				'exclude'               => true,
				'inputType'             => 'text',
				'eval'                  => array('style'=>'width:100px')
			),
			'value' => array
			(
				'label'                 => &$GLOBALS['TL_LANG']['tl_files']['exif_value'],
				'exclude'               => true,
				'inputType'             => 'text',
				'eval'                  => array('style'=>'width:370px')
			)
		)
	)
);