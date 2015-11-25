<?php

class Vendor
{

	/**
	 * (Blue Note 仕様)
	 */
	protected static function _define()
	{
		// Blue Note 仕様
		if (! defined('CRYPT_KEY')) {
			define('CRYPT_KEY', 'a084gDIJa9r321');
		}
	}

	/**
	 * 暗号化(Blue Note 仕様)
	 */
	public static function cry($val)
	{
		self::_define();

		$size = mcrypt_get_block_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);
		$val = self::_pkcs5_pad($val, $size);
		$td = mcrypt_module_open(MCRYPT_BLOWFISH, '',  MCRYPT_MODE_ECB, '');
		$ivsize = mcrypt_enc_get_iv_size($td);
		srand();
		$iv = mcrypt_create_iv($ivsize, MCRYPT_RAND);
		mcrypt_generic_init($td, CRYPT_KEY, $iv);
		$data = mcrypt_generic($td, $val);
		mcrypt_generic_deinit($td);
		mcrypt_module_close($td);

		$data = base64_encode($data);
		
		// / -> _ + -> - に置き換える
		$data = str_replace("/", "_", $data);
		$data = str_replace("+", "-", $data);
		
		return $data;
	}

	/**
	 * 復号(Blue Note 仕様)
	 */
	public static function uncry($val)
	{
		self::_define();

		// / -> _ + -> - に変換したものを戻す
		$val = str_replace("_", "/", $val);
		$val = str_replace("-", "+", $val);
		
		$val = str_replace(" ", "+", $val);
		$val = base64_decode($val);
		
		$size = mcrypt_get_block_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);
		$td = mcrypt_module_open(MCRYPT_BLOWFISH, '',	MCRYPT_MODE_ECB, '');
		$ivsize = mcrypt_enc_get_iv_size($td);
		srand();
		$iv = mcrypt_create_iv($ivsize, MCRYPT_RAND);
		mcrypt_generic_init($td, CRYPT_KEY, $iv);
		$data = mdecrypt_generic($td, $val);
		mcrypt_generic_deinit($td);
		mcrypt_module_close($td);
		$data = self::_pkcs5_unpad($data, $size);
		
		return $data;
	}

	/**
	 * (Blue Note 仕様)
	 */
	protected static function _pkcs5_pad($text, $blocksize)
	{
		$pad = $blocksize - (strlen($text) % $blocksize);
		return $text . str_repeat(chr($pad), $pad);
	}

	/**
	 * (Blue Note 仕様)
	 */
	protected static function _pkcs5_unpad($text)
	{
		$pad = ord($text{strlen($text)-1});
		if ($pad > strlen($text)) return false;
		if (strspn($text, chr($pad), strlen($text) - $pad) != $pad) return false;
		return substr($text, 0, -1 * $pad);
	}

}

