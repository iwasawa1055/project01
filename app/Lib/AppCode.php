<?php

/**
 * AppCode
 */
class AppCode {

	// OEM の IV はあえてここで設定
	private static $iv = '4rgaP3pKm+hP9jie5o2z4J+SNVzrQE90xgo6tX49KNYnkZlkSiPt0a+jfZ3JRg5P518eP4wdBbnOIFLin9piJQ==';
	private static $key = 'kt87R7CiVBoU0pPZxWMTVPb4HGUd1IVRUFu9rink5NBGFUum1aDPqm/V8FdQJdgzoNZ3fsGNvruzuAf0l+QpiA==';

	//OEM 以外 API imageItemで使用
	private static $enc_key = 'a6c7902010daf166885d66b5e37eed08feb0325dd455b1d21d4263dca6e67f1b';


	/**
	 * コンストラクタ
	 *
	 * @access	public
	 * @param	void
	 * @return	void
	 * @since	v2.0.0
	 * @author	osada<osada@terrada.co.jp> 
	 * @todo	devel
	 */
	public function __construct()
	{
	}

	public static function getMktime()
	{
		return mktime(date('H'), 0, 0, date('m'), date('d'), date('Y'));
	}

	public static function reGetMktime()
	{
		return mktime(date('H')+1, 0, 0, date('m'), date('d'), date('Y'));
	}


	/**
	 * 平文可逆暗号化
	 *
	 * @access	public static
	 * @param	string $_plain 平文
	 * @return	string 暗号文
	 * @since	v2.0.0
	 * @author	osada<osada@terrada.co.jp>
	 * @todo	devel
	 */
	public static function encodeLoginData($_plain)
	{
		$td = mcrypt_module_open(MCRYPT_BLOWFISH, '', MCRYPT_MODE_CBC, '');
		$iv_size = mcrypt_enc_get_iv_size($td);
		$iv = substr(hash('sha512', self::$iv), 0, $iv_size);
		$key_size = mcrypt_enc_get_key_size($td);
		$key = substr(hash('sha512', self::$key), 0, $key_size);
		mcrypt_generic_init($td, $key, $iv);
		$encode = mcrypt_generic($td, $_plain);
		$base64 = base64_encode($encode);
		$for_url = str_replace(array('+', '/', '='), array('.', '_', '-'), $base64);
		mcrypt_generic_deinit($td);
		mcrypt_module_close($td);
		return $for_url;
	}

	/**
	 * 暗号文復号
	 *
	 * @access	public static
	 * @param	string $_encode 暗号文
	 * @return	string 平文
	 * @since	v2.0.0
	 * @author	osada<osada@terrada.co.jp>
	 * @todo	devel
	 */
	public static function decodeLoginData($_encode)
	{
		$td = mcrypt_module_open(MCRYPT_BLOWFISH, '', MCRYPT_MODE_CBC, '');
		$iv_size = mcrypt_enc_get_iv_size($td);
		$iv = substr(hash('sha512', self::$iv), 0, $iv_size);
		$key_size = mcrypt_enc_get_key_size($td);
		$key = substr(hash('sha512', self::$key), 0, $key_size);
		mcrypt_generic_init($td, $key, $iv);
		$base64 = str_replace(array('.', '_', '-'), array('+', '/', '='), $_encode);
		$bin = base64_decode($base64);
		$decode = mdecrypt_generic($td, $bin);
		mcrypt_generic_deinit($td);
		mcrypt_module_close($td);
		return trim($decode);
	}

	public static function toUrlFromBase64()
	{
		
	}

	/**
	 * 平文可逆暗号化 API imageItemで使用
	 *
	 * @access	public static
	 * @param	string $_plain 平文
	 * @return	string 暗号文
	 * @since	v2.0.0
	 * @author	masagoto
	 * @todo	devel
	 */
	public static function encodeKey($_plain)
	{

		// $iv = self::getMktime();
		// $key = self::$enc_key;

		$td = mcrypt_module_open(MCRYPT_BLOWFISH, '', MCRYPT_MODE_CBC, '');
		$iv_size = mcrypt_enc_get_iv_size($td);
		// $iv = substr(hash('sha512', self::$iv), 0, $iv_size);
		$iv = substr(hash('sha512', self::getMktime()), 0, $iv_size);
		$key_size = mcrypt_enc_get_key_size($td);
		// $key = substr(hash('sha512', self::$key), 0, $key_size);
		$key = substr(hash('sha512', self::$enc_key), 0, $key_size);
		mcrypt_generic_init($td, $key, $iv);
		$encode = mcrypt_generic($td, $_plain);
		$base64 = base64_encode($encode);
		$for_url = str_replace(array('+', '/', '='), array('.', '_', '-'), $base64);
		mcrypt_generic_deinit($td);
		mcrypt_module_close($td);
		return $for_url;
	}

