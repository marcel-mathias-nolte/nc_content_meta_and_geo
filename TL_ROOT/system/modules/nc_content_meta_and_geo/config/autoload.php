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
 * Register the namespaces
 */
ClassLoader::addNamespaces(array
(
	'NC',
));


/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
	// Classes
	'NC\NcContentMap'        => 'system/modules/nc_content_meta_and_geo/classes/NcContentMap.php',
    'NC\NcExifReader'        => 'system/modules/nc_content_meta_and_geo/classes/NcExifReader.php',
	'Pel'                    => 'system/modules/nc_content_meta_and_geo/classes/Pel.php',
	'PelDataWindow'          => 'system/modules/nc_content_meta_and_geo/classes/PelDataWindow.php',
	'PelEntryAscii'          => 'system/modules/nc_content_meta_and_geo/classes/PelEntryAscii.php',
	'PelConvert'             => 'system/modules/nc_content_meta_and_geo/classes/PelConvert.php',
	'PelEntryByte'           => 'system/modules/nc_content_meta_and_geo/classes/PelEntryByte.php',
	'PelEntryLong'           => 'system/modules/nc_content_meta_and_geo/classes/PelEntryLong.php',
	'PelEntryNumber'         => 'system/modules/nc_content_meta_and_geo/classes/PelEntryNumber.php',
	'PelEntry'               => 'system/modules/nc_content_meta_and_geo/classes/PelEntry.php',
	'PelEntryRational'       => 'system/modules/nc_content_meta_and_geo/classes/PelEntryRational.php',
	'PelEntryShort'          => 'system/modules/nc_content_meta_and_geo/classes/PelEntryShort.php',
	'PelEntryUndefined'      => 'system/modules/nc_content_meta_and_geo/classes/PelEntryUndefined.php',
	'PelException'           => 'system/modules/nc_content_meta_and_geo/classes/PelException.php',
	'PelExif'                => 'system/modules/nc_content_meta_and_geo/classes/PelExif.php',
	'PelFormat'              => 'system/modules/nc_content_meta_and_geo/classes/PelFormat.php',
	'PelIfd'                 => 'system/modules/nc_content_meta_and_geo/classes/PelIfd.php',
	'PelJpeg'                => 'system/modules/nc_content_meta_and_geo/classes/PelJpeg.php',
	'PelJpegComment'         => 'system/modules/nc_content_meta_and_geo/classes/PelJpegComment.php',
	'PelJpegContent'         => 'system/modules/nc_content_meta_and_geo/classes/PelJpegContent.php',
	'PelJpegMarker'          => 'system/modules/nc_content_meta_and_geo/classes/PelJpegMarker.php',
	'PelTag'                 => 'system/modules/nc_content_meta_and_geo/classes/PelTag.php',
	'PelTiff'                => 'system/modules/nc_content_meta_and_geo/classes/PelTiff.php',

	// Elements
	'NC\ContentNcContentMap' => 'system/modules/nc_content_meta_and_geo/elements/ContentNcContentMap.php',

	// Modules
	'NC\ModuleNcContentMap'  => 'system/modules/nc_content_meta_and_geo/modules/ModuleNcContentMap.php',
));


/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
	'nc_content_map_default' => 'system/modules/nc_content_meta_and_geo/templates',
	'nc_news_geotag'         => 'system/modules/nc_content_meta_and_geo/templates',
	'nc_content_mapimage'    => 'system/modules/nc_content_meta_and_geo/templates',
	'nc_content_mapnews'     => 'system/modules/nc_content_meta_and_geo/templates',
));
