<?php // debug.class.php ::  Clase Debug, maneja reporte de eventos

if(!defined('INSIDE')){ die("attemp hacking");}
//
//  Experiment code!!!
//
/*vamos a experimentar >:)
  le veo futuro a las classes, ayudaria mucho a tener un codigo mas ordenado...
  que esperabas!!! soy newbie!!! D':<
*/

class debug
{
	var $log,$numqueries;

	function debug()
	{
		$this->vars = $this->log = '';
		$this->numqueries = 0;
	}

	function add($mes)
	{
		$this->log .= $mes;
		$this->numqueries++;
	}

	function echo_log()
	{	global $xnova_root_path;
		echo  "<br><table><tr><td class=k colspan=4><a href=".$xnova_root_path."admin/settings.php>Debug Log</a>:</td></tr>".$this->log."</table>";
		die();
	}
	
	function error($message,$title)
	{
		global $link,$game_config;
		if($game_config['debug']==1){
			echo "<h2>$title</h2><br><font color=red>$message</font><br><hr>";
			echo  "<table>".$this->log."</table>";
		}
		//else{
			//A futuro, se creara una tabla especial, para almacenar
			//los errores que ocurran.
			global $user,$xnova_root_path,$phpEx;
			include_once ($xnova_root_path . 'config.'.$phpEx);
			if(!$link) die('mySQL nicht verf�gbar im Moment ');
			$query = "INSERT INTO {{table}} SET
				`error_sender` = '{$user['id']}' ,
				`error_time` = '".time()."' ,
				`error_type` = '{$title}' ,
				`error_text` = '".mysql_escape_string($message)."' ,
				`error_page` = '".mysql_escape_string($_SERVER['HTTP_REFERER'])."';";
				
			$sqlquery = mysql_query(str_replace("{{table}}", $dbsettings["prefix"].'errors',$query))
				or die('error fatal');
			$query = "explain select * from {{table}}";
			$q = mysql_fetch_array(mysql_query(str_replace("{{table}}", $dbsettings["prefix"].
				'errors', $query))) or die('error fatal: ');
				

			if (!function_exists('message'))
				echo "Fehler, bitte den Fehler dem Administrator melden. Fehler n�: <b>".$q['rows']."</b>";
			else
				message("Fehler, bitte den Fehler dem Administrator melden. Fehler n�: <b>".$q['rows']."</b>", "Erreur");
		//}
		
		die();
	}
	
	
}

// Created by Perberos. All rights reversed (C) 2006
?>
