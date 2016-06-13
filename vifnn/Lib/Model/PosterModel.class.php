<?php

class PosterModel
{
	const IMAGE_THUMB_SCALE = 1;
	const IMAGE_THUMB_FILLED = 2;
	const IMAGE_THUMB_CENTER = 3;
	const IMAGE_THUMB_NORTHWEST = 4;
	const IMAGE_THUMB_SOUTHEAST = 5;
	const IMAGE_THUMB_FIXED = 6;

	private $img;
	private $width;
	private $height;
	private $error;
	private $config;
	private $scale;
	private $originalWidth;
	private $originalHeight;

	public function __construct($config = array())
	{
		$this->config = array(
	'target_width'       => 900,
	'default_font_scale' => 0.62,
	'default_size'       => 14,
	'default_color'      => '255,255,255,1',
	'default_font'       => 'yahei',
	'line_spacing'       => 2,
	'word_spacing'       => 2,
	'fonts'              => array('yahei' => './uploads/defaultfonts/yahei.ttf'),
	'vars'               => array(),
	'avatar'             => '',
	'default_avatar'     => TMPL_PATH . 'static/extension/hbimage/placeholder/tx.png',
	'qrcode'             => '',
	'qrcode_url'         => '',
	'logo'               => '',
	'tmp_path'           => './uploads/extension/tmp'
	);
		$this->config = array_merge($this->config, $config);
	}

	public function setConfig($config = array())
	{
		$this->config = array_merge($this->config, $config);
		return $this;
	}

	public function setVars($vars = array())
	{
		$this->config['vars'] = $this->config['vars'] ? $this->config['vars'] : array();
		$this->config['vars'] = array_merge($this->config['vars'], $vars);
		return $this;
	}

	public function create($data)
	{
		$this->originalWidth = $data['width'];
		$this->originalHeight = $data['height'];
		$data['scale'] = $data['scale'] ? $data['scale'] : $this->getTargetScale($data['width']);
		$this->scale = $data['scale'];
		$this->width = $data['width'] = $this->scaleSize($data['width']);
		$this->height = $data['height'] = $this->scaleSize($data['height']);

		if ($data['width'] <= 0) {
			$this->error = '画布宽度不合法';
			return false;
		}

		if ($data['height'] <= 0) {
			$this->error = '画布高度不合法';
			return false;
		}

		$this->img = imagecreatetruecolor($data['width'], $data['height']);

		if (!$this->drawBg($data['bg'])) {
			return false;
		}

		if (!empty($data['element'])) {
			$element = $this->sortByz($data['element']);

			for ($i = 0; $i < count($element); $i++) {
				$type = $element[$i]['type'];
				$display = (isset($element[$i]['display']) ? $element[$i]['display'] : '1');
				if (($display == '0') || empty($display) || ($display == 'none')) {
					continue;
				}

				$result = true;

				switch ($type) {
				case 'textarea':
					$result = $this->drawTextArea($element[$i]);
					break;

				case 'avatar':
					$result = $this->drawAvatar($element[$i]);
					break;

				case 'qrcode':
					$result = $this->drawQrcode($element[$i]);
					break;

				default:
					$result = true;
				}

				if (!$result) {
					return false;
				}
			}
		}

		return true;
	}

	private function drawBg($data)
	{
		$colorArr = $this->getColor($data['color'], '255,255,255,1');
		$color = imagecolorallocatealpha($this->img, $colorArr[0], $colorArr[1], $colorArr[2], $colorArr[3]);
		imagefill($this->img, 0, 0, $color);

		if (!empty($data['image'])) {
			$image = $data['image'];
			$tmpPa = $this->originalWidth . '_' . $this->originalHeight;

			if (strpos($image['src'], $tmpPa) !== false) {
				$image['src'] = str_replace($tmpPa, $this->width . '_' . $this->height, $image['src']);
			}
			else if (strpos($image['src'], 'hbimage/') !== false) {
				$image['src'] = dirname($image['src']) . '/' . $this->config['target_width'] . '/' . basename($image['src']);
			}

			$bgImg = $this->getImg($image['src']);

			if (empty($bgImg)) {
				$this->error = '背景图片不存在';
				return false;
			}

			$bgImg = $this->thumb($bgImg, $this->width, $this->height, self::IMAGE_THUMB_CENTER);
			$alpha = (isset($image['alpha']) ? $image['alpha'] * 100 : 100);
			$this->imagecopymerge_alpha($this->img, $bgImg, 0, 0, 0, 0, imagesx($bgImg), imagesy($bgImg), $alpha);
			imagedestroy($bgImg);
		}

		if (!empty($data['head'])) {
			$head = $data['head'];
			$tmpH = $this->scaleSize(isset($head['height']) ? $head['height'] : 100);
			$tmpC = $this->getColor($head['color'], '255,255,255,1');
			$tmpA = (isset($head['alpha']) ? 127 - ceil($head['alpha'] * 127) : $tmpC[3]);
			$headColor = imagecolorallocatealpha($this->img, $tmpC[0], $tmpC[1], $tmpC[2], $tmpA);
			imagefilledrectangle($this->img, 0, 0, 0 + imagesx($this->img), 0 + $tmpH, $headColor);
		}

		return true;
	}

