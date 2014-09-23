<?php
/*
Plugin Name: Button Maker
Plugin URI: 
Description: Plugin for Custom Buttons.
Author: Pluginhandy
Author URI: http://pluginhandy.com/
Version: 1.1
Text Domain: 
License: GPL version 2 or later - 
*/

$siteurl = get_option('siteurl');
define('PROF_FOLDER', dirname(plugin_basename(__FILE__)));
define('PROF_URL', $siteurl.'/wp-content/plugins/' . PROF_FOLDER);
define('PROF_FILE_PATH', dirname(__FILE__));
define('PROF_DIR_NAME', basename(PROF_FILE_PATH));
define('ABSPATH', getcwd());

wp_enqueue_style('my_css_dsslider', '/wp-content/plugins/' . PROF_FOLDER . '/style.css');
wp_register_script( 'jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js');
wp_enqueue_script( 'jscolor', '/wp-content/plugins/' . PROF_FOLDER . '/jscolor/jscolor.js');

global $wpdb;
$comp_table_prefix=$wpdb->prefix;
define('PROF_TABLE_PREFIX', $comp_table_prefix);
$btntable = PROF_TABLE_PREFIX."custom_btns";

function demo_install()
{
    global $wpdb;
    $table = PROF_TABLE_PREFIX."custom_btns";
    $structure = "CREATE TABLE IF NOT EXISTS $table (
		 `id` int(9) NOT NULL AUTO_INCREMENT,
		 `text` varchar(255) NOT NULL,
		 `size` varchar(255) NOT NULL,
		 `color` varchar(255) NOT NULL,
		 `height` varchar(255) NOT NULL,
		 `width` varchar(255) NOT NULL,
		 `background` varchar(255) NOT NULL,
                 `hover` varchar(255) NOT NULL,
		 `shape` varchar(255) NOT NULL,
		 `url` text NOT NULL,
		 `clicks` varchar(255) NOT NULL,
		 `impressions` varchar(255) NOT NULL,
		 `html` text NOT NULL,
		 `show` int(11) NOT NULL,
		 `order` int(11) NOT NULL,
		 `created_at` varchar(255) NOT NULL,
		 UNIQUE KEY `id` (`id`)
	);";
    $wpdb->query($structure);
    
    $table2 = PROF_TABLE_PREFIX."select_post";
    $structure2 = "CREATE TABLE IF NOT EXISTS $table2 (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `post` int(11) NOT NULL,
        PRIMARY KEY (`id`)
    )";
    $wpdb->query($structure2);
}

/* Ajax request */
add_action('wp_ajax_nopriv_map_post_type_show','map_post_type_show');
add_action('wp_ajax_map_post_type_show','map_post_type_show');
function map_post_type_show() {
    $btntable = PROF_TABLE_PREFIX."custom_btns";
    if(isset($_POST['btn_id'])) {
        global $wpdb;
        
        //echo "<pre>";print_r($_POST);die();
        $id = $_POST['btn_id'];
        /* update clicks */
        $wpdb->query("UPDATE `$btntable` set clicks=(clicks+1) where id=$id");
        
        $getbtn = $wpdb->get_results("Select * from `$btntable` where id=$id");
        if($getbtn != NULL) {
            $url = $getbtn[0]->url;
            $result['output'] = 'yes';
            $result['url'] = $url;
        } else {
            $result['output'] = 'no';
        }
        echo json_encode($result);
        exit;
    }
    
    if(isset($_POST['btns'])) {
        global $wpdb;
        //echo "<pre>";print_r($_POST);die();
        $btns = $_POST['btns'];
        $getbtns = explode(',',$btns);
        
        foreach($getbtns as $btnid) {
            /* update impressions */
            $wpdb->query("UPDATE `$btntable` set impressions=(impressions+1) where id=$btnid");
        }
        
        $result['output'] = 'updated';
        echo json_encode($result);
        exit;
    }
    
}


function my_plugin_deactivate()
{
    global $wpdb;
    $table = PROF_TABLE_PREFIX."custom_btns";
    $structure = "drop table if exists $table";
    $wpdb->query($structure);
    
    $table2 = PROF_TABLE_PREFIX."select_post";
    $table_del = "DROP TABLE IF EXISTS $table2";
    $wpdb->query($table_del);
}

