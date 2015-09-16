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
 * Front end modules
 */
$GLOBALS['FE_MOD']['content']['nc_content_geotag_map'] = 'ModuleNcContentMap';


/**
 * Content elements
 */
$GLOBALS['TL_CTE']['content']['nc_content_geotag_map'] = 'ContentNcContentMap';


/**
 * Hooks
 */
$GLOBALS['TL_HOOKS']['getImage'][] = array('NcExifReader', 'preResize');
$GLOBALS['TL_HOOKS']['postUpload'][] = array('NcExifReader', 'postResize');