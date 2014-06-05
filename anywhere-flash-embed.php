<?php
/*
Plugin Name:  Anywhere Flash Embed
Plugin URI: http://www.skmukhiya.com.np
Description: Helps to embed flash to post, page, sidebar anywhere to make your site lively. For details please email, itsmeskm99@gmail.com
Version: 1.0
Author: Suresh KUMAR Mukhiya
Author URI: https://www.odesk.com/users/~0182e0779315e50896
Tags: Flash Embed, Flash file, Embed Flash to Post
*/

//hooks to load the plugin
add_action("plugins_loaded", "afe_setup_globals", 1);

//Adding admin menu
add_action("admin_menu", "afe_setup_globals", 1);

add_action("admin_menu","afe_help_page");

if(!function_exists("afe_help_page")){
	function afe_help_page()
	{
	       add_options_page( 'Anywhere Flash Embed',
               'Anywhere Flash Embed', 'manage_options',
               'afe_help_page', 'afe_help_page_layout' );
	}
}

/**
 * AFE function to initiate the global settings for the flash object
 * @param null
 * @return void
**/
if(!function_exists('afe_setup_globals'))
{
function afe_setup_globals($atts){
	global $afe;
	
	$afe = array(
		"css_class" => "afe-flash",
		"css_id" => "afe-swf-",
		"count" => 1,
		"swfs" => array()
	);
	$afe = (object)$afe;
	
}	
}

/**
 * AFE function to initiate the help page
 * @param null
 * @return void
**/
if(!function_exists("afe_help_page_layout")){
	function afe_help_page_layout()
	{
		?>
		<div class="postbox">
							
								<h3><span>Anywhere Flash Embed Help page</span></h3>
								<div class="inside">
									<ul>
										<li>
											Upload the anywhere-flash-embed page to plugin directory.
										</li>
										<li>Go to plugin dashboard and click on activate.</li>
										<li>To embed flash anywhere in page or post just post in editor something in the following format.   
  
<code>[swf src=\"http://www.example.com/my-flash-file.swf\" width=300 height=100]</code> </li>
									</ul>
								</div> <!-- .inside -->
							
							</div>
		<?php
	}
}

if(!function_exists('afe_control_options'))
{
	function afe_control_options()
	{
		echo "hellp";
	}
}

/**
 * AFE function to initiate the flash object
 * @param null
 * @return void
**/
function afe_init(){
	wp_enqueue_script("swfobject");
}
add_action("init", "afe_init");

/**
 * AFE function hook into footer
 * @param null
 * @return void
**/
function afe_wp_footer(){
	global $afe;
	echo "<script type=\"text/javascript\">\n";
	echo "//<![CDATA[\n";
	foreach($afe->swfs as $swf){
		echo "swfobject.embedSWF(\"" . $swf->src . "\", \"" . $swf->replace_id . "\", \"" . $swf->width . "\", \"" . $swf->height . "\", \"" . $swf->version . "\", false, " . ( $swf->flashvars ? json_encode($swf->flashvars) : "false" ) . ", " . ( $swf->params ? json_encode($swf->params) : "false" ) . ");\n";
	}
	echo "//]]>\n";
	echo "</script>";
}
add_action("wp_footer", "afe_wp_footer");


/**
 * AFE function to create the shortcode
 * @param null
 * @return void
**/
if(!function_exists('afe_shortcode')){
	function afe_shortcode($atts, $content = false){
	global $afe;
	
	$args = shortcode_atts(array(
		"src" => false,
		"width" => false,
		"height" => false,
		"flashvars" => false,
		"params" => false,
		"version" => "9"
	), $atts);
	
	extract($args);
	
	if(!$src || !$width || !$height)
		return "";
	
	if($params) $args["params"] = wp_parse_args(html_entity_decode($params), false);
	if($flashvars) $args["flashvars"] = wp_parse_args(html_entity_decode($flashvars), false);
	
	$id = $afe->css_id . $afe->count;
	$afe->swfs[] = (object)array_merge($args, array(
		"replace_id" => $id
	));
	
	$afe->count++;
	
	return "<div id=\"" . $id . "\" class=\"" . $afe->css_class . "\">" . ($content ? $content : "<!-- -->") . "</div>";
}
}
add_shortcode("swf", "afe_shortcode");
?>