	/**
	 * 平文可逆暗号化 API imageItemで使用
	 *
	 * @access	public static
	 * @param	string $_plain 平文
	 * @return	string 暗号文
	 * @since	v2.0.0
	 * @author	masagoto
	 * @todo	devel
	 */
	public static function encodeKeyReversible($_plain, $_iv, $_key)
	{
		$td = mcrypt_module_open(MCRYPT_BLOWFISH, '', MCRYPT_MODE_CBC, '');
		$iv_size = mcrypt_enc_get_iv_size($td);
		$iv = substr(hash('sha512', $_iv), 0, $iv_size);
		$key_size = mcrypt_enc_get_key_size($td);
		$key = substr(hash('sha512', $_key), 0, $key_size);
		mcrypt_generic_init($td, $key, $iv);
		$encode = mcrypt_generic($td, $_plain);
		$base64 = base64_encode($encode);
		$for_url = str_replace(array('+', '/', '='), array('.', '_', '-'), $base64);
		mcrypt_generic_deinit($td);
		mcrypt_module_close($td);
		return $for_url;
	}

	/**
	 * 暗号文復号　API imageItemで使用
	 *
	 * @access	public static
	 * @param	string $_encode 暗号文
	 * @return	string 平文
	 * @since	v2.0.0
	 * @author	masagoto
	 * @todo	devel
	 */
	public static function decodeKey($_encode)
	{
		$td = mcrypt_module_open(MCRYPT_BLOWFISH, '', MCRYPT_MODE_CBC, '');
		$iv_size = mcrypt_enc_get_iv_size($td);
		// $iv = substr(hash('sha512', self::$iv), 0, $iv_size);
		$iv = substr(hash('sha512', self::getMktime()), 0, $iv_size);
		$key_size = mcrypt_enc_get_key_size($td);
		// $key = substr(hash('sha512', self::$key), 0, $key_size);
		$key = substr(hash('sha512', self::$enc_key), 0, $key_size);
		mcrypt_generic_init($td, $key, $iv);
		$base64 = str_replace(array('.', '_', '-'), array('+', '/', '='), $_encode);
		$bin = base64_decode($base64);
		$decode = mdecrypt_generic($td, $bin);
		mcrypt_generic_deinit($td);
		mcrypt_module_close($td);
		return trim($decode);
	}


	/**
	 * 平文可逆暗号化 API imageItemで使用 retry用
	 *
	 * @access	public static
	 * @param	string $_plain 平文
	 * @return	string 暗号文
	 * @since	v2.0.0
	 * @author	masagoto
	 * @todo	devel
	 */
	public static function reEncodeKey($_plain)
	{
		// $iv = self::reGetMktime();
		// $key = self::$enc_key;

		$td = mcrypt_module_open(MCRYPT_BLOWFISH, '', MCRYPT_MODE_CBC, '');
		$iv_size = mcrypt_enc_get_iv_size($td);
		// $iv = substr(hash('sha512', self::$iv), 0, $iv_size);
		$iv = substr(hash('sha512', self::reGetMktime()), 0, $iv_size);
		$key_size = mcrypt_enc_get_key_size($td);
		// $key = substr(hash('sha512', self::$key), 0, $key_size);
		$key = substr(hash('sha512', self::$enc_key), 0, $key_size);
		mcrypt_generic_init($td, $key, $iv);
		$encode = mcrypt_generic($td, $_plain);
		$base64 = base64_encode($encode);
		$for_url = str_replace(array('+', '/', '='), array('.', '_', '-'), $base64);
		mcrypt_generic_deinit($td);
		mcrypt_module_close($td);
		return $for_url;
	}

	/**
	 * 暗号文復号　API imageItemで使用 retry用
	 *
	 * @access	public static
	 * @param	string $_encode 暗号文
	 * @return	string 平文
	 * @since	v2.0.0
	 * @author	masagoto
	 * @todo	devel
	 */
	public static function reDecodeKey($_encode)
	{
		$td = mcrypt_module_open(MCRYPT_BLOWFISH, '', MCRYPT_MODE_CBC, '');
		$iv_size = mcrypt_enc_get_iv_size($td);
		// $iv = substr(hash('sha512', self::$iv), 0, $iv_size);
		$iv = substr(hash('sha512', self::reGetMktime()), 0, $iv_size);
		$key_size = mcrypt_enc_get_key_size($td);
		// $key = substr(hash('sha512', self::$key), 0, $key_size);
		$key = substr(hash('sha512', self::$enc_key), 0, $key_size);
		mcrypt_generic_init($td, $key, $iv);
		$base64 = str_replace(array('.', '_', '-'), array('+', '/', '='), $_encode);
		$bin = base64_decode($base64);
		$decode = mdecrypt_generic($td, $bin);
		mcrypt_generic_deinit($td);
		mcrypt_module_close($td);
		return trim($decode);
	}

}

