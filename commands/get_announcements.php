<?php
/******************************************************************************
 * Appmidio Command get_announcements.php
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
		$sql = 'SELECT ann_id 
                     , ann_headline 
                     , ann_description
                     , ann_timestamp_create
                     , ann_usr_id_create
                     , IFNULL(f1.usd_value, \'\') AS cre_first_name 
                     , IFNULL(f2.usd_value, \'\') AS cre_last_name 
                  FROM '.TBL_ANNOUNCEMENTS.'
                  LEFT JOIN '.TBL_CATEGORIES.' ON cat_id = ann_cat_id 
        		  LEFT JOIN '.TBL_USER_DATA.' AS f1 ON f1.usd_usr_id = ann_usr_id_create AND f1.usd_usf_id = (SELECT usf_id FROM '.TBL_USER_FIELDS.' WHERE usf_name_intern = \'FIRST_NAME\') 
                  LEFT JOIN '.TBL_USER_DATA.' AS f2 ON f2.usd_usr_id = ann_usr_id_create AND f2.usd_usf_id = (SELECT usf_id FROM '.TBL_USER_FIELDS.' WHERE usf_name_intern = \'LAST_NAME\') 
                 WHERE (  cat_org_id = '.$gCurrentOrganization->getValue('org_id').'
                       OR cat_org_id IS NULL )
                 ORDER BY ann_timestamp_create DESC ';

		return $sql;
	}
}

?>
