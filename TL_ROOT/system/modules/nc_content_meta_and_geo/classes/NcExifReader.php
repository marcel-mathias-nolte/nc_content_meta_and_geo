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
 * Class NcExifReader
 *
 * Read image metadata.
 */
class NcExifReader extends \Backend
{
	/**
	 * Resize an uploaded image if neccessary
	 * @param string
	 * @return array
	 */
	public function readData($strFile)
	{
		$result = array();
		$exif = exif_read_data(TL_ROOT . '/' . $strFile, 0, true);
		if (is_array($exif) && count($exif) > 0)
		{
			foreach ($exif as $group => $section)
			{
				if (is_array($section) && count($section) > 0)
				{
					foreach ($section as $key => $value)
					{
						if (($key == 'GPSLatitude' || $key == 'GPSLongitude' || $key == 'GPSDestLatitude' || $key == 'GPSDestLongitude') && is_array($value) && count($value) >= 3)
						{
							$dec = 0;
							list($val, $quot) = explode('/', $value[0]);
							$dec += $val / $quot;
							list($val, $quot) = explode('/', $value[1]);
							$dec += $val / $quot / 60;
							list($val, $quot) = explode('/', $value[2]);
							$dec += $val / $quot / 3600;
							$value = $dec . '';
						}
						else if (($key == 'GPSImgDirection' || $key == 'FocalLength' || $key == 'ExposureBiasValue' || $key == 'ApertureValue' || $key == 'ShutterSpeedValue' || $key == 'FNumber' || $key == 'ExposureTime') && strpos($value, '/') !== false)
						{
							list($val, $quot) = explode('/', $value);
							$value = ($val / $quot) . '';
						}
						else
						{
							$value = is_array($value) ? implode(';', $value) : $value . '';
							$newvalue = "";
							for($i = 0; $i < strlen($value); $i++)
							{
								if ($value[$i] != "\0")
								{
									$newvalue .= $value[$i];
								}
							}
							$value = utf8_encode($newvalue);
						}
						$result[] = array(
							'group' => $group,
							'key' => $key,
							'value' => $value
						);
					}
				}
			}
		}
		$lng = '';
		$lat = '';
		$author = '';
		$title = '';
		$cam = array(
			'make' => '',
			'model' => '',
			'exposure' => '',
			'aperture' => '',
			'focal' => '',
			'iso ' => '',
			'bias' => '',
		);
		foreach ($result as $row)
		{
			switch (strtolower($row['key']))
			{
				case 'gpslongituderef':
					if (strtolower($row['value']) == 'w')
					{
						$lng = '-' . $lng;
					}
					break;
				case 'gpslatituderef':
					if (strtolower($row['value']) == 's')
					{
						$lat = '-' . $lat;
					}
					break;
				case 'gpslatitude':
					$lat .= $row['value'];
					break;
				case 'gpslongitude':
					$lng .= $row['value'];
					break;
				case 'author':
					$author = $row['value'];
					break;
				case 'title':
					$title = $row['value'];
					break;
				case 'make':
					$cam['make'] = trim($row['value']);
					break;
				case 'model':
					$cam['model'] = trim($row['value']);
					break;
				case 'exposuretime':
					if ($row['value'] >= 1)
					{
						$cam['exposure'] = trim($row['value']) . 's';
					}
					else
					{
						$cam['exposure'] = '1/' . ( 1 / $row['value'] ) . 's';
					}
					break;
				case 'exposurebiasvalue':
					if ($row['value'] != 0)
					{
						$cam['bias'] = ( $row['value'] > 0 ? '+' : '' ) . trim($row['value']) . 'EV';
					}
					break;
				case 'fnumber':
					$cam['aperture'] = 'f/' . trim($row['value']);
					break;
				case 'focallength':
					$cam['focal'] = trim($row['value']) . 'mm';
					break;
				case 'isospeedratings':
					$cam['iso'] = 'ISO' . ( $row['value'] * 1 );
					break;
			}
		}
		if (!empty($lat) && !empty($lng))
		{
			$result[] = array(
				'group' => 'EXTRACTED', 
				'key' => 'GMaps',
				'value' => $lat . ',' . $lng
			);
		}
		$camr = '';
		foreach ($cam as $val)
		{
			if (!empty($val))
			{
				$camr .= $val . ' ';
			}
		}
		if (!empty($camr))
		{
			$result[] = array(
				'group' => 'EXTRACTED', 
				'key' => 'Cam',
				'value' => trim($camr)
			);
		}
		if (!empty($author))
		{
			$result[] = array(
				'group' => 'EXTRACTED', 
				'key' => 'Author',
				'value' => trim($author)
			);
		}
		if (!empty($title))
		{
			$result[] = array(
				'group' => 'EXTRACTED', 
				'key' => 'Title',
				'value' => trim($title)
			);
		}
		return $result;
	}
	
