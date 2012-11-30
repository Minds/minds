<?php
/**
 * Show the relevant license image
 */
 $license = $vars['license'];
 
 if($license == 'cca')
 $license = 'attribution-cc';
 if($license == 'ccs')
 $license = 'attribution-sharealike-cc';
 if($license == 'gpl')
 $license = 'gnuv3';
  
 $license_links = array(	'attribution-cc'=>'http://creativecommons.org/licenses/by/3.0/',
 							'attribution-sharealike-cc' => 'http://creativecommons.org/licenses/by-sa/3.0/',
							'attribution-noderivs-cc' => 'http://creativecommons.org/licenses/by-nd/3.0/',
							'attribution-noncommercial-cc' => 'http://creativecommons.org/licenses/by-nc/3.0/',
							'attribution-noncommercial-sharealike-cc' => 'http://creativecommons.org/licenses/by-nc-sa/3.0/',
							'attribution-noncommercial-noderivs-cc' => 'http://creativecommons.org/licenses/by-nc-nd/3.0/',
							'publicdomaincco' => 'http://creativecommons.org/publicdomain/zero/1.0/',
							'gnuv3' => 'http://www.gnu.org/licenses/gpl.html',
							'gnuv1.3' => 'http://www.gnu.org/copyleft/fdl.html',
							'mozillapublic' => 'http://www.mozilla.org/MPL/',
							'bsd' =>'http://opensource.org/licenses/BSD-2-Clause',
 						);
						
	if($license == 'bsd' || $license == 'mozillapublic'){
 		 
		 //no logos for these two so we have text instead
		  $url = elgg_view('output/url', array('href'=>$license_links[$license], 'text'=>elgg_echo('minds:license:'.$license), 'target'=> '_blank'));

	} else {
				  
		  $img = elgg_view('output/img', array('src'=>elgg_get_site_url().'mod/minds/graphics/licenses/'.$license.'.png'));
		 
		 $url = elgg_view('output/url', array('href'=>$license_links[$license], 'text'=>$img, 'target'=> '_blank'));
		 	
	}
	
	echo $url;