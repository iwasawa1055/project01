<?php

/**
 * ファイル操作関連ライブラリ
 */
class AppFile
{
	public $ssh_connection;

	/**
	 * ファイルアップロード実行
	 * @param string $_host ストレージサーバーホスト名
	 * @param string $_username ストレージサーバーアクセス用ユーザーネーム
	 * @param string $_id_rsa_public id_rsa.pubのファイルパス
	 * @param string $_id_rsa id_rsaのファイルパス
	 * @param string $_local_image ローカルに一時保存されたファイルのパス(ファイル名まで必要)
	 * @param string $_upload_image ローカルに一時保存されたファイルのパス(ファイル名まで必要)
	 */
	public function upload($_host, $_username, $_id_rsa_public, $_id_rsa, $_local_image, $_upload_image)
	{
		// ssh接続
		$this->_connectStrageServer($_host, $_username, $_id_rsa_public, $_id_rsa);

		// 生成画像が存在するかどうか確認
		if ( !is_file($_local_image) ) {
			new AppInternalCritical(AppE::NOT_FOUND . 'image', 404);
		}

		// 画像送信
		$send_results = ssh2_scp_send(
			$this->ssh_connection,
			$_local_image,
			$_upload_image,
			0664
		);

		if ($send_results === false) {
			// 送信エラー
			new AppInternalCritical(AppE::INTERNAL_SERVER_ERROR . 'strage server send error', 500);
		}

		// ssh接続解除
		$this->_disconnectStrageServer();

		return true;
	}


	/****************** protected 関数

	/**
	 * ストレージサーバーへSSH接続
	 */
	protected function _connectStrageServer($_host, $_username, $_id_rsa_public, $_id_rsa)
	{

		$this->ssh_connection = ssh2_connect($_host, 22);

		if ( empty($this->ssh_connection) ) {
			// コネクションエラー
			new AppInternalCritical(AppE::INTERNAL_SERVER_ERROR . 'strage server connected error', 500);
		}

		$authorize_result = ssh2_auth_pubkey_file(
			$this->ssh_connection, 
			$_username,
			$_id_rsa_public,
			$_id_rsa
		);

		if ($authorize_result === false) {
			// 認証エラー
			new AppInternalCritical(AppE::INTERNAL_SERVER_ERROR . 'strage server ssh auth error', 500);
		}

		return $authorize_result;
	}

	/**
	 * ストレージサーバーの接続解除
	 */
	protected function _disconnectStrageServer()
	{
		return ssh2_exec($this->ssh_connection, 'exit');
	}

	/**
	 * ssh2接続先に指定のdirがあるかどうかチェック
	 */
	protected function _ssh2IsDir($_remote_dir)
	{
		$is_dir = $this->_ssh2ExecResponse('cd '.$_remote_dir.';pwd;');
		if ($is_dir . '/' !== $_remote_dir) {
			return false;
		}
		return true;
	}

	/**
	 * ssh接続先でのlinuxコマンド結果取得
	 */
	protected function _ssh2ExecResponse($_command)
	{
		$stream = ssh2_exec($this->ssh_connection, $_command);

		stream_set_blocking($stream, true);
		return trim(stream_get_contents($stream));
	}

}

