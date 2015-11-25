<?php

class AppImage
{

	public static function resize($_from_file, $_to_w = 50, $_to_h = 50)
	{
		if (! $ToImage = ImageCreateTrueColor($_to_w, $_to_h)) {
			return false;
		}
		if (! $sizes = getImageSize($_from_file)) {
			return false;
		}
		list($from_w, $from_h, $from_type, $from_attr) = $sizes;

		//* JPG
		if ($from_type === IMAGETYPE_JPEG) {
			if (! $FromImage = ImageCreateFromJpeg($_from_file)) {
				return false;
			}
			$from_w = ImagesX($FromImage);
			$from_h = ImagesY($FromImage);
			if (! ImageCopyResampled($ToImage, $FromImage, 0, 0, 0, 0, $_to_w, $_to_h, $from_w, $from_h)) {
				return false;
			}
			if (! ImageJpeg($ToImage, $_from_file)) {
				return false;
			}
		//* PNG
		} else if ($from_type === IMAGETYPE_PNG) {
			if (! $FromImage = ImageCreateFromPng($_from_file)) {
				return false;
			}
			$from_w = ImagesX($FromImage);
			$from_h = ImagesY($FromImage);
			if (! ImageCopyResampled($ToImage, $FromImage, 0, 0, 0, 0, $_to_w, $_to_h, $_from_w, $from_h)) {
				return false;
			}
			if (! ImagePng($ToImage, $_from_file)) {
				return false;
			}
		//* GIF
		} else if ($from_type === IMAGETYPE_GIF) {
			if (! $FromImage = ImageCreateFromGif($_from_file)) {
				return false;
			}
			$from_w = ImagesX($FromImage);
			$from_h = ImagesY($FromImage);
			if (! ImageCopyResampled($ToImage, $FromImage, 0, 0, 0, 0, $_to_w, $_to_h, $_from_w, $from_h)) {
				return false;
			}
			if (! ImageGif($ToImage, $_from_file)) {
				return false;
			}
		//* BMP
		} else if ($from_type === IMAGETYPE_BMP || $from_type === IMAGETYPE_WBMP) {
			if (! $FromImage = ImageCreateFromWbmp($_from_file)) {
				return false;
			}
			$from_w = ImagesX($FromImage);
			$from_h = ImagesY($FromImage);
			if (! ImageCopyResampled($ToImage, $FromImage, 0, 0, 0, 0, $_to_w, $_to_h, $_from_w, $from_h)) {
				return false;
			}
			if (! ImageWbmp($ToImage, $_from_file)) {
				return false;
			}
		//* Other
		} else {
			ImageDestroy($ToImage);
			return null;
		}

		ImageDestroy($FromImage);
		ImageDestroy($ToImage);
		return true;
	}

}

