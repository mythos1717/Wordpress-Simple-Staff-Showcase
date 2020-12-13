<?php
/*
Plugin Name: Simple Staff Showcase
Plugin URI: 
Description: Manage staff bios, positions, and display order of Elliot, Robinson & Company Staff
Version: 1.0
Author: Cassie Witt
Author URI: http://cassiewitt.com
*/

defined( 'ABSPATH' ) or die( 'Plugin file cannot be accessed directly.' );

add_action( 'init', 'create_staff_member' );


function create_staff_member() {
    register_post_type( 'staff_member',
        array(
            'labels' => array(
                'name' => 'Staff Members',
                'singular_name' => 'Staff Member',
                'add_new' => 'Add New',
                'add_new_item' => 'Add New Staff Member',
                'edit' => 'Edit',
                'edit_item' => 'Edit Staff Member',
                'new_item' => 'New Staff Member',
                'view' => 'View',
                'view_item' => 'View Staff Member',
                'search_items' => 'Search Staff Member',
                'not_found' => 'No Staff Members found',
                'not_found_in_trash' => 'No Staff Members found in Trash',
                'parent' => 'Parent Staff Member'
            ),
 
            'public' => true,
            'menu_position' => 15,
            'supports' => array( 'title', 'editor', 'comments', 'thumbnail', 'custom-fields' ),
            'taxonomies' => array( ),
            'menu_icon' => 'dashicons-businessman',
            'has_archive' => true
        )
    );
}

add_action( 'admin_init', 'my_admin' );

function my_admin() {
    add_meta_box( 'staff_member_details_meta_box',
        'Staff Member Details',
        'display_staff_member_details_meta_box',
        'staff_member', 'normal', 'high'
    );
}

function display_staff_member_details_meta_box( $post ) {
    // Retrieve staff_position, staff_work_number, staff_fax_number, and staff_email based on id
    $staff_position = get_post_meta( $post->ID, 'staff_position', true );
	 $staff_work_number = get_post_meta( $post->ID, 'staff_work_number', true );
	  $staff_fax_number = get_post_meta( $post->ID, 'staff_fax_number', true );
    $staff_email = get_post_meta( $post->ID, 'staff_email', true );
	$staff_sort_order = get_post_meta( $post->ID, 'staff_sort_order', true );
	$staff_sort_order = intval($staff_sort_order);
    ?>
			<table>
				<tr>
					<td valign="top">Position</td>
					<td>
            <input name="position" style="width: 100%;" name="position" id="position" value="<?php echo $staff_position; ?>"/></td>
				</tr>
				<tr>
					<td valign="top">Work Number:</td>
					<td><input name="number" id="number" style="width: 100%;" value="<?php echo $staff_work_number; ?>"/></td>
				</tr>
				<tr>
					<td valign="top">Fax Number:</td>
					<td><input name="fax" id="fax" style="width: 100%;" value="<?php echo $staff_fax_number; ?>"/></td>
				</tr>
				<tr>
					<td valign="top">Email:</td>
					<td>
						<input name="email" id="email" style="width: 100%;" value="<?php echo $staff_email; ?>"/></td>
				</tr>
				<tr>
					<td valign="top">Sort Order:</td>
					<td>
						<input name="sort" id="sort" style="width: 100%;" type="number" value="<?php echo $staff_sort_order; ?>"/></td>
				</tr>
			</table>
<?php
		}
		add_action( 'save_post', 'add_staff_member_detail_fields', 10, 2 );
		
		function add_staff_member_detail_fields( $post_id, $staff_member ) {
    // Check post type for movie reviews
    if ( $_POST['post_type'] == 'staff_member' ) {

        // Store data in post meta table if present in post data
         if ( isset( $_POST['position'] ) && $_POST['position'] != '' ) {
            update_post_meta( $post_id, 'staff_position', $_POST['position'] );
        }
        if ( isset( $_POST['number'] ) && $_POST['number'] != '' ) {
            update_post_meta( $post_id, 'staff_work_number', $_POST['number'] );
        }
		 if ( isset( $_POST['fax'] ) && $_POST['fax'] != '' ) {
            update_post_meta( $post_id, 'staff_fax_number', $_POST['fax'] );
        }
		if ( isset( $_POST['email'] ) && $_POST['email'] != '' ) {
            update_post_meta( $post_id, 'staff_email', $_POST['email'] );
        }
		if ( isset( $_POST['sort'] ) && $_POST['sort'] != '' ) {
            update_post_meta( $post_id, 'staff_sort_order', $_POST['sort'] );
        }
	}
}

