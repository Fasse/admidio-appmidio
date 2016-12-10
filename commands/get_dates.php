<?php
/******************************************************************************
 * Appmidio Command get_dates.php
 *
 * Version 1.4.0
 *
 * Funktion fuer das Admidio-Plugin Appmidio, um die Termine auszulesen
 *
 * Copyright    : (c) 2013-2015 The Zettem Team
 * Homepage     : https://play.google.com/store/apps/details?id=de.zettem.Appmidio
 * License      : GNU Public License 2 http://www.gnu.org/licenses/gpl-2.0.html
 *
*****************************************************************************/

require_once(PLUGIN_PATH. '/../adm_program/system/common.php');
require_once(PLUGIN_PATH. '/'.$plugin_folder.'/functions/common.php');


function sql_command()
{
	global $gValidLogin, $gPreferences, $g_tbl_praefix, $gCurrentUser;

	if($gValidLogin == false)
	{
		msg_unauthorized();
	} 
	else if($gPreferences['enable_dates_module'] == 0)
	{
		// das Modul ist deaktiviert
		// $gMessage->show($gL10n->get('SYS_MODULE_DISABLED'));
		msg_not_found('Das Modul Termine ist deaktiviert.');
	}
	else 
	{
		$sql = "SELECT ";
		$sql = $sql."	dat_id ";
		$sql = $sql."	, cat_name ";
		$sql = $sql."	, dat_headline ";
		$sql = $sql."	, IFNULL(dat_description, '') AS dat_description ";
		$sql = $sql."	, dat_begin ";
		$sql = $sql."	, dat_end ";
		$sql = $sql."	, dat_all_day ";
		$sql = $sql."	, IFNULL(dat_location, '') AS dat_location ";
		$sql = $sql."	, IFNULL(dat_country, '') AS dat_country ";
		$sql = $sql."	, IFNULL(room_name, '') AS room_name ";
		$sql = $sql."	, dat_timestamp_create ";
		$sql = $sql."	, dat_usr_id_create "; 
		$sql = $sql."	, IFNULL(f1.usd_value, '') AS cre_first_name ";
		$sql = $sql."	, IFNULL(f2.usd_value, '') AS cre_last_name ";
		$sql = $sql."	, dat_timestamp_change ";
		$sql = $sql."	, dat_usr_id_change "; 
		$sql = $sql."	, IFNULL(f3.usd_value, '') AS upd_first_name ";
		$sql = $sql."	, IFNULL(f4.usd_value, '') AS upd_last_name ";
		$sql = $sql."FROM ";
		$sql = $sql."	".$g_tbl_praefix."_dates ";
		$sql = $sql."	JOIN ".$g_tbl_praefix."_categories ON cat_id = dat_cat_id ";
		$sql = $sql."	LEFT JOIN ".$g_tbl_praefix."_rooms ON room_id = dat_room_id ";
		$sql = $sql."	LEFT JOIN ".$g_tbl_praefix."_user_data AS f1 ON f1.usd_usr_id = dat_usr_id_create AND f1.usd_usf_id = (SELECT usf_id FROM ".$g_tbl_praefix."_user_fields WHERE usf_name_intern = 'FIRST_NAME') ";
		$sql = $sql."	LEFT JOIN ".$g_tbl_praefix."_user_data AS f2 ON f2.usd_usr_id = dat_usr_id_create AND f2.usd_usf_id = (SELECT usf_id FROM ".$g_tbl_praefix."_user_fields WHERE usf_name_intern = 'LAST_NAME') ";
		$sql = $sql."	LEFT JOIN ".$g_tbl_praefix."_user_data AS f3 ON f3.usd_usr_id = dat_usr_id_change AND f3.usd_usf_id = (SELECT usf_id FROM ".$g_tbl_praefix."_user_fields WHERE usf_name_intern = 'FIRST_NAME') ";
		$sql = $sql."	LEFT JOIN ".$g_tbl_praefix."_user_data AS f4 ON f4.usd_usr_id = dat_usr_id_change AND f4.usd_usf_id = (SELECT usf_id FROM ".$g_tbl_praefix."_user_fields WHERE usf_name_intern = 'LAST_NAME') ";
		$sql = $sql."WHERE ";
		$sql = $sql."	(dat_begin >= '".DATE_NOW." 00:00:00' OR dat_end > '".DATE_NOW." 00:00:00') ";
		$sql = $sql."	AND (dat_global = 1 ";
		$sql = $sql."   OR dat_id IN (SELECT dtr_dat_id FROM ".$g_tbl_praefix."_date_role LEFT JOIN ".$g_tbl_praefix."_members ON mem_rol_id = dtr_rol_id WHERE dtr_rol_id IS NULL OR mem_usr_id = ".$gCurrentUser->getValue('usr_id').")) ";
		$sql = $sql."ORDER BY ";
		$sql = $sql."	dat_begin ";

		return $sql;
	}
}

?>
