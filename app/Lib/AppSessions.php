<?php

class AppSessions
{

	public static function refer($_return = 'prev_action', $_depth = 1)
	{
		if (isset($_SERVER['HTTP_REFERER'])) {
			CakeSession::write('referer', $_SERVER['HTTP_REFERER']);
		} else {
			CakeSession::write('referer', '');
		}
		$referer = CakeSession::read('referer');

		$prev_action = CakeSession::read('action');
		CakeSession::write('prev_action', $prev_action);

		$traces = debug_backtrace();
		$action = isset($traces[$_depth]['function']) ? $traces[$_depth]['function'] : '';
		$class = isset($traces[$_depth]['class']) ? $traces[$_depth]['class'] : '';
		CakeSession::write('action', $action);
		CakeSession::write('class', $class);

		return $$_return;
	}

	public static function close()
	{
		session_write_close();
	}

	public static function destroy()
	{
		$_SESSION = array();
		$cookies = session_get_cookie_params();
		setcookie(session_name(), '', time() - 42000, $cookies['path'], $cookies['domain']);
		session_destroy();
	}

}

