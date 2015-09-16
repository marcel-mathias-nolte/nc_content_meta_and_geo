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
 * Class NcContentMap
 *
 * Various callbacks for all elements.
 */
class NcContentMap extends \Backend
{
	/**
	 * Extend news articles with geotag
	 */
	public function extendArticle(&$objArticleTemplate, $objArticle, $news)
	{
		$this->news = $news;
		if(!$objArticle['nc_geotagging_hide'] && !empty($objArticle['nc_geotagging_location']))
		{
			$objArticle['nc_geotagging_location'] = deserialize($objArticle['nc_geotagging_location']);
			if (isset($objArticle['nc_geotagging_location']['lat'], $objArticle['nc_geotagging_location']['lon']))
			{
				$objTemplate = new \FrontendTemplate('nc_news_geotag');
				$objTemplate->lat = $objArticle['nc_geotagging_location']['lat'];
				$objTemplate->lon = $objArticle['nc_geotagging_location']['lon'];
				$objTemplate->strAddress = !empty($objArticle['nc_geotagging_location']['address']) ? $objArticle['nc_geotagging_location']['address'] : '';
				$objTemplate->strLabel = $GLOBALS['TL_LANG']['MISC']['NC']['show_on_map'];
				$objTemplate->href = sprintf('system/modules/nc_news_geotag/assets/map.php?lat=%f&lon=%f');
				$objArticleTemplate->geotag = $objTemplate->parse();
			}
		}
	}
	
	/**
	 * Return all templates as array
	 * @param DataContainer
	 * @return array
	 */
	public function getTemplates(\DataContainer $dc)
	{
		$intPid = $dc->activeRecord->pid;
		if ($this->Input->get('act') == 'overrideAll')
		{
			$intPid = $this->Input->get('id');
		}
		return $this->getTemplateGroup('nc_content_map_', $intPid);
	}

	/**
	 * Get the map items
	 * @param array
	 * @return array
	 */
	public function getMapItems($arrSources, $intNewsPage, $arrSize, $blnFullsize)
	{
		// MAPS GRÖSSE EINSTELLBAR!
		$items = array();
		if (in_array('tl_news', $arrSources))
		{
			$newsarchives = array();
			$objNewsArchiveLister = $this->Database->prepare("SELECT id, jumpTo FROM tl_news_archive" . (!FE_USER_LOGGED_IN ? " WHERE requireLogin != '1'" : ""))->execute();
			while ($objNewsArchiveLister->next())
			{
				$newsarchives[$objNewsArchiveLister->id] = \PageModel::findByPk($intNewsPage ? $intNewsPage : $objNewsArchiveLister->jumpTo);
			}
			$objNewsLister = $this->Database->prepare("SELECT * FROM tl_news WHERE (start = '' OR start < ?) AND (stop = '' OR stop > ?) AND published = 1 AND nc_geotagging_hide != 1")->execute(time(), time());
			while ($objNewsLister->next())
			{
				if (isset($newsarchives[$objNewsLister->pid]) && !isset($items['news_' . $objNewsLister->id]) && is_array(deserialize($objNewsLister->nc_geotagging_location)))
				{
					$objTemplate = new \FrontendTemplate('nc_content_mapnews');
					$objTemplate->link = ampersand($this->generateFrontendUrl($newsarchives[$objNewsLister->pid]->row(), ($GLOBALS['TL_CONFIG']['useAutoItem'] ?  '/' : '/items/') . ((!$GLOBALS['TL_CONFIG']['disableAlias'] && $objNewsLister->alias != '') ? $objNewsLister->alias : $objNewsLister->id)));
					$objTemplate->title = deserialize($objNewsLister->headline);
					$objTemplate->location = deserialize($objNewsLister->nc_geotagging_location);
					$objTemplate->date = $objNewsLister->date;
					$objTemplate->class = 'news';
					$items['news_' . $objNewsLister->id] = array(
						'location' => deserialize($objNewsLister->nc_geotagging_location),
						'link' => ampersand($this->generateFrontendUrl($newsarchives[$objNewsLister->pid]->row(), ($GLOBALS['TL_CONFIG']['useAutoItem'] ?  '/' : '/items/') . ((!$GLOBALS['TL_CONFIG']['disableAlias'] && $objNewsLister->alias != '') ? $objNewsLister->alias : $objNewsLister->id))),
						'title' => deserialize($objNewsLister->headline),
						'date' => $objNewsLister->date,
						'text' => $objTemplate->parse()
					);
				}
			}
		}
		if (in_array('tl_files', $arrSources))
		{
			$newsarchives = array();
			$objFilesLister = $this->Database->prepare("SELECT * FROM tl_files WHERE nc_geotagging_hide != 1 AND nc_geotagging_location IS NOT NULL")->execute();
			while ($objFilesLister->next())
			{
				$geo = deserialize($objFilesLister->nc_geotagging_location);
				if (isset($geo['lat'], $geo['lon']) && $geo['lat'] > 0 && $geo['lon'] > 0)
				{
					$meta = deserialize($objFilesLister->meta);
					$objTemplate = new \FrontendTemplate('nc_content_mapimage');
					$arrData = array(
						'size' => deserialize($arrSize),
						'fullsize' => deserialize($blnFullsize),
						'id' => $objFilesLister->id,
						'singleSRC' => $objFilesLister->path,
						'imagemargin' => '',
						'floating' => '',
						'imageUrl' => '',
						'title' => $meta['title'],
						'alt' => $meta['title'],
						'caption' => $meta['caption']
					);
					$this->addImageToTemplate($objTemplate, $arrData);
					$objTemplate->title = $meta['title'];
					$objTemplate->location = $geo;
					$objTemplate->date = $objFilesLister->date;
					$objTemplate->class = 'image';
					$items['image_' . $objFilesLister->id] = array(
						'location' => $geo,
						'link' => $objFilesLister->path,
						'title' => $meta['title'],
						'date' => $objFilesLister->date,
						'text' => $objTemplate->parse()
					);
				}
			}
		}
		uasort($items, function($a, $b) {
			if ($a['date'] == $b['date']) {
				return 0;
			}
			return ($a['date'] < $b['date']) ? -1 : 1;
		});
		return $items;
	}
}