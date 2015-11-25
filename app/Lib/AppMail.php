<?php

App::uses('AppValid', 'Lib');

class AppMail {

	public static $mobile_domains = array(
		'docomo' => array(
			'docomo.ne.jp',
			'mopera.ne.jp',
			'dwmail.jp',
		),
		'au' => array(
			'ezweb.ne.jp',
			'ido.ne.jp',
			'tkk.ne.jp',
			'tkc.ne.jp',
			'tu-ka.ne.jp',
		),
		'softbank' => array(
			'softbank.ne.jp',
			'softbank.jp',
			'disney.ne.jp',
			'vodafone.ne.jp',
			'jp-d.ne.jp',
			'jp-h.ne.jp',
			'jp-t.ne.jp',
			'jp-c.ne.jp',
			'jp-r.ne.jp',
			'jp-k.ne.jp',
			'jp-n.ne.jp',
			'jp-s.ne.jp',
			'jp-q.ne.jp',
		),
		'emobile' => array(
			'emnet.ne.jp',
			'emobile.ne.jp',
			'emobile-s.ne.jp',
		),
		'willcom' => array(
			'willcom.com',
			'wcm.ne.jp',
			'pdx.ne.jp',
		)
	);

	/**
	 * コンストラクタ
	 *
	 * @access	public
	 * @param	void
	 * @return	void
	 * @todo	devel
	 */
	public function __construct()
	{}

