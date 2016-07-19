<?

 /**
  * подключение к базе данных
  * @package ALL
  */

    include($inc_path.'/config.php');

    include_once('db/class.db.mysql.php');

    $GLOBALS['db'] = new Db($db_host,$db_user,$db_password,$db_name);

//    $db->query('set names cp1251');
        include($moduls_root.'/setup/func.initVars.php');
        initVars();

?>
