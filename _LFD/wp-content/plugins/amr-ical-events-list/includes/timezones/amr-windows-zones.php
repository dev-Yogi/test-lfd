<?php 
// This file adds the ability to parse windows zones and map them to proper timezones
// the timezone mapping was generated by windowszonemap.php which reads the xml file at 
// http://unicode.org/repos/cldr/trunk/common/supplemental/windowsZones.xml and converts it to a php array

function amr_map_windows_to_timezone($icstzid) {
include ('WindowsZonesToTimeZones.txt');
	
	if (isset($wz[$icstzid])) {
		if (isset ($_REQUEST['tzdebug'])) echo '<br />Found a windows zone, converted it to a timezone.'.$wz[$icstzid]; 
		//reset($wz[$icstzid]); // we don't know the territories, so just grab first one
		//$first = current($wz[$icstzid]);	
		return ($wz[$icstzid]);
	}
	else return $icstzid;

	
}

 //echo amr_map_windows_to_timezone('Alaskan Standard Time'); //for test
add_filter ('amr-timezoneid-filter','amr_map_windows_to_timezone');

