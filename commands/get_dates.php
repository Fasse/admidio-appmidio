<?php
/******************************************************************************
 * Appmidio Command get_dates.php
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
		$sql = 'SELECT dat_id 
                     , cat_name 
                     , dat_headline 
                     , IFNULL(dat_description, '') AS dat_description 
                     , dat_begin 
                     , dat_end 
                     , dat_all_day 
                     , IFNULL(dat_location, '') AS dat_location 
                     , IFNULL(dat_country, '') AS dat_country 
                     , IFNULL(room_name, '') AS room_name 
                     , dat_timestamp_create 
                     , dat_usr_id_create 
                     , IFNULL(f1.usd_value, '') AS cre_first_name 
                     , IFNULL(f2.usd_value, '') AS cre_last_name 
                     , dat_timestamp_change 
                     , dat_usr_id_change 
                     , IFNULL(f3.usd_value, '') AS upd_first_name 
                     , IFNULL(f4.usd_value, '') AS upd_last_name 
                  FROM '.TBL_DATES.' 
                  JOIN '.TBL_CATEGORIES.' ON cat_id = dat_cat_id
                  LEFT JOIN '.TBL_ROOMS.' ON room_id = dat_room_id 
                  LEFT JOIN '.TBL_USER_DATA.' AS f1 ON f1.usd_usr_id = dat_usr_id_create AND f1.usd_usf_id = (SELECT usf_id FROM ".$g_tbl_praefix."_user_fields WHERE usf_name_intern = 'FIRST_NAME') 
                  LEFT JOIN '.TBL_USER_DATA.' AS f2 ON f2.usd_usr_id = dat_usr_id_create AND f2.usd_usf_id = (SELECT usf_id FROM ".$g_tbl_praefix."_user_fields WHERE usf_name_intern = 'LAST_NAME') 
                  LEFT JOIN '.TBL_USER_DATA.' AS f3 ON f3.usd_usr_id = dat_usr_id_change AND f3.usd_usf_id = (SELECT usf_id FROM ".$g_tbl_praefix."_user_fields WHERE usf_name_intern = 'FIRST_NAME') 
                  LEFT JOIN '.TBL_USER_DATA.' AS f4 ON f4.usd_usr_id = dat_usr_id_change AND f4.usd_usf_id = (SELECT usf_id FROM ".$g_tbl_praefix."_user_fields WHERE usf_name_intern = 'LAST_NAME') 
                 WHERE (dat_begin >= '".DATE_NOW." 00:00:00' OR dat_end > '".DATE_NOW." 00:00:00') 
                   AND (  cat_org_id = '.$gCurrentOrganization->getValue('org_id').'
                       OR cat_org_id IS NULL )
                 ORDER BY dat_begin ';

        return $sql;
	}

}

?>