	private function drawTextArea($data)
	{
		$width = ($data['width'] ? $data['width'] : NULL);

		if (isset($width)) {
			$width = $this->scaleSize($width);
		}

		$x = $this->scaleSize($data['x'] ? $data['x'] : 0);
		$y = $this->scaleSize($data['y'] ? $data['y'] : 0);
		$lineHeight = $this->scaleSize($data['line_height'] ? $data['line_height'] : 0);
		$lineSpacing = $this->scaleSize($data['line_spacing'] ? $data['line_spacing'] : $this->config['line_spacing']);
		$content = ($data['content'] ? $data['content'] : array());
		$parent = array();
		$parent['size'] = $data['size'];
		$parent['angle'] = $data['angle'];
		$parent['font'] = $data['font'];
		$parent['color'] = $data['color'];
		$parent['vertical_align'] = $data['vertical_align'];
		$parent['word_spacing'] = $data['word_spacing'];
		$multiLine = $this->typesetting($content, $parent, $x, $y, $lineHeight, $lineSpacing, $width);

		foreach ($multiLine as $line) {
			$words = $line['words'];

			for ($i = 0; $i < count($words); $i++) {
				$word = $words[$i];

				if (!is_file($word['font'])) {
					$this->error = '字体文件不存在';
					return false;
				}

				$color = imagecolorallocatealpha($this->img, $word['color'][0], $word['color'][1], $word['color'][2], $word['color'][3]);
				imagettftext($this->img, $word['size'], $word['angle'], $word['x'] + $word['left'], $word['y'] + $word['top'], $color, $word['font'], $word['text']);
			}
		}

		return true;
	}

	private function typesetting($content, $parent, $x, $y, $lineHeight = 0, $lineSpacing = 0, $width = NULL)
	{
		$posX = $x;
		$posY = $y;
		$multiLine = array();
		$lineNum = 0;
		$multiLine[] = array(
	'words'       => array(),
	'line_height' => $lineHeight,
	'posY'        => $posY
	);

		for ($m = 0; $m < count($content); $m++) {
			$item = $content[$m];
			$item['size'] = empty($item['size']) ? $parent['size'] : $item['size'];
			$item['angle'] = empty($item['angle']) ? $parent['angle'] : $item['angle'];
			$item['font'] = empty($item['font']) ? $parent['font'] : $item['font'];
			$item['color'] = empty($item['color']) ? $parent['color'] : $item['color'];
			$item['vertical_align'] = empty($item['vertical_align']) ? $parent['vertical_align'] : $item['vertical_align'];
			$item['word_spacing'] = empty($item['word_spacing']) ? $parent['word_spacing'] : $item['word_spacing'];
			$size = $this->scaleFont($item['size'] ? $item['size'] : $this->config['default_size']);
			$angle = ($item['angle'] ? $item['angle'] : 0);
			$font = $this->getFont($item['font']);
			$color = $this->getColor($item['color'], $this->config['default_color']);
			$vertical_align = ($item['vertical_align'] ? $item['vertical_align'] : 'baseline');
			$wordSpacing = $this->scaleSize($item['word_spacing'] ? $item['word_spacing'] : $this->config['word_spacing']);
			$text = $this->replaceVars($item['text']);
			$strArr = $this->splitUnicode($text, 1);

			for ($i = 0; $i < count($strArr); $i++) {
				$str = $strArr[$i];

				if ($str != "\r") {
					$box = $this->calculateTextBox($size, $angle, $font, $str);
					$multiLine[$lineNum]['line_height'] = max($multiLine[$lineNum]['line_height'], $box['height']);
					$word = array('size' => $size, 'angle' => $angle, 'font' => $font, 'color' => $color, 'text' => $str, 'left' => $box['left'], 'top' => $box['top']);
					$word['x'] = $posX;
					$word['height'] = $box['height'];
					$word['width'] = $box['width'];
					$word['vertical_align'] = $vertical_align;
					$multiLine[$lineNum]['words'][] = $word;
					$posX += $box['width'] + $wordSpacing;
				}

				$nextBox = $this->nextBox($size, $angle, $font, $strArr[$i + 1], $content[$m + 1]);
				if ((isset($width) && (($x + $width) < ($posX + (empty($nextBox) ? 0 : $nextBox['width'])))) || ($str == "\r")) {
					$posY += $multiLine[$lineNum]['line_height'] + $lineSpacing;
					$posX = $x;
					$lineNum++;
					$multiLine[] = array(
	'words'       => array(),
	'line_height' => $lineHeight,
	'posY'        => $posY
	);
				}
			}
		}

		for ($i = 0; $i < count($multiLine); $i++) {
			$multiLine[$i] = $this->verticalAlign($multiLine[$i]);
		}

		return $multiLine;
	}