	/**
	 * Capture exif meta data before image processing
	 * @param string
	 * @param integer
	 * @param integer
	 * @param string
	 * @param string
	 * @param object
	 * @param string
	 * @return string
	 */
	public function preResize($image, $width, $height, $mode, $strCacheName, $objFile, $target)
	{
		if ($target && ( substr(strtolower($image), -4, 4) == '.jpg' || substr(strtolower($image), -5, 5) == '.jpeg' ))
		{
			$in = new \PelJpeg(TL_ROOT . '/' . $image);
			$_SESSION['exif_preserve'][$target] = $in->getExif();
		}
		return false;
	}

	/**
	 * Embed exif meta data after image processing and set meta and geo data
	 * @param array
	 */
	public function postResize($arrFiles)
	{
		if (count($arrFiles) > 0)
		{
			foreach ($arrFiles as $file)
			{
				if(isset($_SESSION['exif_preserve'][$file]) && $_SESSION['exif_preserve'][$file] != null)
				{ 
					$out = new \PelJpeg(TL_ROOT . '/' . $file);
					$out->setExif($_SESSION['exif_preserve'][$file]);
					$f = new \File($file);
					$f->write($out->getBytes());
					$f->close();
					unset($_SESSION['exif_preserve'][$file]);
				} 
				$exif = $this->readData($file);
				$meta = array();
				$metaTitle = '';
				$metaCaption = '';
				foreach ($exif as $row)
				{
					if ($row['group'] == 'EXTRACTED')
					{
						if ($row['key'] == 'GMaps')
						{
							list ($lat, $lng) = explode(',', $row['value']);
							$this->Database->prepare("UPDATE tl_files SET nc_geotagging_location = ? WHERE path = ?")->execute(serialize(array('address' => '', 'lat' => $lat, 'lon' => $lng)), $file);
						}
						if ($row['key'] == 'Author')
						{
							$metaCaption = '&copy;' . $row['value'] . ( empty($metaCaption) ? '' : ', ' ) . $metaCaption;
						}
						if ($row['key'] == 'Title')
						{
							$meta['en']['title'] = $meta['de']['title'] = $row['value'];
						}
						if ($row['key'] == 'Cam' && $GLOBALS['TL_CONFIG']['nc_append_camera_settings'])
						{
							$metaCaption = $metaCaption .= ( empty($metaCaption) ? '' : ', ' ) . $row['value'];
						}
					}
				}
				$lngs = deserialize($GLOBALS['TL_CONFIG']['nc_auto_meta_languages']);
				if (is_array($langs) && count($langs) > 0)
				{
					foreach ($langs as $lang)
					{
						$meta[$lang] = array(
							'title' => $metaTitle,
							'link' => '',
							'caption' => $metaCaption
						);
					}
				}
				else
				{
					$meta['en'] = array(
						'title' => $metaTitle,
						'link' => '',
						'caption' => $metaCaption
					);
				}
				$this->Database->prepare("UPDATE tl_files SET meta = ?, exif = ? WHERE path = ?")->execute(serialize($meta), serialize($exif), $file);
			}
		}
	}
}