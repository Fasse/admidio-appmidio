<?php
/******************************************************************************
 * Appmidio Command get_announcements.php
 *
 * Version 1.4.0
 *
 * Funktion fuer das Admidio-Plugin Appmidio, um die Ankündigungen auszulesen
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
	global $gValidLogin, $gPreferences, $g_tbl_praefix, $gCurrentOrganization;

	if($gValidLogin == false)
	{
		msg_unauthorized();
	}
	else if($gPreferences['enable_announcements_module'] == 0)
	{
		// das Modul ist deaktiviert
		// $gMessage->show($gL10n->get('SYS_MODULE_DISABLED'));
		msg_not_found('Das Modul Ankündigungen ist deaktiviert.');
	}
	else
	{
		$sql = "SELECT ";
		$sql = $sql."	ann_id ";
		$sql = $sql."	, ann_headline ";
		$sql = $sql."	, ann_description ";
		$sql = $sql."	, ann_timestamp_create ";
		$sql = $sql."   , ann_usr_id_create ";
		$sql = $sql."	, IFNULL(f1.usd_value, '') AS cre_first_name ";
		$sql = $sql."	, IFNULL(f2.usd_value, '') AS cre_last_name ";
		$sql = $sql." FROM ";
		$sql = $sql."	".$g_tbl_praefix."_announcements ";
		$sql = $sql."	LEFT JOIN ".$g_tbl_praefix."_user_data AS f1 ON f1.usd_usr_id = ann_usr_id_create AND f1.usd_usf_id = (SELECT usf_id FROM ".$g_tbl_praefix."_user_fields WHERE usf_name_intern = 'FIRST_NAME') ";
		$sql = $sql."	LEFT JOIN ".$g_tbl_praefix."_user_data AS f2 ON f2.usd_usr_id = ann_usr_id_create AND f2.usd_usf_id = (SELECT usf_id FROM ".$g_tbl_praefix."_user_fields WHERE usf_name_intern = 'LAST_NAME') ";
		$sql = $sql." WHERE ";
		$sql = $sql."	ann_global = 1 ";
		$sql = $sql."	OR ann_org_id = ".$gCurrentOrganization->getValue('org_id');
		$sql = $sql." ORDER BY ";
		$sql = $sql."	ann_timestamp_create DESC ";

		return $sql;
	}
}

?>