	/**
	 * メール送信
	 *
	 * @access	public static
	 * @param	array $_envs エンベローブ
	 * @param	array $_headers ヘッダ
	 * @param	string $_body 本文
	 * @param	string $_file 添付ファイル
	 * @param	string $_msg エラーメッセージ
	 * @return	bool
	 * @todo	devel
	 */
    public static function send(array $_envs, array $_headers, $_body = null, &$_file = null, &$_msg = null)
    {
		mb_language('ja');
		mb_internal_encoding('UTF-8');

		$res = '';
		try {
			if (1 > func_num_args()) {
				throw new Exception("MUA: The passed arguments count is invalid.", 1000);
			}

			//* Envelope
			$k = null;
			$k = "HOST";
			if (empty($_envs[$k])) {
				throw new Exception("MUA: The passed $k is empty.", 1010);
			}
			if (! AppValid::isHost($_envs[$k])) {
				throw new Exception("MUA: The passed $k is invalid.", 1011);
			}

			$helo = null;
			$helo = "HELO " . $_envs[$k] . "\r\n";

			$k = "PORT";
			if (empty($_envs[$k])) {
				throw new Exception("MUA: The passed $k is empty.", 1020);
			}
			if (! AppValid::isPort($_envs[$k])) {
				throw new Exception("MUA: The passed $k is invalid.", 1021);
			}

			$k = "MAIL FROM";
			if (empty($_envs[$k])) {
				throw new Exception("MUA: The passed $k is empty.", 1030);
			}
			if (! AppValid::isMailAddress($_envs[$k])) {
				throw new Exception("MUA: The passed $k is invalid.", 1031);
			}
			$mail_from = null;
			$mail_from = $k . ": " . $_envs[$k] . "\r\n";
			
			$k = "RCPT TO";
			if (empty($_envs[$k])) {
				throw new Exception("MUA: The passed $k is empty.", 1040);
			}
			foreach ($_envs[$k] as $v) {
				if (! AppValid::isMailAddress($v)) {
					throw new Exception("MUA: The passed $k is invalid.", 1041);
				}
				$rcpt_tos[] = $k . ": " . $v . "\r\n";
			}

			//* Header
			$headers = array();
			if (! empty($_headers)) {
				foreach ($_headers as $k => $v) {
					switch ($k) {
						case "From":
						case "To":
						case "Cc":
						case "Bcc":
						case "Reply-To":
						case "Return-Recipt-To":
						case "Disposition-Notification-To":
						case "Return-Path":
						case "Errors-To":
							if (empty($v)) {
								continue;
							}
							if (! is_array($v)) {
								$v = array($v);
							}
							foreach ($v as $vv) {
								if (preg_match('/</', $vv)) {
									$vv = explode('<', $vv);
									$vv = str_replace('>', '', $vv[1]);
								}
								if (! AppValid::isMailAddress($vv)) {
									if ($k === 'From') {
										$code = 2000;
									} else if ($k === 'To') {
										$code = 2010;
									} else if ($k === 'Cc') {
										$code = 2020;
									} else if ($k === 'Bcc') {
										$code = 2030;
									} else if ($k === 'Reply-To') {
										$code = 2040;
									} else if ($k === 'Return-Recipt-To') {
										$code = 2050;
									} else if ($k === 'Disposition-Notification-To') {
										$code = 2060;
									} else if ($k === 'Return-Path') {
										$code = 2070;
									} else if ($k === 'Errors-To') {
										$code = 2080;
									}
									throw new Exception("MUA: The passed $k is invalid.", $code);
								}
							}

							$v = implode(", ", $v);
							if (preg_match('/</', $v)) {
								$vals = explode('<', $v);
								$headers[$k] = $k . ": " . mb_encode_mimeheader($vals[0], 'UTF-8') . '<' . $vals[1] . "\r\n";
							} else {
								$headers[$k] = $k . ": " . $v . "\r\n";
							}
						break;
						case "Date":
							if (empty($v)) {
								continue;
							}
							//あとでやる
							//if () {
							//	throw new Exception("MUA: The passed $k is invalid.", 2090);
							//}
							$headers[$k] = $k . ": " . $v . "\r\n";
						break;
						case "Message-ID":
							if (empty($v)) {
								continue;
							}
							//あとでやる
							//if (preg_match('/[\d]/', $v)) {
							//	throw new Exception("MUA: The passed $k is invalid.", 2100);
							//}
							$headers[$k] = $k . ': <' . $v . '>' . "\r\n";
						break;
						case "Subject":
							if (empty($v)) {
								continue;
							}
							//あとでやる: 78 文字改行
							//if ()) {
							//	throw new Exception("MUA: The passed $k is invalid.", 2110);
							//}
							$headers[$k] = $k . ": " . mb_encode_mimeheader($v, 'UTF-8') . "\r\n";
						break;
						default:
							throw new Exception("MUA: The passed header has invalid key ($k).", 2990);
						break;
					}
				}
			}
			//** Header Content
			$headers['MIME-Version'] = 'MIME-Version: 1.0' . "\r\n";
			$headers['Content-Type'] = 'Content-Type: text/plain; charset=utf-8' . "\r\n";
			$headers['Content-Transfer'] = 'Content-Transfer-Encoding: base64' . "\r\n";

			if ($_file !== null) {
				//*** Boundary
				$boundary = uniqid(rand(), true);
				$headers['Content-Type'] = 'Content-Type: multipart/mixed; boundary="' . $boundary . '"' . "\r\n";
			}

			//* Body
			if ($_body !== null)  {
				if (! is_string($_body)) {
					throw new Exception("MUA: The passed 3rd argument is not string type.", 3000);
				}
				$body = '';
				if ($_file !== null) {
					$body .= '--' . $boundary . "\r\n"; 
					$body .= 'Content-Type: text/plain; charset=utf-8' . "\r\n";
					$body .= 'Content-Transfer-Encoding: base64' . "\r\n";
					$body .= "\r\n";
				}
				$body .= base64_encode($_body);
				$body .= "\r\n";
			}

			//* File
			if ($_file !== null) {
				$files = array();
				if (! is_array($_file)) {
					$files[] = $_file;
				} else {
					$files = $_file;
				}

				foreach ($files as $file) {
					if (! is_file($file)) {
						throw new Exception("MUA: The passed 4th argument is not file.", 3010);
					}
					if (! is_readable($file)) {
						throw new Exception("MUA: The passed 4th argument is not readable file.", 3011);
					}
					$file_name = mb_encode_mimeheader(mb_convert_encoding(basename($file), 'UTF-8'));

					$body .= '--' . $boundary . "\r\n";
					$body .= 'Content-Type: application/octet-stream; name="' . $file_name . '"' . "\r\n";
					$body .= 'Content-Transfer-Encoding: base64' . "\r\n";
					$body .= 'Content-Disposition: attachment; filename="' . $file_name . '"' . "\r\n";
					$body .= "\r\n";
					$body .= chunk_split(base64_encode(file_get_contents($file)), 76, "\r\n");
					$body .= "\r\n";
				}
				$body .= '--' . $boundary . '--' . "\r\n";
			}

			//* SMTP
            $sock = null;
            if (! $sock = fsockopen($_envs["HOST"], $_envs["PORT"])) {
                throw new Exception("SMTP: The client socket open is failed.", 4000);
            }

            $res = fgets($sock);
			if (0 !== strncmp($res, "220", 3)) {
				throw new Exception("SMTP: The server socket connection is failed. ($res)", 4010);
            }

            fputs($sock, $helo);
            $res = fgets($sock);
            if (0 !== strncmp($res, "250", 3)) {
                throw new Exception("SMTP: The HELO command is failed. ($res)", 4020);
            }

            if (! empty($_envs["USER"]) && ! empty($_envs["PASS"])) {
                fputs($sock, "AUTH LOGIN" . "\r\n");
                $res = fgets($sock);
                if (0 !== strncmp($res, "334", 3)) {
                    throw new Exception("SMTP: The AUTH LOGIN command is failed. ($res)", 4030);
				}

                fputs($sock, base64_encode($_envs["USER"]) . "\r\n");
                $res = fgets($sock);
                if (0 !== strncmp($res, "334", 3)) {
                    throw new Exception("SMTP: The USER command is failed. ($res)", 4031);
				}

                fputs($sock, base64_encode($_envs["PASS"]) . "\r\n");
                $res = fgets($sock);
                if (0 !== strncmp($res, "235", 3)) {
                    throw new Exception("SMTP: The PASS command is failed. ($res)", 4032);
                }
			}

            fputs($sock, $mail_from);
            $res = fgets($sock);
            if (0 !== strncmp($res, "250", 3)) {
                throw new Exception("SMTP: The MAIL FROM command is failed. ($res)", 4040);
            }

            foreach ($rcpt_tos as $v) {
                fputs($sock, $v);
                $res = fgets($sock);
                if (0 !== strncmp($res, "250", 3)) {
                    throw new Exception("SMTP: The RCPT TO command is failed. ($res)", 4050);
				}
			}

            fputs($sock, "DATA\r\n");
            $res = fgets($sock);
            if (0 !== strncmp($res, "354", 3)) {
                throw new Exception("SMTP: The DATA command is failed. ($res)", 4060);
			}

            foreach ($headers as $v) {
                fputs($sock, $v);
			}

            if (isset($body)) {
                fputs($sock, $body);
			}

            fputs($sock, ".\r\n");
            $res = fgets($sock);
            if (0 !== strncmp($res, "250", 3)) {
                throw new Exception("SMTP: The DATA period is failed. ($res)", 4070);
            }
        } catch (Exception $e) {
		    $step_code = $e->getCode();
			$step_msg = $e->getMessage();

			//* Socket Close
            if ($step_code > 4000) {
                fputs($sock, "QUIT\r\n");
                fclose($sock);
			}

			//* Message Elements
			$json_env = json_encode($_envs);
			$json_header = json_encode($_headers);
			$smtp_code = substr($res, 0, 3);

			$mail_from = str_replace(array("\r", "\n"), ' ', $mail_from);
			$rcpt_to = str_replace(array("\r", "\n"), ' ', implode('; ', $rcpt_tos));

			//* Mail Log
			self::_log($_envs, $_headers, $_body, $_file, $step_msg);

			//* Error Message
			$exception_message = 'Message:<<' . $step_msg . '>>; Envelope:<<' . $json_env . '>>; Header:<<' . $json_header . '>>; SMTP Code:<<' . $smtp_code . '>>;', $step_code);

			//* Exception Trigger
			if ($step_code > 4000) {
				new AppInternalCritical($exception_message, 500);
			} else {
				new AppMedialCritical($exception_message, 500);
			}
        }
		//* Socket Close
        fputs($sock, "QUIT\r\n");
		fclose($sock);