function my_plugin_activate() {
  add_option( 'Activated_Plugin', 'Plugin-Slug' );
  /* activation code here */
}
register_activation_hook( __FILE__, 'my_plugin_activate' );
register_deactivation_hook(__FILE__ , 'my_plugin_deactivate' );

function load_plugin() {
    if ( is_admin() && get_option( 'Activated_Plugin' ) == 'Plugin-Slug' ) {
        delete_option( 'Activated_Plugin' );
        /* do stuff once right after activation */
        // example: add_action( 'init', 'my_init_function' );
    }
}
add_action( 'admin_init', 'load_plugin' );
register_activation_hook(__FILE__,'demo_install');


function Form_admin_menu() {
    include('data_show.php');
}
function Form_select_post() {
    include('posttype.php');
}
function Form_show_admin_menu() {
    include('form.php');
}
function Form_admin_actions() {
    add_menu_page("My Buttons", "My Buttons", 1,__FILE__ , "Form_admin_menu");
    add_submenu_page(__FILE__,'Form','Add New Button','8','form','Form_show_admin_menu');
    //add_submenu_page(__FILE__,'Form','Select Post','8','posttype','Form_select_post');
}
add_action('admin_menu', 'Form_admin_actions');
  

function Form() {
    include('form.php');
}
add_shortcode( 'form1', 'Form' );

if(isset($_POST['save_btn'])) {
    //$tables = $wpdb->get_results("Select * from `$btntable`");
    //echo "<pre>";print_r($tables);print_r($_POST);exit;
    $text = $_POST['btn_text'];
    $size = $_POST['font_size'];
    $color = $_POST['font_color'];
    $height = $_POST['height'];
    $width = $_POST['width'];
    $backcolor = $_POST['back_color'];
    $hovercolor = $_POST['hover_color'];
    $shape = $_POST['shape'];
    $url = $_POST['url'];
    $html = trim($_POST['btn_html']);
    $current_time = time();
    $wpdb->query("INSERT INTO `$btntable`(`text`, `size`, `color`, `height`, `width`, `background`, `hover`, `shape`, `url`, `clicks`, `impressions`, `html`, `created_at`) VALUES('$text','$size','$color','$height','$width','$backcolor','$hovercolor','$shape','$url','0','0','$html','$current_time')");
    $nextpage = site_url().'/wp-admin/admin.php?page=button-maker/buttons.php';
    echo "<script type='text/javascript'>document.location.href='$nextpage';</script>";
    exit;
}

if(isset($_POST['edit_btn'])) {
    //echo "<pre>";print_r($_POST);exit;
    $id = $_POST['btnid'];
    $text = $_POST['btn_text'];
    $size = $_POST['font_size'];
    $color = $_POST['font_color'];
    $height = $_POST['height'];
    $width = $_POST['width'];
    $backcolor = $_POST['back_color'];
    $hovercolor = $_POST['hover_color'];
    $shape = $_POST['shape'];
    $url = $_POST['url'];
    $html = trim($_POST['btn_html']);
    $wpdb->query("UPDATE `$btntable` set text='$text',size='$size',color='$color',height='$height',width='$width',background='$backcolor',hover='$hovercolor',url='$url',shape='$shape',html='$html' where id=$id");
    $nextpage = site_url().'/wp-admin/admin.php?page=button-maker/buttons.php';
    echo "<script type='text/javascript'>document.location.href='$nextpage';</script>";
    exit;
}

if(isset($_POST['submit_post'])) {
    //echo "<pre>";print_r($_POST);exit;
    $postid = $_POST['mypost_id'];
    $results = $wpdb->get_results("Select * from `wp_select_post`");
    if($results == NULL) {
        $wpdb->query("INSERT INTO `wp_select_post`(`post`) VALUES ('$postid')");
    } else {
        //echo "<pre>";print_r($results);exit;
        $id = $results[0]->id;
        $wpdb->query("UPDATE `wp_select_post` set post='$postid' where id=$id");
    }
    $nextpage = site_url().'/wp-admin/admin.php?page=posttype';
    echo "<script type='text/javascript'>document.location.href='$nextpage';</script>";
    exit;
}