function showstaff($atts, $content = null) {
	//add in code here for ascending/descending
    extract(shortcode_atts(array(
        "order" => 'ASC'
    ), $atts));
	//fix shortcode to work better
	
	//get all staff details
	global $wpdb;
	$staff = $wpdb->get_results("SELECT ID,post_title FROM wp_posts WHERE post_status = 'publish' AND post_type='staff_member'");
	//var_dump($staff);
	?>
	<div class="staff-wrapper">
	<div class="staff-preview-wrapper">
						<div id="staff-preview">
			<?php

if(!empty($staff)){
	for($i=0; $i < count($staff); $i++){ 
		$staff_data = get_post($staff[$i]->ID); 
		?>
		<div class="staff-preview-details col-sm-3">
		<div id="staff-preview-image" class="shadow_stroke">
		<?php
	if (has_post_thumbnail( $staff[$i]->ID )) {
			echo get_the_post_thumbnail($staff[$i]->ID);
		} else { 
			echo 'NO IMAGE!!!!';
		}
		?>
		</div>
		<div id="staff-preview-name">
		<a  onclick="getStaffDetails('member-<?php echo $staff_data->ID; ?>'); " href="#staff-details">
		<?php
		echo $staff_data->post_title.'<br />';
		?></a>
		</div>
		</div>
		<?php
	}
} else {
}



?>
<div style="clear:both"></div>
</div>
</div>

<div id="staff-details">
<a name="staff-details"></a>
<?php
if(!empty($staff)){
	for($i=0; $i < count($staff); $i++){ 
		$staff_data = get_post($staff[$i]->ID);
		?>
		<div class="staff-member-detail" id="member-<?php echo $staff_data->ID;?>" >
		
		<div id="staff-image" class="col-sm-4">
		<?php
		if (has_post_thumbnail( $staff[$i]->ID )) {
			echo get_the_post_thumbnail($staff[$i]->ID);
		} else { 
			echo 'NO IMAGE!!!!';
		}
		?>
		</div>
		<div id="staff-meta" class="col-sm-8">
		<h3> <?php echo $staff_data->post_title.'<br />';?></h3>
		<p><span class="info-title">Position:</span><?php echo $staff_data->staff_position;?></p> 
		<p><span class="info-title">Phone:</span>     <?php echo $staff_data->staff_work_number;?></p>
		<p><span class="info-title">Fax:</span>     <?php echo $staff_data->staff_fax_number;?></p>
		<p><a class="button" href="mailto:<?php echo $staff_data->staff_email;?>">Email Me!</a></p>
		</div>
		<div id="staff-bio" class="col-sm-8">
		<?php
		echo $staff_data->post_content.'<br /><br /><br />';
		?>
		</div>
		</div>
		<?php
	}
}
else{
}

		?>
</div>
</div>
<?php
    return $content;
}

add_shortcode("showstaff", "showstaff");

function staffmanager_scripts_basic()
{
    wp_register_script( 'staffmanager-functions', plugins_url( '/js/staffmanager-functions.js', __FILE__ ), array('jquery') );
	  wp_enqueue_script( 'staffmanager-functions' );
}
add_action( 'wp_enqueue_scripts', 'staffmanager_scripts_basic' );

function staffmanager_styles()
{
    wp_register_style( 'staffmanager-style', plugins_url( '/css/staffmanager.css', __FILE__ ), array(), '20120208', 'all' );
	   wp_enqueue_style( 'staffmanager-style' );
	    wp_register_style( 'bootstrap-style', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css');
	   wp_enqueue_style( 'bootstrap-style' );
}
add_action( 'wp_enqueue_scripts', 'staffmanager_styles' );
?>