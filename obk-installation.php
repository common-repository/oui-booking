<?php
defined( 'ABSPATH' ) or die();

function obk_install(){
	if (!isset($wpdb)) $wpdb = $GLOBALS['wpdb'];
    global $wpdb;
    $wpdb->query($wpdb->prepare("CREATE TABLE IF NOT EXISTS {$wpdb->prefix}obk_spaces (id int(%d) AUTO_INCREMENT PRIMARY KEY NOT NULL,id_lieu int(11) NOT NULL,label varchar(512) NOT NULL,nb_de_place decimal(12,6) UNSIGNED,prix_de_la_place decimal(12,6),devise varchar(6),timeUnit decimal(11,6),tps_reservation_max_heure decimal(11,6),acompte_prix decimal(12,6),acompte_pourcentage decimal(9,6) ,lien_CGU varchar(2048),date_debut_reservation date NOT NULL,date_fin_reservation date NOT NULL,description text,statut_par_defaut_reservation varchar(20),notification_email tinyint(4),email_notification varchar(512),form_date_debut tinyint(4),form_heure_debut tinyint(4),form_date_fin tinyint(4),form_heure_fin tinyint(4),form_personnes tinyint(4),form_nom tinyint(4),form_prenom tinyint(4), form_adresse tinyint(4),form_code_postal tinyint(4),form_ville tinyint(4),form_pays tinyint(4),form_email tinyint(4),form_telephone tinyint(4),form_remarques tinyint(4),form_nb_de_place tinyint(4),info_date_debut_reservation tinyint(4),info_date_fin_reservation tinyint(4),info_prix_de_la_place tinyint(4),info_acompte_prix tinyint(4),info_acompte_pourcentage tinyint(4),info_timeUnit tinyint(4),info_tps_reservation_max_heure tinyint(4),info_description tinyint(4),info_calendrier tinyint(4),payment_instructions text,user_minutes_interval tinyint(4),periodesprices text NOT NULL,dayprice text NOT NULL,openingtimes text NOT NULL,minBookingDuration decimal(11,6),info_minBookingDuration tinyint(4),exceptionalclosure text NOT NULL);", "11"));

	$wpdb->query($wpdb->prepare("CREATE TABLE IF NOT EXISTS {$wpdb->prefix}obk_locations (id int(%d) AUTO_INCREMENT PRIMARY KEY NOT NULL,nom varchar(128) NOT NULL);", "11"));
    
	$wpdb->query($wpdb->prepare("CREATE TABLE IF NOT EXISTS {$wpdb->prefix}obk_modifyprice (id int(%d) AUTO_INCREMENT PRIMARY KEY NOT NULL,label varchar(512) NOT NULL,description text, date_debut date NOT NULL,date_fin date NOT NULL,quantite decimal(13,6) UNSIGNED NOT NULL,quantite_initiale decimal(13,6) UNSIGNED NOT NULL,montant decimal(12,6) NOT NULL,pourcentage decimal(9,6) NOT NULL,periode_heure decimal(10,6) UNSIGNED NOT NULL,code varchar(512) NOT NULL,type varchar(32) NOT NULL,details_texte text);", "11"));
    
	$wpdb->query($wpdb->prepare("CREATE TABLE IF NOT EXISTS {$wpdb->prefix}obk_modifyprice_spaces (id int(%d) AUTO_INCREMENT PRIMARY KEY NOT NULL,obk_idSpace int(11) NOT NULL,id_modificationprix int(11) NOT NULL);", "11"));
	
	$wpdb->query($wpdb->prepare("CREATE TABLE IF NOT EXISTS {$wpdb->prefix}obk_modifyprice_bookings (id int(%d) AUTO_INCREMENT PRIMARY KEY NOT NULL,id_reservation int(11) NOT NULL,label varchar(512) NOT NULL,description text NOT NULL,quantite decimal(13,6) UNSIGNED NOT NULL,montant decimal(12,6) NOT NULL,pourcentage decimal(9,6) NOT NULL,periode_heure decimal(10,6) UNSIGNED NOT NULL,code varchar(512) NOT NULL,type varchar(32) NOT NULL,details_texte text NOT NULL);", "11"));
	
	$wpdb->query($wpdb->prepare("CREATE TABLE IF NOT EXISTS {$wpdb->prefix}obk_parameters (id int(%d) AUTO_INCREMENT PRIMARY KEY NOT NULL,nom varchar(128) NOT NULL,val text);", "11"));
	
	$wpdb->query($wpdb->prepare("CREATE TABLE IF NOT EXISTS {$wpdb->prefix}obk_bookings (id int(%d) AUTO_INCREMENT PRIMARY KEY NOT NULL,obk_idSpace INT(11) NOT NULL,date datetime NOT NULL,nb_de_place decimal(12,6),prix_de_la_place decimal(12,6),acompte_prix decimal(12,6),acompte_pourcentage decimal(12,6),date_arrivee datetime,date_depart datetime,nb_de_personnes mediumint(9),nom varchar(256),prenom varchar(256),adresse varchar(1024),code_postal varchar(10),ville varchar(256),pays varchar(512),email varchar(1024),telephone varchar(20),remarques text,arrivee_acceptee varchar(128),depart_demande varchar(128),statut varchar(20),emplacement varchar(512),lieu varchar(128),devise varchar(6),reference_interne varchar(256),timeUnit decimal(11,6),periodesprices text NOT NULL,dayprice text NOT NULL);", "11"));
	
	$wpdb->query($wpdb->prepare("INSERT INTO {$wpdb->prefix}obk_parameters (id, nom, val) VALUES (%d, %s, %s) ON DUPLICATE KEY UPDATE ID = %d", 2, 'customcss', '/* Oui! Booking Custom CSS */', 2)); 
	$wpdb->query($wpdb->prepare("INSERT INTO {$wpdb->prefix}obk_parameters (id, nom, val) VALUES (%d, %s, %s) ON DUPLICATE KEY UPDATE ID = %d", 3, 'roundnumber', '2', 3)); 
	$wpdb->query($wpdb->prepare("INSERT INTO {$wpdb->prefix}obk_parameters (id, nom, val) VALUES (%d, %s, %s) ON DUPLICATE KEY UPDATE ID = %d", 8, 'decimalseparator', '.', 8)); 
	$wpdb->query($wpdb->prepare("INSERT INTO {$wpdb->prefix}obk_parameters (id, nom, val) VALUES (%d, %s, %s) ON DUPLICATE KEY UPDATE ID = %d", 9, 'timezone', 'UTC', 9)); 
	$wpdb->query($wpdb->prepare("INSERT INTO {$wpdb->prefix}obk_parameters (id, nom, val) VALUES (%d, %s, %s) ON DUPLICATE KEY UPDATE ID = %d", 10, 'colors', '#ffffff-#7f7f7f-#400040-#ffffff-#400040-#f4f4f4-#7f7f92-#ddffcc-#7f7f7f-#fee29f-#7f7f7f-#ffc0bd-#7f7f7f-#3b88c3-#ffffff-#3b88c3-#ffffff-#f4f4f4-#7f7f7f-#eeeeee-#7f7f7f-#e0e0e0-#7f7f7f-#bfceda-#5d5d5d-#00589c-#ffffff-#d6d6d6-#7e7e7e-#ffffff-#7f7f7f-#f5f5f5-#e4e4e4', 10)); 
	
	$wpdb->query($wpdb->prepare("CREATE TABLE IF NOT EXISTS {$wpdb->prefix}obk_shortcodes (id int(%d) AUTO_INCREMENT PRIMARY KEY NOT NULL,nom varchar(128) NOT NULL,affichage varchar(16), tabEmplacements text);", "11"));
}

