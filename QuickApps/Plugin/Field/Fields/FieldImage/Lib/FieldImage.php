<?php
App::uses('FieldFile', 'FieldFile.Lib');

/**
 * Utility functions for File image handler.
 *
 * Thumbnails files are always stored in `.tn` directory.
 *
 * @author Christopher Castro <chris@quickapps.es>
 * @link http://www.quickappscms.org
 */
class FieldImage {
	public static $previews = array(
		'thumbnail' => array('label' => 'Thumbnail', 'width' => 100, 'height' => 100),
		'medium' => array('label' => 'Medium', 'width' => 220, 'height' => 220),
		'large' => array('label' => 'Large', 'width' => 480, 'height' => 480)
	);

/**
 * This method can do two things:
 *
 * - Returns an array list of available previews suitable for select boxes.
 * - Returns array information for the given preview option.
 *
 * @param string $id Optional preview ID which to get info
 * @return array
 */
	public static function previewsOptions($id = false) {
		if ($id) {
			return  $options[$id];
		}

		$options = array();

		foreach (FieldImage::$previews as $value => $info) {
			$options[$value] = __t($info['label']);
		}

		return $options;
	}

/**
 * Defines a new preview configuration, or overwrite if exists.
 *
 * @param string $id unique ID. e.g.: `new_preview_mode`
 * @param string $label Human-readable name. e.g.: `New preview mode`
 * @param integer $width Width for images that would use this preview mode
 * @param integer $height Height for images that would use this preview mode
 * @return void
 */
	public static function definePreview($id, $label, $width, $height) {
		self::$previews[$id] = array('label' => $label, 'width' => $width, 'height' => $height);
	}

/**
 * Convert bytes to human readable format
 *
 * @param integer $bytes Size in bytes to convert
 * @param integer $precision (default 2)
 * @return string
 */
	public static function bytesToSize($bytes, $precision = 2) {
		return FieldFile::bytesToSize($bytes, $precision);
	}

/**
 * Given a file mime, create a path to a matching icon.
 *
 * @param string $mime file mime type
 * @return A string to the icon as a local path, or FALSE if an appropriate icon could not be found
 */
	public static function fileIcon($mime) {
		return FieldFile::fileIcon($mime);
	}

/**
 * Determine the generic icon MIME package based on a file's MIME type.
 *
 * @param string $mime File mime type
 * @return The generic icon MIME package expected for this file
 */
	public static function fileIconMap($mime) {
		return FieldFile::fileIconMap($mime);
	}

