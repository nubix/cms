<?php
/**
* Author : Jan Germann
* Datum : 26.04.2010
* Modul : files
* Beschreibung : Funktionen zur Bildverarbeitung
*/


/**
* Erzeugt ein Thumbnail aus einem vorhandenen Bild,
* unter beibehalt des Seitenverhältnisses
*/
function createThumbnail($imageSourceUrl, $imageDestUrl, $maxWidth=50, $maxHeight=50)
{
	/**
	* Neue Bildgrößen festlegen
	*/
	list($oldWidth, $oldHeight) = getimagesize($imageSourceUrl);
	$factorWidth  = $maxWidth / $oldWidth;
	$factorHeight = $maxHeight / $oldHeight;
	$factor = ($factorWidth < $factorHeight)?$factorWidth:$factorHeight;
	$newWidth = intval($oldWidth * $factor);
	$newHeight = intval($oldHeight * $factor);
	/**
	* Bildindentifier erstellen, dieser wird das Thumbnail enthalten
	*/
	$thumb = imagecreatetruecolor($newWidth, $newHeight);
	switch(end(explode(".", $imageSourceUrl)))
	{
		case 'gif':
			$source = imagecreatefromgif($imageSourceUrl);
			$type = 'gif';
			break;
		case 'jpg':
		case 'jpeg':
			$source = imagecreatefromjpeg($imageSourceUrl);
			$type = 'jpeg';
			break;
	}
	$thumbnailData = imagecopyresized($thumb, $source, 0, 0, 0, 0, $newWidth, $newHeight, $oldWidth, $oldHeight);
	
	imagejpeg($thumb, $imageDestUrl, 60);
}