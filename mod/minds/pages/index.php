<?php
/**
 * Minds theme
 *
 * @package Minds
 * @author Kramnorth (Mark Harding)
 *
 * 
 * Free & Open Source Social Media
 */
global $CONFIG;
$limit = get_input('limit', 4);
$offset = get_input('offset', 0);
$filter = get_input('filter', 'featured');

if($offset > 0 && $filter == 'featured'){
	$limit++;
}

if($filter == 'featured' && !get_input('timespan')){
	$entities = minds_get_featured('', $limit, 'entities',$offset); 
} else {
	//trending
	$options = array(
		'timespan' => get_input('timespan', 'day')
	);
	$trending = new MindsTrending(array(), $options);
	$guids = $trending->getList(array('limit'=> $limit, 'offset'=>$offset));
	if($guids){
		$entities = elgg_get_entities(array('guids'=>$guids, 'limit'=>$limit,'offset'=>0));
	} 
}

if(!elgg_is_logged_in()){
	$buttons = elgg_view('output/url', array('href'=>elgg_get_site_url().'register', 'text'=>elgg_echo('register'), 'class'=>'elgg-button elgg-button-action'));
} else {
	 $buttons = elgg_view('output/url', array('href'=>elgg_get_site_url().'archive/upload','text'=>elgg_echo('minds:archive:upload'), 'class'=>'elgg-button elgg-button-action'));
	 $buttons .= elgg_view('output/url', array('href'=>elgg_get_site_url().'blog/add','text'=>elgg_echo('blog:add'), 'class'=>'elgg-button elgg-button-action'));

}

//$buttons .= elgg_view('output/url', array('href'=>elgg_get_site_url().'nodes/launch', 'text'=>elgg_echo('register:node'), 'class'=>'elgg-button elgg-button-action'));

//$user_count = elgg_get_entities(array('type'=>'user', 'count'=>true));
//$max = 1000000;
//$countdown = $max - $user_count;
//if(strpos(elgg_get_site_url(), 'www.minds.com/') !== FALSE)
//	$subtitle = "$countdown more human sign-ups until automatic global <a href='release'><b>code release</b></a>.";
elgg_load_js('carousel');
if(!get_input('ajax'))
	$title = Minds\Core\views::view('output/carousel', array('divs'=>$titles_array, 'subtitle'=> $subtitle));

$featured_item_class = $filter == 'featured' ? 'elgg-state-selected' : null;
$trending_item_class = $filter == 'trending' ? 'elgg-state-selected' : null;

$trending_menu = elgg_view_menu('trending');

if(elgg_is_sticky_form('register'))
	extract(elgg_get_sticky_values('register'));
	
$signup_form = elgg_is_logged_in() ? '' : <<<HTML
<div class="frontpage-signup">
		<form action="action/register">
			<input type="text" name="u" placeholder="username" value="$u" autocomplete="off"/>
			<input type="text" name="e" placeholder="email" value="$e" autocomplete="off"/>
			<input type="password" name="p" value="$p" placeholder="password" autocomplete="off"/>
			<input type="hidden" name="tcs" value="true"/>
			<input type="submit" value="Sign up" class="elgg-button elgg-button-submit"/>
		</form>
	</div>
HTML;

if(in_array(elgg_get_site_url(), array('https://www.minds.com/','https://www.minds.io/','http://127.0.0.1/'))){
	
	$signup_form = elgg_is_logged_in() ? '' : <<<HTML
		<div class="com-ui">
		
			<div class="frontpage-signup node">
				<form action="nodes/launch" method="GET">
					<div class="domain-input">
						<input type="text" name="domain" placeholder="Enter your new site address" autocomplete="off"/>
						<div class="url-domain">.minds.com</div>
					</div>
					<input type="submit" value="Create site" class="elgg-button elgg-button-submit"/>
				</form>
			</div>
	
			<div class="frontpage-signup user">
				<form action="register" method="GET">
					<div class="user-input">
						<div class="url-domain">www.minds.com/</div>
						<input type="text" name="u" placeholder="you" autocomplete="off"/>
					</div>
					<input type="submit" value="Create channel" class="elgg-button elgg-button-submit"/>
				</form>
			</div>
		
		</div>
HTML;
	
	
}


/** Hacky and shouldn't be here **/
$donations_box = '';
$paypal = elgg_get_plugin_setting('paypal', 'minds');
$bitcoin = elgg_get_plugin_setting('bitcoin', 'minds');
if($paypal || $bitcoin){
	$donations_box = '<div class="donations-box">';
		
	if($paypal)
		$donations_box .= '<a onclick="window.open(this.href, \'Paypal Donations\',
\'left=60,top=60,width=800,height=600,toolbar=1,resizable=0\'); return false;" href="https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business='.urlencode($paypal) .'&lc=US&item_name='.$CONFIG->site->name.'%2e&no_note=0&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donate_SM%2egif%3aNonHostedGuest" class="donations-button donations-button-paypal" target="_blank"> <span class="entypo"> &#59409; </span> Click to donate USD </a>'; 
	if($bitcoin)
		$donations_box .= '<a class="donations-button donations-button-bitcoin" onclick="window.open(this.href, \'Bitcoin\',
\'left=60,top=60,width=400,height=400,toolbar=1,resizable=0\'); return false;" href="http://chart.apis.google.com/chart?cht=qr&chs=300x300&chl='. $bitcoin . '&chld=H|0"> <span class="entypo"> &#59408; </span> Donate Bitcoins to '.$bitcoin.'</a>';
	$donations_box .= '</div>';

}

$header = <<<HTML
<div class="elgg-head homepage clearfix">
	$title
	$signup_form
	$donations_box

</div>
HTML;

$content .= elgg_trigger_plugin_hook('output-extend', 'index');

$params = array(	'content'=> $content, 
					'header'=> $header,
					'filter' => false
					);

$body = elgg_view_layout('one_column', $params);

$class = 'index ';

if(!elgg_get_plugin_setting('style','minds'))
	elgg_set_plugin_setting('style', 'fat', 'minds');

$class .= elgg_get_plugin_setting('style','minds') == 'fat' ? 'carousel-fat' : 'carousel-thin';

echo elgg_view_page('', $body, 'default', array('class'=>$class));