	public static function imageResize($filename, $new_w, $new_h, $quality = 100, $square = false, $x = 0, $y = 0, $force = false) {
		Configure::write('debug', 0);

		$file = basename($filename);
		$ext = self::findExts($file);
		$ThumbFolder = dirname($filename) . DS . '.tn' . DS;

		if (!file_exists($ThumbFolder)) {
			self::rmkdir($ThumbFolder);
		}

		$ThumbCacheName	= preg_replace('/\.' . $ext . '$/', '', $file) . "_{$new_w}_{$new_h}_{$quality}_{$square}_{$x}_{$y}_{$force}.{$ext}";
		$ThumOld = file_exists($ThumbFolder . $ThumbCacheName) ? filectime($ThumbFolder . $ThumbCacheName) : 0;
		$name = $filename;
		$filename = $ThumbFolder . $ThumbCacheName;

		// seven days cache duration
		if ((time()-604800) > $ThumOld) {
			switch(true) {
				case preg_match("/jpg|jpeg|JPG|JPEG/", $ext):
					if (imagetypes() & IMG_JPG) {
						$src_img = imagecreatefromjpeg($name);
						$type = 'jpg';
					} else {
						return;
					}
				break;

				case preg_match("/png/", $ext):
					if (imagetypes() & IMG_PNG) {
						$src_img = imagecreatefrompng($name);
						$type = 'png';
					} else {
						return;
					}
				break;

				case preg_match("/gif|GIF/", $ext):
					if (imagetypes() & IMG_GIF) {
						$src_img = imagecreatefromgif($name);
						$type = 'gif';
					} else {
						return;
					}
				break;
			}

			if (!isset($src_img)) {
				return;
			}

			$old_x = imagesx($src_img);
			$old_y = imagesy($src_img);
			$original_aspect = $old_x/$old_y;
			$new_aspect = $new_w/$new_h;

			if ($square) {
				if ($original_aspect >= $new_aspect) {
					$thumb_w = ($new_h*$old_x)/$old_y;
					$thumb_h = $new_h;
					$pos_x = $thumb_w * ($x/100);
					$pos_y = $thumb_h * ($y/100);
				} else {
					$thumb_w = $new_w;
					$thumb_h = ($new_w*$old_y)/$old_x;
					$pos_x = $thumb_w * ($x/100);
					$pos_y = $thumb_h * ($y/100);
				}

				$crop_y = $pos_y - ($new_h/2);
				$crop_x = $pos_x - ($new_w/2);

				if ($crop_y < 0) {
					$crop_y = 0;
				} else if (($crop_y+$new_h) > $thumb_h) {
					$crop_y = $thumb_h - $new_h;
				}

				if ($crop_x < 0) {
					$crop_x = 0;
				} else if (($crop_x+$new_w) > $thumb_w) {
					$crop_x = $thumb_w - $new_w;
				}
			} else {
				$crop_y = 0;
				$crop_x = 0;

				if ($original_aspect >= $new_aspect) {
					if ($new_w > $old_x && !$force) {
						imagedestroy($src_img);
						copy($name, $filename);
					}

					$thumb_w = $new_w;
					$thumb_h = ($new_w*$old_y)/$old_x;
				} else {
					if ($new_h > $old_y /*&& !$force*/) {
						imagedestroy($src_img);
						copy($name, $filename);
					}

					$thumb_w = ($new_h * $old_x) / $old_y;
					$thumb_h = $new_h;
				}
			}

			$dst_img_one = imagecreatetruecolor($thumb_w,$thumb_h);

			imagecopyresampled($dst_img_one, $src_img, 0, 0, 0, 0, $thumb_w, $thumb_h, $old_x, $old_y);

			if ($square) {
				$dst_img = imagecreatetruecolor($new_w, $new_h);

				imagecopyresampled($dst_img, $dst_img_one, 0, 0, $crop_x, $crop_y, $new_w, $new_h, $new_w, $new_h);
			} else {
				$dst_img = $dst_img_one;
			}

			if ($type == 'png') {
				imagepng($dst_img, $filename);
			} elseif ($type == 'gif') {
				imagegif($dst_img, $filename);
			} else {
				imagejpeg($dst_img, $filename, $quality);
			}

			@imagedestroy($dst_img);
			@imagedestroy($dst_img_one);
			@imagedestroy($src_img);
		}

		$specs = getimagesize($filename);

		header('Content-type: ' . $specs['mime']);
		header('Content-length: ' . filesize($filename));
		header('Cache-Control: public');
		header('Expires: ' . gmdate('D, d M Y H:i:s', strtotime('+1 year')));
		header('Last-Modified: ' . gmdate('D, d M Y H:i:s', filemtime($filename)));
		die(file_get_contents($filename));
	}

/**
 * Get file extension.
 *
 * @param string $filename Name of the file. e.g.: `my-file.docx`
 * @return string
 */
	public static function findExts($file) {
		return FieldFile::findExts($file);
	}

/**
 * Get image size (WIDTH and HEIGHT).
 *
 * @param string $file_path Full path to image file. e.g.: `/var/www/my-image.jpg`
 * @param string $preview Get size based on the given preview mode
 * @return array width and height
 */
	public static function getImageSize($file_path, $preview = false) {
		list($width, $height, $type, $attr) = getimagesize($file_path);

		if ($preview && isset(self::$previews[$preview])) {
			$ratio = $width / $height;
			$dim = self::$previews[$preview];
			$width = $dim['width'];
			$height = $dim['height'];

			if ($ratio < 1) { // height > width
				$width = $ratio * $height;
			} else if ($ratio > 1) { // width > height
				$height = $width / $ratio;
			}
		}

		return array(round($width, 0), round($height, 0));
	}

/**
 * Picture encoder.
 * Generates encoded info for FieldImage::imageResize()
 *
 * @return string base64 encoded arguments
 */
	public static function p() {
		$args = func_get_args();
		$args = join(',', $args);
		$enc = base64_encode($args);

		return $enc;
	}

/**
 * Deletes the given image and all its thumbnails.
 *
 * @param $path string Full path to the given image. e.g.: /var/www/webroot/img/photo.jpg
 * @return boolean
 */
	public static function unlink($path) {
		$previews_dir = dirname($path) . DS . '.tn' . DS;
		$file = basename($path);
		$ext = self::findExts($file);

		foreach (glob("{$previews_dir}" . preg_replace('/\.' . $ext . '$/', '', $file) . "_*.{$ext}") as $tn) {
			@unlink($tn);
		}

		return @unlink($path);
	}

/**
 * Recursive make dir.
 *
 * @param string $pathname Folder path
 * @param mixed $mode (default 0777)
 * @return boolean
 */
	public static function rmkdir($pathname, $mode = 0777) {
		if (is_array($pathname)) {
			foreach ($pathname as $path) {
				self::rmkdir($path, $mode);
			}
		} else {
			is_dir(dirname($pathname)) || self::rmkdir(dirname($pathname), $mode);

			return is_dir($pathname) || @mkdir($pathname, $mode);
		}
	}
}