	private function verticalAlign($line)
	{
		$lineHeight = $line['line_height'];
		$posY = $line['posY'];
		$words = array();

		foreach ($line['words'] as $word) {
			if (($word['vertical_align'] == 'baseline') || ($word['vertical_align'] == 'bottom')) {
				$word['y'] = $posY + ($lineHeight - $word['height']);
			}
			else if ($word['vertical_align'] == 'middle') {
				$word['y'] = $posY + (($lineHeight - $word['height']) / 2);
			}
			else if ($word['vertical_align'] == 'top') {
				$word['y'] = $posY;
			}

			$words[] = $word;
		}

		$line['words'] = $words;
		return $line;
	}

	private function nextBox($size, $angle, $font, $next, $nextItem)
	{
		if (isset($next)) {
			return $this->calculateTextBox($size, $angle, $font, $next);
		}

		if (!isset($nextItem)) {
			return NULL;
		}

		$size2 = $this->scaleFont($nextItem['size'] ? $nextItem['size'] : $this->config['default_size']);
		$angle2 = ($nextItem['angle'] ? $nextItem['angle'] : 0);
		$font2 = $this->getFont($nextItem['font']);
		$strArr2 = $this->splitUnicode($this->replaceVars($nextItem['text']), 1);
		return isset($strArr2[0]) ? $this->calculateTextBox($size2, $angle2, $font2, $strArr2[0]) : NULL;
	}

	private function sortByz($element = array())
	{
		$index = array();

		for ($i = 0; $i < count($element); $i++) {
			$index[$element[$i]['z'] . '' . $i] = $i;
		}

		ksort($index);
		$newArr = array();

		foreach ($index as $key => $value) {
			$newArr[] = $element[$value];
		}

		return $newArr;
	}

	private function getTargetScale($width)
	{
		return !empty($this->config['target_width']) ? $this->config['target_width'] / $width : 1;
	}

	private function scaleSize($size)
	{
		return round($this->scale * $size);
	}

	private function scaleFont($size)
	{
		return round($this->scale * $size * $this->config['default_font_scale']);
	}

