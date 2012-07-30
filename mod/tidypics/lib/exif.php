<?php
/**
 * Exif Processing Library
 *
 * @package TidypicsExif
 */

/**
 * Pull EXIF data from image file
 * 
 * @param TidypicsImage $image
 */
function td_get_exif($image) {

	// catch for those who don't have exif module loaded
	if (!is_callable('exif_read_data')) {
		return;
	}

	$mime = $image->mimetype;
	if ($mime != 'image/jpeg' && $mime != 'image/pjpeg') {
		return;
	}

	$filename = $image->getFilenameOnFilestore();
	$exif = exif_read_data($filename, 'IFD0,EXIF', true);
	if (is_array($exif)) {
		$data = array_merge($exif['IFD0'], $exif['EXIF']);
		foreach ($data as $key => $value) {
			if (is_string($value)) {
				// there are sometimes unicode characters that cause problems with serialize
				$data[$key] = preg_replace( '/[^[:print:]]/', '', $value);
			}
		}
		$image->tp_exif = serialize($data);
	}
}

/**
 * Grab array of EXIF data for display
 * 
 * @param TidypicsImage $image
 * @return array|false
 */
function tp_exif_formatted($image) {

	$exif = $image->tp_exif;
	if (!$exif) {
		return false;
	}

	$exif = unserialize($exif);

	$model = $exif['Model'];
	if (!$model) {
		$model = "N/A";
	}
	$exif_data['Model'] = $model;

	$exposure = $exif['ExposureTime'];
	if (!$exposure) {
		$exposure = "N/A";
	}
	$exif_data['Shutter'] = $exposure;

	//got the code snippet below from http://www.zenphoto.org/support/topic.php?id=17
	//convert the raw values to understandible values
	$Fnumber = explode("/", $exif['FNumber']);
	if ($Fnumber[1] != 0) {
		$Fnumber = $Fnumber[0] / $Fnumber[1];
	} else {
		$Fnumber = 0;
	}
	if (!$Fnumber) {
		$Fnumber = "N/A";
	} else {
		$Fnumber = "f/$Fnumber";
	}
	$exif_data['Aperture'] = $Fnumber;

	$iso = $exif['ISOSpeedRatings'];
	if (!$iso) {
		$iso = "N/A";
	}
	$exif_data['ISO Speed'] = $iso;

	$Focal = explode("/", $exif['FocalLength']);
	if ($Focal[1] != 0) {
		$Focal = $Focal[0] / $Focal[1];
	} else {
		$Focal = 0;
	}
	if (!$Focal || round($Focal) == "0") {
		$Focal = 0;
	}
	if (round($Focal) == 0) {
		$Focal = "N/A";
	} else {
		$Focal = round($Focal) . "mm";
	}
	$exif_data['Focal Length'] = $Focal;

	$captured = $exif['DateTime'];
	if (!$captured) {
		$captured = "N/A";
	}
	$exif_data['Captured'] = $captured;

	return $exif_data;
}