function obk_deactivation(){
    global $wpdb;
}

function obk_uninstall(){
	global $wpdb;
	$wpdb->query($wpdb->prepare("DROP TABLE {$wpdb->prefix}obk_spaces"));
	$wpdb->query($wpdb->prepare("DROP TABLE {$wpdb->prefix}obk_locations"));
	$wpdb->query($wpdb->prepare("DROP TABLE {$wpdb->prefix}obk_modifyprice"));
	$wpdb->query($wpdb->prepare("DROP TABLE {$wpdb->prefix}obk_modifyprice_spaces"));
	$wpdb->query($wpdb->prepare("DROP TABLE {$wpdb->prefix}obk_modifyprice_bookings"));
	$wpdb->query($wpdb->prepare("DROP TABLE {$wpdb->prefix}obk_parameters"));
	$wpdb->query($wpdb->prepare("DROP TABLE {$wpdb->prefix}obk_bookings"));
}

function obk_checkUpgrade(){
	$old_ver = obk_getWPOption('version');
	$new_ver = WPOBK_VERSION;
	if ($old_ver < $new_ver){
		obk_upgrade($old_ver,$new_ver);
	}
	obk_updateWPOption( 'version', $new_ver );
}

function obk_getWPOption($name) {
	$option = get_option('wpobk');
	if ($option === false){
		return WPOBK_VERSION;
	}
	if (isset($option[$name])){
		return $option[$name];
	}else{
		return WPOBK_VERSION;
	}
}