		//* Log
		$mail_from = str_replace(array("\r", "\n"), ' ', $mail_from);
		$rcpt_to = str_replace(array("\r", "\n"), ' ', implode('; ', $rcpt_tos));
		self::_log($_envs, $_headers, $_body, $_file, 'SMTP Succeeded');

		//* Return
        return true;
    }

	/**
	 * _log()
	 * メールログ
	 *
	 * @access	public static
	 * @param	string $_msg ログメッセージ
	 * @return	string ログフォーマット
	 * @todo	devel
	 */
	public static function _log($_envs, $_headers, $_body, $_file, $_message)
	{
		// Format
		$log = "\n";
		$log .= '[Log ID]' . "\n" . (uniqid('app_response_', true)) . "\n";
		$log .= '[Access ID]' . "\n" . (! empty($_SERVER['UNIQUE_ID']) ? $_SERVER['UNIQUE_ID'] : uniqid('@', true)) . "\n";
		$log .= '[Env Var]' . "\n" . (isset($_SERVER) ? var_export($_SERVER, true) : '-') . "\n";
		$log .= '[Mail Envs]' . "\n" . ($_envs ? var_export($envs, true) : '-') . "\n";
		$log .= '[Mail Header]' . "\n" . ($_headers ? var_export($_headers, true) : '-') . "\n";
		$log .= '[Mail Body]' . "\n" . ($_body ? $body : '-') . "\n";
		$log .= '[Mail File]' . "\n" . ($_file ? var_export($file, true) : '-') . "\n";
		$log .= '[Mail Message]' . "\n" . ($_message ? $message : '-') . "\n";
		$log .= "\n";

		CakeLog::write(APP_MAIL_LOG, $log);

		return $log;
	}

}

