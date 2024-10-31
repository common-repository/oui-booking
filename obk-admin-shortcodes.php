<?php
defined( 'ABSPATH' ) or die();
function obk_process_action_shortcodes(){
	global $wpdb;
	global $SAFE_DATA;
	global $obk_act;
	
	if (!current_user_can('administrator')){return;}
	$isDataFormSafe = obk_secureDataForm();
	if (!$isDataFormSafe[0]){
		echo 'DEBUG4:------------------- '. $isDataFormSafe[1].'<br>';
		error_log('DEBUG4:------------------- '. $isDataFormSafe[1]);
	}else{
		$SAFE_DATA = $isDataFormSafe[2];
	}
	if (isset($SAFE_DATA['obk_act'])){
		$obk_act = $SAFE_DATA['obk_act'];
		check_admin_referer($obk_act);
	}
	
	$id_shortcode = -1;
	$nom_shortcode = 'Shortcode';
	$affichage_Shortcode = 'fulldisplay';
	$tabEmplacements_Shortcode = [];
	
	if ($obk_act == 'newSaveShortcode'){
		$nom_shortcode = $SAFE_DATA['shortcodename'];
		$affichage_Shortcode = $SAFE_DATA['shortcodedisplay'];
		$tabEmplacements = $SAFE_DATA['tabEmplacements'];
		$wpdb->query($wpdb->prepare("INSERT INTO {$wpdb->prefix}obk_shortcodes (id,nom,affichage,tabEmplacements) VALUES (NULL,%s,%s,%s)",$nom_shortcode,$affichage_Shortcode,$tabEmplacements));
		$SAFE_DATA['id_shortcode'] = $wpdb->insert_id;
		$obk_act = 'editShortcode';
	}
	
	if ($obk_act == 'saveShortcode'){
		$id_shortcode = $SAFE_DATA['id_shortcode'];
		$nom_shortcode = $SAFE_DATA['shortcodename'];
		$affichage_Shortcode = $SAFE_DATA['shortcodedisplay'];
		$tabEmplacements = $SAFE_DATA['tabEmplacements'];
		$wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}obk_shortcodes SET nom = %s, affichage = %s, tabEmplacements = %s WHERE id = %d",$nom_shortcode,$affichage_Shortcode,$tabEmplacements, $id_shortcode));
		$obk_act = 'editShortcode';
		$tabEmplacements_Shortcode = json_decode($tabEmplacements);
	}
	
	if ($obk_act == 'deleteShortcode'){
		$id_shortcode = $SAFE_DATA['id_shortcode'];
		$wpdb->query($wpdb->prepare("DELETE FROM {$wpdb->prefix}obk_shortcodes WHERE id = %d", $id_shortcode));
		unset($SAFE_DATA['id_shortcode']);
	}
	
	if (isset($SAFE_DATA['id_shortcode'])){
		$id_shortcode = $SAFE_DATA['id_shortcode'];
		$resultShortcode = $wpdb->get_row($wpdb->prepare("SELECT nom, affichage, tabEmplacements FROM {$wpdb->prefix}obk_shortcodes WHERE id = %d",$id_shortcode));
		$nom_shortcode = $resultShortcode->nom;
		$affichage_Shortcode = $resultShortcode->affichage;
		$tabEmplacements_Shortcode = json_decode(stripslashes($resultShortcode->tabEmplacements));
	}
	$tabEmplacements_Shortcode[] = ['',sizeof($tabEmplacements_Shortcode)+1,'','',''];
	
	echo '	<div id="obk_content">
				<div id="obk_content1"><h1 id="obk_mainPluginTitle">';
	echo '			<a href="'.admin_url('admin.php?page=oui-booking').'"><img src="'.plugins_url( 'img/logo55.png', __FILE__ ).'"></a>';
	echo '			<span>'.get_admin_page_title().'</span></h1>';
	echo '			<div class="obk_wrap">';
						
	echo obk_displayAdminNotice(1);				
						
	if (($obk_act == 'newShortcode') || ($obk_act == 'editShortcode')){
		echo '<form method="POST" action="" name="obk_saveShortcode">';
		
		if ($obk_act == 'newShortcode'){
			echo '<input type="hidden" name="obk_act" value="newSaveShortcode">';
			wp_nonce_field('newSaveShortcode');
			echo '<h2>'.__('Add shortcode','oui-booking').'</h2><div class="obk_headBar"><div>';
		}else{
			echo '<input type="hidden" name="obk_act" value="saveShortcode"><input type="hidden" name="id_shortcode" value="'.$id_shortcode.'">';
			wp_nonce_field('saveShortcode');
			echo '<h2>'.__('Edit shortcode','oui-booking').' <span id="obk_spanShortcode">[obk_shortcodeList id="'.$id_shortcode.'"] </span></h2><div class="obk_headBar"><div>';
		}
		echo '		
					<label for="obk_shortcodename"><b>'.__('Name','oui-booking').':</b></label>
					<br><input type="text" name="shortcodename" id="obk_shortcodename" value="'.$nom_shortcode.'">
					<br>'.__('The name of the shortcode, only visible for you.','oui-booking').'
				</div>
				<div>
					<label for="obk_shortcodedisplay"><b>'.__('Display mode','oui-booking').':</b></label>
					<br><select name="shortcodedisplay" id="obk_shortcodedisplay">
							<option value="dropdownlist" '.(($affichage_Shortcode == 'dropdownlist') ? 'selected' : '' ).'>'.__('Dropdown list','oui-booking').'</option>
							<option value="fulldisplay" '.(($affichage_Shortcode == 'fulldisplay') ? 'selected' : '' ).'>'.__('Full display','oui-booking').'</option>
						</select>
					<br>'.__('Choose how the shortcode should display the booking form list.','oui-booking').'
				</div>
			</div>
			<br>';
		
		$position = 0;
		$maxPosition = sizeof($tabEmplacements_Shortcode);
		$emplacements = $wpdb->get_results("SELECT emp.id,emp.label,lieu.nom FROM {$wpdb->prefix}obk_spaces emp INNER JOIN {$wpdb->prefix}obk_locations lieu ON lieu.id=emp.id_lieu ORDER BY emp.id DESC");
		foreach($tabEmplacements_Shortcode as $emplacement_Shortcode){
			$position++;
			$title = __('Add a place in the shortcode','oui-booking');
			foreach($emplacements as $emplacement){
				if ($emplacement->id == $emplacement_Shortcode[0]){
					$title = '#'.$emplacement->id.' - '.$emplacement->nom.' - '.$emplacement->label;
				}
			}
			$class = 'obk_hideAccordion';
			if ($position == $maxPosition){$class = '';}
			echo '<div><button class="obk_accordion '.$class.'">'.$title.'</button>
				 <div class="obk_panel " >
					<div id="obk_welcome">
						<div>
							<br><label for="obk_shortcodespace"><b>'.__('Place','oui-booking').':</b></label>
							<br><select name="shortcodespace" id="obk_shortcodespace"><option></option>';
							
							foreach($emplacements as $emplacement){
								echo '<option value="'.$emplacement->id.'" '.(($emplacement->id == $emplacement_Shortcode[0]) ? 'selected' : '').'>#'.$emplacement->id.' - '.$emplacement->nom.' - '.$emplacement->label.'</option>';
							}
							echo '</select>
							<br>'.__('The place to display','oui-booking').'
						</div>
						<div>
							<br><select name="shortcodespaceposition" id="obk_shortcodespaceposition">';
							for($i = 1; $i <= $maxPosition; $i++){
								echo '<option value="'.$i.'" '.(($emplacement_Shortcode[1] == $i) ? 'selected' : '' ).'>'.__('Position','oui-booking').' '.$i.'</option>';
							}
							echo '</select>
						</div>
					</div>
					
					<br><br><label><b>'.__('Redirection','oui-booking').':</b></label>
					
					<div class="obk_shortcodeRedirectionBlock">
					<div><input type="radio" name="shortcodespaceposttype'.$position.'" value="page" '.(($emplacement_Shortcode[2] == 'page') ? 'checked' : '').'><span class="obk_shortcodeRedirection">'.__('Pages','oui-booking').': </span><select class="obk_shortcodeRedirectionSelect" name="shortcodeRedirectionSelectPage"><option></option>';
					$pages = get_pages();	
					foreach($pages as $page){
						echo '<option value="'.$page->ID.'" '.((($emplacement_Shortcode[2] == 'page')&&($emplacement_Shortcode[3] == $page->ID)) ? 'selected' : '').'>'.$page->post_title.'</option>';
					}
					echo '</select>
					</div>
					
					<div><input type="radio" name="shortcodespaceposttype'.$position.'" value="post" '.(($emplacement_Shortcode[2] == 'post') ? 'checked' : '').'><span class="obk_shortcodeRedirection">'.__('Post','oui-booking').': </span><select class="obk_shortcodeRedirectionSelect" name="shortcodeRedirectionSelectPost"><option></option>';
					$posts = get_posts();	
					foreach($posts as $post){
						echo '<option value="'.$post->ID.'"'.((($emplacement_Shortcode[2] == 'post')&&($emplacement_Shortcode[3] == $post->ID)) ? 'selected' : '').'>'.$post->post_title.'</option>';
					}
					echo '</select>
					</div>
					
					<div><input type="radio" name="shortcodespaceposttype'.$position.'" value="link" '.(($emplacement_Shortcode[2] == 'link') ? 'checked' : '').'><span class="obk_shortcodeRedirection">'.__('Link','oui-booking').': </span><input type="text" name="shortcodespacecustomlink" class="obk_shortcodeRedirectionSelect" value="'.(($emplacement_Shortcode[2] == 'link') ? $emplacement_Shortcode[2] : '').'">
					
					</div>
					</div>'.__('Which page should the user selection redirect to?','oui-booking').'
					
					<br><br><label><b>'.__('Description','oui-booking').':</b></label>
					<br>';
					wp_editor($emplacement_Shortcode[4], 'shortcodespacedescription'.$position );
					echo '<br>'.__('The description of the place, only visible with the full display mode.','oui-booking').'
					<br><br>
				</div>
			</div>';
		}
			
		echo '<br><br><div class="obk_buttons"><div class="obk_pad10">'.get_submit_button(__('Save', 'oui-booking'));
		echo '<textarea id="tabEmplacements" name="tabEmplacements"></textarea></form>';
		
		
		
		
		
	
		echo '<form action="" method="post" class="obk_delete" onsubmit="return confirm(\''.__('Are you sure you want to delete this shortcode?', 'oui-booking').'\');">
			<input type="hidden" name="obk_act" value="deleteShortcode">
			<input type="hidden" name="id_shortcode" value="'.$id_shortcode.'">';
		echo obk_get_wp_nonce_field('deleteShortcode');
		echo get_submit_button(__('Delete', 'oui-booking'),'delete').'</div></div>';
		
		
		
		
		
		echo '<br><br>'.__('Note: You will be able to add more places in the shortcode after each saving.','oui-booking').'
		
		</form>';
		echo '<script>obk_listenerSaveShortcode()</script>';
	}else{
						
						
						
						
						
						
						
						
						
						
		$resultats5 = $wpdb->get_results("SELECT sp.id, sp.label, lo.nom FROM {$wpdb->prefix}obk_spaces sp INNER JOIN {$wpdb->prefix}obk_locations lo ON sp.id_lieu = lo.id ORDER BY lo.id DESC, sp.id DESC");	
		
		echo '<h2>'.__('Shortcodes for a single place (Booking form)','oui-booking').'</h2>';
		echo __('Here you can choose a language (optional) for the display of the booking form. Then, copy the shortcode and paste it where you want (in a post or in a page) to show the form.','oui-booking');
		echo '<br>';
		echo __('To add more languages, please open the pot file in the plugin languages directory and create a new mo file based on it, with a program like Poedit. Then share your creation with the community!','oui-booking');
		
		if (sizeof($resultats5) == 0){
			echo '<br><b>'.__('Add a booking place before editing shortcodes', 'oui-booking') . '</b>';
		}else{
			echo '<br><br><table class="obk_widefat" id="obk_tabShortcodes"><thead><th>#</th><th class="obk_column-primary">'.__('Shortcodes','oui-booking').'</th><th>'.__('Languages','oui-booking').'</th><th>'.__('Place','oui-booking').'</th></tr></thead><tbody>';
			$dir = dirname(__FILE__) . '/languages';
			$listOfFiles = scandir($dir);
			$optionsLanguage = '';
			foreach($listOfFiles as $file){
				if (($file != '.')&&($file != '..')){
					if ((strtolower(substr($file,0,12)) == 'oui-booking-')&&(strtolower(substr($file,-2,2) == 'mo'))){
						$code = explode('-',explode('.',$file)[0])[2];
						$optionsLanguage .= '<option value="'.$code.'">'.$code.'</option>';
					}
				}
			}
			foreach($resultats5 as $space){
				echo '	<tr><td>#'.$space->id.'</td>
							<td class="obk_column-primary"><span id="obk_shortcodeShortcode'.$space->id.'">[obk_shortcode id="'.$space->id.'" lang=""]</span></td>
							<td><select name="obk_shortcodeLanguage-'.$space->id.'" onchange="obk_shortcodeChangeLanguage(this)"><option></option>'.$optionsLanguage.'</select></td>
							<td>'.ucfirst($space->nom) .' - '.ucfirst($space->label).'</td>
							
							
						</tr>';
			}
			echo '</tbody></table>';
		}
		
		
		
		
		
		
		
		
		
		echo '<br><h2>'.__('Shortcodes for a multiple selection of place','oui-booking').'</h2>';
		echo __('If you want to propose a list of available places for your visitors, you can add a shortcode here and choose the places it will display.','oui-booking');
		
		echo '<br><br><table class="obk_widefat" id="obk_tabShortcodes"><thead><th>#</th><th class="obk_column-primary">'.__('Shortcodes','oui-booking').'</th><th>'.__('Name','oui-booking').'</th><th>'.__('Display','oui-booking').'</th></tr></thead><tbody>';
		$resultats6 = $wpdb->get_results("SELECT id, nom, affichage FROM {$wpdb->prefix}obk_shortcodes ORDER BY id DESC");
		foreach($resultats6 as $shortcode){
			echo '	<tr><td>#'.$shortcode->id.'</td>
						<td class="obk_column-primary">[obk_shortcodeList id="'.$shortcode->id.'"]</td>
						<td><form action="" method="post"><input type="hidden" name="obk_act" value="editShortcode"><input type="hidden" name="id_shortcode" value="'.$shortcode->id.'">';
			wp_nonce_field('editShortcode');
			echo 		get_submit_button($shortcode->nom).'</form></td>
						<td>'.(($shortcode->affichage == 'dropdownlist') ? __('Dropdown list','oui-booking') : __('Full display','oui-booking') ).'</td>
						
						
					</tr>';
		}
		
		echo '</tbody></table>';
		echo '<br><form action="" method="POST"><input type="hidden" name="obk_act" value="newShortcode">';
		wp_nonce_field('newShortcode');
		echo get_submit_button(__('Add a new shortcode', 'oui-booking')).'</form>';
	}	
	
	
	
	
	
	
	
	
	
	
	
	
	
	echo '			</div>';
	echo '		</div>
				<div id="obk_content2">
					<div>';
	echo 				obk_getTutorialsLinks();
	echo '			</div>
				</div>
			</div>';
}
