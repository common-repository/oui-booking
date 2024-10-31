<?php
defined( 'ABSPATH' ) or die();
$echo .= '<div id="obk_plugin_onglets_calendrier"><div><input type="hidden" id="obk_nbDePlace" value="'.$emplacement->nb_de_place.'">';
if (isset($SAFE_DATA["calendarCurrentYearMonth"])){
	$echo .= '<input type="hidden" id="obk_currentMonth" value="'.(substr($SAFE_DATA["calendarCurrentYearMonth"],5,2)-1).'">';
	$echo .= '<input type="hidden" id="obk_currentYear" value="'.substr($SAFE_DATA["calendarCurrentYearMonth"],0,4).'">';
}
$echo .= '<script>obk_initCalendar();</script>
<div id="obk_navBar">
	<form name="when">';
$echo .= obk_get_wp_nonce_field('chargeCalendrier','obk_mainCalendar');
$echo .= '	<table>
			<tr>
			   <td><span class="obk_norotate_arrow_calendar">
						<img class="emoji" src="'.plugins_url( 'img/arrow.svg', __FILE__ ).'" onClick="obk_calendarSkip(\'-\')">
					</span>
				</td>
			   <td> </td>
			   <td><select name="month" onChange="obk_calendarOnMonth()">';
			   $echo .= obk_getMonthsOption(date('Y'),date('m'));
			   $echo .= '</select>
			   </td>
			   <td colspan="2"><input class="obk_calendarYear" type="text" name="year" size=4 maxlength=4 onKeyPress="return obk_calendarCheckNums()" onKeyUp="obk_calendarOnYear()"></td>
			   <td><span class="obk_rotate_arrow_calendar">
						<img class="emoji" src="'.plugins_url( 'img/arrow.svg', __FILE__ ).'" onClick="obk_calendarSkip(\'+\')">
					</span>
				</td>
			</tr>
		</table>
	</form>
</div>
<div id="obk_calendar"></div>';
$echo .= '<h2>'.__('Arrivals of the week', 'oui-booking').'</h2>';
$echo .= '<div class="obk_wrap" id="obk_contentReservations"></div>';
$echo .= '</div></div>';