function obk_updateWPOption($name, $value) {
	$option = get_option('wpobk');
	$option = ($option === false ) ? array() : (array)$option;
	$option = array_merge($option, array($name => $value));
	update_option('wpobk', $option);
}

function obk_upgrade($old_ver,$new_ver){
	global $wpdb;
	for($numVersion = $old_ver + 1; $numVersion <= $new_ver; $numVersion++){
		switch($numVersion){
			case "1": break;
			case "2": break;
			case "3": break;
			case "4": break;
			case "5": break;
			case "6": /* Version 1.2.2 */
				$checkupdate = $wpdb->get_results($wpdb->prepare("SHOW COLUMNS FROM {$wpdb->prefix}obk_spaces WHERE %d=%d","1","1"));
				$cols = array();
				foreach($checkupdate as $row){
					$cols[$row->Field] = $row->Type;
				}
				$initFileds = false;
				if (!isset($cols["jours_autorises_arrivee"])){$initFileds=true;$wpdb->query($wpdb->prepare("ALTER TABLE {$wpdb->prefix}obk_spaces ADD jours_autorises_arrivee varchar(%d)","7"));}
				if (!isset($cols["jours_autorises_depart"])){$initFileds=true;$wpdb->query($wpdb->prepare("ALTER TABLE {$wpdb->prefix}obk_spaces ADD jours_autorises_depart varchar(%d)","7"));}
				if (!isset($cols["info_jours_autorises_arrivee"])){$initFileds=true;$wpdb->query($wpdb->prepare("ALTER TABLE {$wpdb->prefix}obk_spaces ADD info_jours_autorises_arrivee tinyint(%d)","4"));}
				if (!isset($cols["info_jours_autorises_depart"])){$initFileds=true;$wpdb->query($wpdb->prepare("ALTER TABLE {$wpdb->prefix}obk_spaces ADD info_jours_autorises_depart tinyint(%d)","4"));}
				if ($initFileds){
					$wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}obk_spaces SET jours_autorises_arrivee=%s WHERE jours_autorises_arrivee=''","1234560"));
					$wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}obk_spaces SET jours_autorises_depart=%s WHERE jours_autorises_depart=''","1234560"));
					$wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}obk_spaces SET info_jours_autorises_arrivee=%d WHERE info_jours_autorises_arrivee='0'","1"));
					$wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}obk_spaces SET info_jours_autorises_depart=%d WHERE info_jours_autorises_depart='0'","1"));
				}
				break;
			case "7": /* Version 1.2.3 */
				$wpdb->query("ALTER TABLE {$wpdb->prefix}obk_modifyprice ADD details_texte text");
				$wpdb->query("ALTER TABLE {$wpdb->prefix}obk_modifyprice_bookings ADD details_texte text");
				$wpdb->query($wpdb->prepare("INSERT INTO {$wpdb->prefix}obk_options (id, nom, val) VALUES (%d, %s, %s) ON DUPLICATE KEY UPDATE ID = %d", 10, 'colors', '#ffffff-#7f7f7f-#400040-#ffffff-#400040-#324c61-#7f7f92-#ddffcc-#7f7f7f-#fee29f-#7f7f7f-#ffc0bd-#7f7f7f-#3b88c3-#ffffff-#3b88c3-#ffffff-#f4f4f4-#7f7f7f-#eeeeee-#7f7f7f-#e0e0e0-#7f7f7f-#bfceda-#5d5d5d-#00589c-#ffffff', 10)); 
				break;
			case "8": /* Version 1.3.2 */
				$wpdb->query("DELETE FROM {$wpdb->prefix}obk_options WHERE id=4");
				$wpdb->query("ALTER TABLE {$wpdb->prefix}obk_spaces ADD user_minutes_interval tinyint(4)");
				$wpdb->query("UPDATE {$wpdb->prefix}obk_spaces SET form_heure_debut=1, form_heure_fin=1, user_minutes_interval=1");
				break;
			case "9": /* Version 1.3.5 */
				$wpdb->query("UPDATE {$wpdb->prefix}obk_options SET val=CONCAT(val,'-#d6d6d6-#7e7e7e-#ffffff-#7f7f7f-#f5f5f5-#e4e4e4') WHERE id='10'");
				break;
			case "10": /* Version 1.4.1 */
				$wpdb->query("DROP TABLE {$wpdb->prefix}obk_texts");
				$wpdb->query("DELETE FROM {$wpdb->prefix}obk_options WHERE id=1");
				$wpdb->query("RENAME TABLE {$wpdb->prefix}obk_options TO {$wpdb->prefix}obk_parameters");
				break;
			case "11": /* Version 1.4.2, 2018, french financial law, article 105 */
				$wpdb->query("DELETE FROM {$wpdb->prefix}obk_modifyprice_bookings WHERE type='payment'");
				$wpdb->query("DELETE FROM {$wpdb->prefix}obk_parameters WHERE nom='paypalenabled'");
				$wpdb->query("DELETE FROM {$wpdb->prefix}obk_parameters WHERE nom='paypalemail'");
				$wpdb->query("DELETE FROM {$wpdb->prefix}obk_parameters WHERE nom='paypalcustomform'");
				$wpdb->query($wpdb->prepare("CREATE TABLE IF NOT EXISTS {$wpdb->prefix}obk_shortcodes (id int(%d) AUTO_INCREMENT PRIMARY KEY NOT NULL,nom varchar(128) NOT NULL,affichage varchar(16), tabEmplacements text);", "11"));
				break;
			case "12": /* Version 1.4.3 */
				$wpdb->query("UPDATE {$wpdb->prefix}obk_modifyprice SET code='userchoice' WHERE type='option'");
				break;
			case "13": /* Version 1.5 */
				$wpdb->query("ALTER TABLE {$wpdb->prefix}obk_spaces ADD periodesprices text NOT NULL");
				$wpdb->query("ALTER TABLE {$wpdb->prefix}obk_spaces ADD dayprice text NOT NULL");
				$wpdb->query("ALTER TABLE {$wpdb->prefix}obk_bookings ADD tps_reservation_min_heure decimal(11,6)");
				$wpdb->query("ALTER TABLE {$wpdb->prefix}obk_bookings ADD periodesprices text NOT NULL");
				$wpdb->query("ALTER TABLE {$wpdb->prefix}obk_bookings ADD dayprice text NOT NULL");
				$wpdb->query("UPDATE {$wpdb->prefix}obk_modifyprice_bookings SET description = '' WHERE description IS NULL");
				$wpdb->query("UPDATE {$wpdb->prefix}obk_modifyprice_bookings SET details_texte = '' WHERE details_texte IS NULL");
				$wpdb->query("ALTER TABLE {$wpdb->prefix}obk_modifyprice_bookings MODIFY description text NOT NULL");
				$wpdb->query("ALTER TABLE {$wpdb->prefix}obk_modifyprice_bookings MODIFY details_texte text NOT NULL");
				break;
			case "17": /*Version 1.6 */
				$wpdb->query("ALTER TABLE {$wpdb->prefix}obk_spaces ADD openingtimes text NOT NULL");
				$wpdb->query("ALTER TABLE {$wpdb->prefix}obk_spaces ADD exceptionalclosure text NOT NULL");
				$spacesUpdate = $wpdb->get_results("SELECT id,jours_autorises, info_jours_autorises, jours_autorises_arrivee, info_jours_autorises_arrivee, jours_autorises_depart, info_jours_autorises_depart, horaire_debut_arrivee_accepte, info_horaire_debut_arrivee_accepte, reference_horaire_debut_arrivee, horaire_fin_arrivee_accepte, info_horaire_fin_arrivee_accepte, reference_horaire_fin_arrivee, horaire_debut_depart_accepte, info_horaire_debut_depart_accepte, reference_horaire_debut_depart, horaire_fin_depart_accepte, info_horaire_fin_depart_accepte, reference_horaire_fin_depart FROM {$wpdb->prefix}obk_spaces");
				foreach ($spacesUpdate as $space) {
					$defaultOT = [[[0],[0],[0]],[[0],[0],[0]],[[0],[0],[0]],[[0],[0],[0]],[[0],[0],[0]],[[0],[0],[0]],[[0],[0],[0]]];
					for($numDay=0; $numDay<7; $numDay++){
						for($pos=0;$pos<strlen($space->jours_autorises);$pos++){
							if (substr($space->jours_autorises,$pos,1) == $numDay){
								$defaultOT[$numDay][0][0] = 1;
								$defaultOT[$numDay][0][] = array('00:00','00:00','false','false');
							}
						}
						for($pos=0;$pos<strlen($space->jours_autorises_arrivee);$pos++){
							if (substr($space->jours_autorises_arrivee,$pos,1) == $numDay){
								$defaultOT[$numDay][1][0] = 1;
								$refDeb = (($space->reference_horaire_debut_arrivee == 1) ? "true" : "false");
								$refFin = (($space->reference_horaire_fin_arrivee == 1) ? "true" : "false");
								$defaultOT[$numDay][1][] = array(substr($space->horaire_debut_arrivee_accepte,0,5),substr($space->horaire_fin_arrivee_accepte,0,5),$refDeb,$refFin);
							}
						}
						for($pos=0;$pos<strlen($space->jours_autorises_depart);$pos++){
							if (substr($space->jours_autorises_depart,$pos,1) == $numDay){
								$defaultOT[$numDay][2][0] = 1;
								$refDeb = (($space->reference_horaire_debut_depart == 1) ? "true" : "false");
								$refFin = (($space->reference_horaire_fin_depart == 1) ? "true" : "false");
								$defaultOT[$numDay][2][] = array(substr($space->horaire_debut_depart_accepte,0,5),substr($space->horaire_fin_depart_accepte,0,5),$refDeb,$refFin);
							}
						}
					}
					$wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}obk_spaces SET openingtimes = %s WHERE id = %s",json_encode($defaultOT),$space->id));
				}	
				$wpdb->query("ALTER TABLE {$wpdb->prefix}obk_spaces DROP COLUMN jours_autorises, DROP COLUMN jours_autorises_arrivee, DROP COLUMN jours_autorises_depart, DROP COLUMN horaire_debut_arrivee_accepte, DROP COLUMN reference_horaire_debut_arrivee, DROP COLUMN horaire_fin_arrivee_accepte, DROP COLUMN reference_horaire_fin_arrivee, DROP COLUMN horaire_debut_depart_accepte, DROP COLUMN reference_horaire_debut_depart, DROP COLUMN horaire_fin_depart_accepte, DROP COLUMN reference_horaire_fin_depart, DROP COLUMN info_jours_autorises, DROP COLUMN info_horaire_debut_arrivee_accepte, DROP COLUMN info_horaire_fin_arrivee_accepte, DROP COLUMN info_horaire_debut_depart_accepte, DROP COLUMN info_horaire_fin_depart_accepte, DROP COLUMN info_jours_autorises_arrivee, DROP COLUMN info_jours_autorises_depart, DROP COLUMN delai_min_reservation_heure");
				$wpdb->query("ALTER TABLE {$wpdb->prefix}obk_bookings DROP COLUMN arrivee_acceptee, DROP COLUMN depart_demande");
				$wpdb->query("ALTER TABLE {$wpdb->prefix}obk_bookings CHANGE tps_reservation_min_heure timeUnit decimal(11,6)");
				$wpdb->query("ALTER TABLE {$wpdb->prefix}obk_spaces CHANGE tps_reservation_min_heure timeUnit decimal(11,6)");
				$wpdb->query("ALTER TABLE {$wpdb->prefix}obk_spaces CHANGE info_tps_reservation_min_heure info_timeUnit tinyint(4)");
				$wpdb->query("ALTER TABLE {$wpdb->prefix}obk_spaces ADD minBookingDuration decimal(11,6)");
				$wpdb->query("ALTER TABLE {$wpdb->prefix}obk_spaces ADD info_minBookingDuration tinyint(4)");
		}
	}
	file_put_contents( plugin_dir_path(__FILE__) . '/error_upgrade_activation.html', ob_get_contents());
}