if(isset($_POST['show_btns'])) {
    //echo "<pre>";print_r($_POST);exit;
    $isshow = $_POST['is_show'];
	$order = $_POST['order'];
	$wpdb->query("UPDATE `$btntable` SET `show` = '0' WHERE `show` =1;");
    foreach($isshow as $show) {
		$wpdb->query("UPDATE `$btntable` SET `show` = '1' WHERE `id` =$show;");
	}
	if($order != NULL) {
		foreach($order as $key=>$value) {
			$wpdb->query("UPDATE `$btntable` SET `order` = '$value' WHERE `id` =$key;");
		}
	}
    $nextpage = site_url().'/wp-admin/admin.php?page=button-maker/buttons.php';
    echo "<script type='text/javascript'>document.location.href='$nextpage';</script>";
    exit;
}

/* create shortcode */
add_shortcode("custom_buttons_shortcode","custom_buttons_function");
function custom_buttons_function($atts)
{
    ob_start();
    //echo "Yes, its working fine"; // this will not print out
	
	global $wpdb;
	$btntable = PROF_TABLE_PREFIX."custom_btns";
	/* get all custom buttons */
	$getbtns = $wpdb->get_results("Select * from `$btntable` WHERE `show`=1 order by `order` asc");
	if($getbtns != NULL) {
		$btns = array();
		foreach($getbtns as $getbtn) {
			$btns[$getbtn->id] = $getbtn;
		}
	}
	
	/* add html for buttons */
	if($btns != NULL) {
		$msg = '<div style="float: left; width: 100%;">';
		$msg .= '<div class="" style="margin: 0px auto; width: 960px;">';
		$btnids = '';
		foreach($btns as $key=>$value) {
			$msg .= '<div style="float: left; margin-bottom: 10px; margin-right: 10px;" class="mybtn" alt="'.$value->hover.'_'.$value->background.'" id="custombtn'.$key.'">'.$value->html.'</div>';
			$btnids .= $key.',';
		}
		$btnids = substr($btnids,0,-1);
		$msg .= '</div>';
		$msg .= '</div>';
		
		/* javascript code */
		$msg .= '<script type="text/javascript">jQuery(document).ready(function(){</script>';
	}
	echo $msg;
	
    // more code
    $result = ob_get_contents(); // get everything in to $result variable
    ob_end_clean();
    return $result;
}



function myscript() {

            global $wpdb;
            $btntable = PROF_TABLE_PREFIX."custom_btns";
			/* get all custom buttons */
			$getbtns = $wpdb->get_results("Select * from `$btntable` WHERE `show`=1 order by created_at asc");
			if($getbtns != NULL) {
				$btns = '';
				foreach($getbtns as $getbtn) {
					$btns .= $getbtn->id.',';
				}
				$btns = substr($btns,0,-1);
			} ?>
        <script type="text/javascript">
        jQuery(document).ready(function(){
           jQuery.ajax({
                url:"<?php echo admin_url('admin-ajax.php'); ?>",
                type: "POST",
                dataType:"json",
                data:({
                    btns:'<?php echo $btns; ?>',
                    action:"map_post_type_show"
                }),
                success: function(data) {
                }
            });
           
           jQuery('.mybtn').click(function(){
                var btnid = this.id;
                var id = btnid.replace("custombtn","");
                jQuery.ajax({
                    url:"<?php echo admin_url('admin-ajax.php'); ?>",
                    type: "POST",
                    dataType:"json",
                    data:({
                        btn_id:id,
                        action:"map_post_type_show"
                    }),
                    success: function(data) {
                        if(data.output === 'yes') {
                            document.location.href = data.url;
                        }
                    }
                 });
           });
           
           jQuery('.mybtn').mouseover(function(){
                var alt = jQuery(this).attr('alt');
                var getcolors = alt.split('_');
                var hovercolor = getcolors[0];
                jQuery(this).find('#btnAddProfile').css('background','#'+hovercolor);
           });
           
           jQuery('.mybtn').mouseout(function(){
                var alt = jQuery(this).attr('alt');
                var getcolors = alt.split('_');
                var backcolor = getcolors[1];
                jQuery(this).find('#btnAddProfile').css('background','#'+backcolor);
           });
        });
        </script>
        <?php
}
add_action( 'wp_footer', 'myscript' ,200);