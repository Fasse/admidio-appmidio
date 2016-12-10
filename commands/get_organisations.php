<?php
/******************************************************************************
 * Appmidio Command get_organisations.php
 *
 * Version 1.4.0
 *
 * Funktion fuer das Admidio-Plugin Appmidio, um die aktuellen Einstellungen auszulesen
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
	global $g_tbl_praefix;

 	$sql = "SELECT ";
	$sql = $sql."	org_id ";
	$sql = $sql."	, org_longname ";
	$sql = $sql."	, org_shortname ";
	$sql = $sql."	, org_org_id_parent ";
	$sql = $sql."	, org_homepage ";
	$sql = $sql."FROM ";
	$sql = $sql."	".$g_tbl_praefix."_organizations ";

	return $sql;
}
?>
