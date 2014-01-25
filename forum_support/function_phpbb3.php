<?php
//########## phpbb3 check begin ######### phpbb3½×¾Â±M¥Î

function phpbb_check($p_ip)
{
	global $db,$cache,$config,$template,$user;
	global $_COOKIE, $HTTP_GET_VARS, $SID,$root_path,$phpEx,$phpbb_root_path;
	$phpbb_root_path=$root_path;
	$phpEx="php";
	// If we are on PHP >= 6.0.0 we do not need some code
	if (version_compare(PHP_VERSION, '6.0.0-dev', '>='))
	{
		/**
		* @ignore
		*/
		define('STRIP', false);
	}
	else
	{
		set_magic_quotes_runtime(0);

		// Be paranoid with passed vars
		if (@ini_get('register_globals') == '1' || strtolower(@ini_get('register_globals')) == 'on' || !function_exists('ini_get'))
		{
			deregister_globals();
		}

		define('STRIP', (get_magic_quotes_gpc()) ? true : false);
	}

	if (defined('IN_CRON'))
	{
		$phpbb_root_path = dirname(__FILE__) . DIRECTORY_SEPARATOR;
	}

	if (!file_exists($phpbb_root_path . 'config.' . $phpEx))
	{
		die("<p>The config.$phpEx file could not be found.</p><p><a href=\"{$phpbb_root_path}install/index.$phpEx\">Click here to install phpBB</a></p>");
	}

	require($phpbb_root_path . 'config.' . $phpEx);

	define('IN_PHPBB', true);
	// Include files
	require($phpbb_root_path . 'includes/acm/acm_' . $acm_type . '.' . $phpEx);
	require($phpbb_root_path . 'includes/cache.' . $phpEx);
	require($phpbb_root_path . 'includes/session.' . $phpEx);
	require($phpbb_root_path . 'includes/auth.' . $phpEx);
	require($phpbb_root_path . 'includes/template.' . $phpEx);
	require($phpbb_root_path . 'includes/functions.' . $phpEx);
	require($phpbb_root_path . 'includes/functions_content.' . $phpEx);

	require($phpbb_root_path . 'includes/constants.' . $phpEx);
	require($phpbb_root_path . 'includes/db/' . $dbms . '.' . $phpEx);
	require($phpbb_root_path . 'includes/utf/utf_tools.' . $phpEx);

	// Set PHP error handler to ours
	set_error_handler(defined('PHPBB_MSG_HANDLER') ? PHPBB_MSG_HANDLER : 'msg_handler');

	// Instantiate some basic classes
	$user		= new user();
	//$auth		= new auth();
	$template	= new template();
	$cache		= new cache();
	$db			= new $sql_db();

	// Connect to DB
	$db->sql_connect($dbhost, $dbuser, $dbpasswd, $dbname, $dbport, false, defined('PHPBB_DB_NEW_LINK') ? PHPBB_DB_NEW_LINK : false);

	// We do not need this any longer, unset for safety purposes
	unset($dbpasswd);

	// Grab global variables, re-cache if necessary
	$config = $cache->obtain_config();
	//$userdata = session_pagestart($user_ip, PAGE_WOG);
	//$db->sql_close();

	// Start session management
	$user->session_begin();
	//$user->setup('viewforum');
	garbage_collection();
	return $user->data['user_id'];
}
function deregister_globals()
{
	$not_unset = array(
		'GLOBALS'	=> true,
		'_GET'		=> true,
		'_POST'		=> true,
		'_COOKIE'	=> true,
		'_REQUEST'	=> true,
		'_SERVER'	=> true,
		'_SESSION'	=> true,
		'_ENV'		=> true,
		'_FILES'	=> true,
		'phpEx'		=> true,
		'phpbb_root_path'	=> true
	);

	// Not only will array_merge and array_keys give a warning if
	// a parameter is not an array, array_merge will actually fail.
	// So we check if _SESSION has been initialised.
	if (!isset($_SESSION) || !is_array($_SESSION))
	{
		$_SESSION = array();
	}

	// Merge all into one extremely huge array; unset this later
	$input = array_merge(
		array_keys($_GET),
		array_keys($_POST),
		array_keys($_COOKIE),
		array_keys($_SERVER),
		array_keys($_SESSION),
		array_keys($_ENV),
		array_keys($_FILES)
	);

	foreach ($input as $varname)
	{
		if (isset($not_unset[$varname]))
		{
			// Hacking attempt. No point in continuing unless it's a COOKIE
			if ($varname !== 'GLOBALS' || isset($_GET['GLOBALS']) || isset($_POST['GLOBALS']) || isset($_SERVER['GLOBALS']) || isset($_SESSION['GLOBALS']) || isset($_ENV['GLOBALS']) || isset($_FILES['GLOBALS']))
			{
				exit;
			}
			else
			{
				$cookie = &$_COOKIE;
				while (isset($cookie['GLOBALS']))
				{
					foreach ($cookie['GLOBALS'] as $registered_var => $value)
					{
						if (!isset($not_unset[$registered_var]))
						{
							unset($GLOBALS[$registered_var]);
						}
					}
					$cookie = &$cookie['GLOBALS'];
				}
			}
		}

		unset($GLOBALS[$varname]);
	}

	unset($input);
}
?>