	private function getImg($path)
	{
		if (empty($path)) {
			return NULL;
		}

		$path = ltrim($path);

		if (gettype($path) == 'resource') {
			return $path;
		}

		if (strpos($path, '/') === 0) {
			$path = '.' . $path;
		}
		else if (strpos($path, C('site_url')) === 0) {
			$path = './' . ltrim(str_replace(C('site_url'), '', $path), './');
		}

		if ((strpos($path, 'http') === 0) || (strpos($path, 'https') === 0)) {
			$tmpPath = rtrim($this->config['tmp_path'], '/') . '/' . sha1($path) . '.tmp';

			if (!file_exists($tmpPath)) {
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $path);
				curl_setopt($ch, CURLOPT_HEADER, 0);
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_TIMEOUT, 60);
				$data = curl_exec($ch);
				curl_close($ch);

				if (empty($data)) {
					return NULL;
				}

				if (!file_exists($this->config['tmp_path'])) {
					mkdir($this->config['tmp_path'], 511, true);
				}

				file_put_contents($tmpPath, $data);
				return imagecreatefromstring($data);
			}
			else {
				$path = $tmpPath;
			}
		}

		if (file_exists($path)) {
			$info = getimagesize($path);

			if (empty($info)) {
				return NULL;
			}

			$fun = 'imagecreatefrom' . image_type_to_extension($info[2], false);
			return call_user_func_array($fun, array($path));
		}

		$strInfo = getimagesizefromstring($path);

		if (!empty($strInfo)) {
			return imagecreatefromstring($path);
		}

		return NULL;
	}

	private function imagecopymerge_alpha($dst_im, $src_im, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h, $pct)
	{
		$cut = imagecreatetruecolor($src_w, $src_h);
		imagecopy($cut, $dst_im, 0, 0, $dst_x, $dst_y, $src_w, $src_h);
		imagecopy($cut, $src_im, 0, 0, $src_x, $src_y, $src_w, $src_h);
		imagecopymerge($dst_im, $cut, $dst_x, $dst_y, 0, 0, $src_w, $src_h, $pct);
		imagedestroy($cut);
	}

	private function getFont($font, $default = NULL)
	{
		$font = ($font ? $font : ($default ? $default : $this->config['default_font']));
		return $this->config['fonts'][$font];
	}

	private function getColor($color, $default = NULL)
	{
		$color = ($color ? $color : ($default ? $default : $this->config['default_color']));

		if (is_string($color)) {
			if (0 === strpos($color, '#')) {
				$colorStr = substr($color, 1);
				if ((strlen($colorStr) == 3) || (strlen($colorStr) == 4)) {
					$tmpArr = str_split($colorStr);
					$colorStr = '';

					for ($i = 0; $i < count($tmpArr); $i++) {
						$colorStr .= $tmpArr[$i] . $tmpArr[$i];
					}
				}

				$color = str_split($colorStr, 2);
				$color = array_map('hexdec', $color);
				$color[3] = isset($color[3]) ? $color[3] : 255;
				$color[3] = 127 - ceil($color[3] * (127 / 255));
			}
			else {
				$color = explode(',', $color);
				$color[3] = isset($color[3]) ? $color[3] : 1;
				$color[3] = 127 - ceil($color[3] * 127);
			}
		}

		$color[3] = isset($color[3]) ? $color[3] : 0;
		return $color;
	}

	private function splitUnicode($str, $l = 0)
	{
		$str = preg_replace('/\\r\\n/', "\r", $str);

		if (0 < $l) {
			$ret = array();
			$len = mb_strlen($str, 'UTF-8');

			for ($i = 0; $i < $len; $i += $l) {
				$ret[] = mb_substr($str, $i, $l, 'UTF-8');
			}

			return $ret;
		}

		return preg_split('//u', $str, -1, PREG_SPLIT_NO_EMPTY);
	}

	private function calculateTextBox($fontSize, $fontAngle, $fontFile, $text)
	{
		$is = preg_match('/[a-zA-Z0-9+\\-*\\/=\\\\?\\(\\)\\{\\}\\[\\]#&%$@!\'"\\s\\.\\,]/', $text);
		$rect = imagettfbbox($fontSize, $fontAngle, $fontFile, $is ? $text : '中');
		$minX = min(array($rect[0], $rect[2], $rect[4], $rect[6]));
		$maxX = max(array($rect[0], $rect[2], $rect[4], $rect[6]));
		$minY = min(array($rect[1], $rect[3], $rect[5], $rect[7]));
		$maxY = max(array($rect[1], $rect[3], $rect[5], $rect[7]));
		$rect2 = imagettfbbox($fontSize, $fontAngle, $fontFile, '中');
		$minY2 = min(array($rect2[1], $rect2[3], $rect2[5], $rect2[7]));
		$maxY2 = max(array($rect2[1], $rect2[3], $rect2[5], $rect2[7]));
		return array('left' => abs($minX) - 1, 'top' => abs($minY2) - 1, 'width' => $maxX - $minX, 'height' => $maxY2 - $minY2, 'box' => $rect);
	}

	private function replaceVars($text)
	{
		$vars = $this->config['vars'];

		foreach ($vars as $key => $value) {
			$text = str_replace('{$' . $key . '}', $value, $text);
		}

		return preg_replace('/\\{\\$\\w+\\}/', '', $text);
	}

	private function drawAvatar($data)
	{
		$width = $this->scaleSize($data['width'] ? $data['width'] : 76);
		$height = $this->scaleSize($data['height'] ? $data['height'] : 76);
		$x = $this->scaleSize($data['x'] ? $data['x'] : 0);
		$y = $this->scaleSize($data['y'] ? $data['y'] : 0);
		$this->config['avatar'] = !empty($this->config['avatar']) ? $this->config['avatar'] : $this->config['default_avatar'];
		$avatarImg = $this->getImg($this->config['avatar']);

		if (empty($avatarImg)) {
			$this->error = '头像不存在';
			return false;
		}

		$avatarImg = $this->thumb($avatarImg, $width, $height, self::IMAGE_THUMB_CENTER);
		$newAvatar = imagecreatetruecolor($width, $height);
		$transColor = imagecolorallocatealpha($newAvatar, 0, 0, 0, 127);
		imagefill($newAvatar, 0, 0, $transColor);
		imageantialias($newAvatar, true);
		imagesettile($newAvatar, $avatarImg);
		imagefilledellipse($newAvatar, $width / 2, $height / 2, $width, $height, IMG_COLOR_TILED);
		$alpha = (isset($data['alpha']) ? $data['alpha'] * 100 : 100);
		$this->imagecopymerge_alpha($this->img, $newAvatar, $x, $y, 0, 0, $width, $height, $alpha);
		imagedestroy($newAvatar);
		imagedestroy($avatarImg);
		return true;
	}

	private function getLtCorner($radius, $color_r = '255', $color_g = '255', $color_b = '255')
	{
		$img = imagecreatetruecolor($radius, $radius);
		$bgcolor = imagecolorallocate($img, $color_r, $color_g, $color_b);
		$fgcolor = imagecolorallocate($img, 0, 0, 0);
		imagefill($img, 0, 0, $bgcolor);
		imagefilledarc($img, $radius, $radius, $radius * 2, $radius * 2, 180, 270, $fgcolor, IMG_ARC_PIE);
		imagecolortransparent($img, $fgcolor);
		return $img;
	}

	private function cornerHandler($img, $radius)
	{
		$lw = imagesx($img);
		$lh = imagesy($img);
		$lt_corner = $this->getLtCorner($radius);
		imagecopymerge($img, $lt_corner, 0, 0, 0, 0, $radius, $radius, 100);
		$lb_corner = imagerotate($lt_corner, 90, 0);
		imagecopymerge($img, $lb_corner, 0, $lh - $radius, 0, 0, $radius, $radius, 100);
		$rb_corner = imagerotate($lt_corner, 180, 0);
		imagecopymerge($img, $rb_corner, $lw - $radius, $lh - $radius, 0, 0, $radius, $radius, 100);
		$rt_corner = imagerotate($lt_corner, 270, 0);
		imagecopymerge($img, $rt_corner, $lw - $radius, 0, 0, 0, $radius, $radius, 100);
	}

	private function thumb($img, $width, $height, $type = self::IMAGE_THUMB_SCALE)
	{
		$w = imagesx($img);
		$h = imagesy($img);
		$x = $y = 0;

		switch ($type) {
		case self::IMAGE_THUMB_SCALE:
			if (($w < $width) && ($h < $height)) {
				return $img;
			}

			$scale = min($width / $w, $height / $h);
			$x = $y = 0;
			$width = $w * $scale;
			$height = $h * $scale;
			break;

		case self::IMAGE_THUMB_CENTER:
			$scale = max($width / $w, $height / $h);
			$w = $width / $scale;
			$h = $height / $scale;
			$x = (imagesx($img) - $w) / 2;
			$y = (imagesy($img) - $h) / 2;
			break;

		case self::IMAGE_THUMB_NORTHWEST:
			$scale = max($width / $w, $height / $h);
			$x = $y = 0;
			$w = $width / $scale;
			$h = $height / $scale;
			break;

		case self::IMAGE_THUMB_SOUTHEAST:
			$scale = max($width / $w, $height / $h);
			$w = $width / $scale;
			$h = $height / $scale;
			$x = imagesx($img) - $w;
			$y = imagesy($img) - $h;
			break;

		case self::IMAGE_THUMB_FILLED:
			if (($w < $width) && ($h < $height)) {
				$scale = 1;
			}
			else {
				$scale = min($width / $w, $height / $h);
			}

			$neww = $w * $scale;
			$newh = $h * $scale;
			$posx = ($width - ($w * $scale)) / 2;
			$posy = ($height - ($h * $scale)) / 2;
			$newimg = imagecreatetruecolor($width, $height);
			$color = imagecolorallocate($newimg, 255, 255, 255);
			imagefill($newimg, 0, 0, $color);
			imagecopyresampled($newimg, $img, $posx, $posy, $x, $y, $neww, $newh, $w, $h);
			imagedestroy($img);
			return $newimg;
		case self::IMAGE_THUMB_FIXED:
			$x = $y = 0;
			break;

		default:
		}

		return $this->crop($img, $w, $h, $x, $y, $width, $height);
	}

	private function crop($img, $w, $h, $x = 0, $y = 0, $width = NULL, $height = NULL)
	{
		empty($width) && ($width = $w);
		empty($height) && ($height = $h);
		$newimg = imagecreatetruecolor($width, $height);
		$color = imagecolorallocate($newimg, 255, 255, 255);
		imagefill($newimg, 0, 0, $color);
		imagecopyresampled($newimg, $img, 0, 0, $x, $y, $width, $height, $w, $h);
		imagedestroy($img);
		return $newimg;
	}

	private function drawQrcode($data)
	{
		$width = $this->scaleSize($data['width'] ? $data['width'] : 122);
		$height = $this->scaleSize($data['height'] ? $data['height'] : 122);
		$x = $this->scaleSize($data['x'] ? $data['x'] : 0);
		$y = $this->scaleSize($data['y'] ? $data['y'] : 0);
		$alpha = (isset($data['alpha']) ? $data['alpha'] * 100 : 100);

		if (!empty($this->config['qrcode_url'])) {
			$qrcodeImg = $this->createQrcode($this->config['qrcode_url'], $width, $this->config['logo']);
		}
		else {
			$qrcodeImg = $this->getImg($this->config['qrcode']);

			if (empty($qrcodeImg)) {
				$this->error = '二维码图片不存在';
				return false;
			}
		}

		$qrcodeImg = $this->thumb($qrcodeImg, $width, $height, self::IMAGE_THUMB_CENTER);
		$this->imagecopymerge_alpha($this->img, $qrcodeImg, $x, $y, 0, 0, $width, $height, $alpha);
		imagedestroy($qrcodeImg);
		return true;
	}

	private function createQrcode($text, $width, $logo = '')
	{
		include APP_PATH . 'Lib/ORG/phpqrcode.php';
		$pxPath = $this->config['tmp_path'] . '/qrcode';

		if (!file_exists($pxPath)) {
			mkdir($pxPath, 511, true);
		}

		$pxPath .= '/' . uniqid() . '.png';
		QRcode::png($text, $pxPath, QR_ECLEVEL_Q, 1, 2);
		$pxInfo = getimagesize($pxPath);
		$pxNum = ceil($width / $pxInfo[0]);
		QRcode::png($text, $pxPath, QR_ECLEVEL_Q, $pxNum, 2);
		$qrcodeImg = imagecreatefrompng($pxPath);

		if (!empty($logo)) {
			$lw = $lh = round($width * 0.14999999999999999);
			$logoImg = $this->getImg($logo);

			if (!empty($logoImg)) {
				$radius = 10;
				$bgW = $lw + 10;
				$bgH = $lh + 10;
				$logoBg = imagecreatetruecolor($bgW, $bgH);
				$logoBgColor = imagecolorallocate($logoBg, 255, 255, 255);
				imagefill($logoBg, 0, 0, $logoBgColor);
				$this->cornerHandler($logoBg, $radius);
				$bgx = $bgy = ($width - $bgW) / 2;
				$this->imagecopymerge_alpha($qrcodeImg, $logoBg, $bgx, $bgy, 0, 0, $bgW, $bgH, 100);
				imagedestroy($logoBg);
				$logoImg = $this->thumb($logoImg, $lw, $lh, self::IMAGE_THUMB_CENTER);
				$this->cornerHandler($logoImg, $radius);
				$dstx = $dsty = ($width - $lw) / 2;
				$this->imagecopymerge_alpha($qrcodeImg, $logoImg, $dstx, $dsty, 0, 0, $lw, $lh, 100);
				imagedestroy($logoImg);
			}
		}

		unlink($pxPath);
		return $qrcodeImg;
	}

	public function getError()
	{
		return $this->error;
	}

	public function save($path)
	{
		if (!file_exists(dirname($path))) {
			mkdir(dirname($path), 511, true);
		}

		imagepng($this->img, $path);
	}

	public function output()
	{
		header('content-type:image/png');
		imagepng($this->img);
	}
}


?>
