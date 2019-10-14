<?php

/**
 * Plugin Name: Chek Creative Custom Login
 * Description: Customizes the WordPress login page with Partner + Chek Creative branding.
 * Version: 1.0
 * Author: Chek Creative
 * Author URI: https://chekcreative.com
 * License: GPL3
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 * 
 * Chek Creative Custom Login is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * any later version.
 *  
 * Chek Creative Custom Login is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with Chek Creative Custom Login. If not, see http://www.gnu.org/licenses/gpl-3.0.html.
 * 
 */

function custom_login_logo() {
	$custom_logo_id = get_theme_mod( 'custom_logo' );
	$image = wp_get_attachment_image_src( $custom_logo_id , 'full' ); ?>
	<link href="https://fonts.googleapis.com/css?family=Montserrat&display=swap" rel="stylesheet">
	<style type="text/css">
		body {
			font-family: 'Montserrat', sans-serif !important;
			background: #e5e5e5 !important;
		}
        #login h1 a, .login h1 a {
            background: url('<?php echo $image[0]; ?>');
			background-size: 50%;
			background-repeat: no-repeat;
			background-position: top center;
        	padding-bottom: 40px;
			margin: 0 auto;
			width: 100%;
			height: 50px;
        }

		#login_error {
			border-left-color: #c32a68 !important;
		}

		.message {
			border-left-color: #3ca087 !important;
		}

		a:hover {
			color: #c32a68 !important;
		}
	</style>
<?php }

function custom_loginlogo_url($url) {
	// SET THE CLIENT'S URL HERE
    return home_url();
}

function chek_creative_login_footer() {
	echo '<div style="display: flex;
        flex-direction: column;
        align-items: center;">';
	echo '<div id="chek-creative-footer" style="text-align: center; 
        display: flex;
        padding: 0px 20px;
        align-items: center;
        margin-bottom: 40px;
        width: 280px;">';
    echo '<img height="14px" src="' 
        . esc_url( plugins_url( 'cc-rocket.png', __FILE__ ) ) 
        . '">';
    echo '<p style="margin-left: 8px; white-space: nowrap;">';
    echo 'Powered by';
    echo '<a href="https://chekcreative.com"
        target="_blank" 
        style="text-decoration: none; color: #555d66;">
            Chek Creative
        </a>';
    echo '</p>';
	echo '</div>';
	echo '</div>';
 }
add_filter( 'login_headerurl', 'custom_loginlogo_url' );
add_action( 'login_enqueue_scripts', 'custom_login_logo' );
add_action( 'login_footer', 'chek_creative_login_footer' );

function my_added_login_field(){
    //Output your HTML
    ?>
    <p>
        <label for="cc-human-check">Prove you're human (4 times eleven)<br>
        <input type="text" tabindex="20" size="20" value="" class="input" id="cc-human-check" name="cc-human-check"></label>
    </p>
<?php
}
add_action('login_form','my_added_login_field');

function my_custom_authenticate( $user, $username, $password ){
    $my_value = $_POST['cc-human-check'];
    $user = get_user_by('login', $username );

    if($user && (empty($my_value) || $my_value != 44 || $my_value != "44")){
        remove_action('authenticate', 'wp_authenticate_username_password', 20);
        remove_action('authenticate', 'wp_authenticate_email_password', 20); 

        return new WP_Error( 'denied', __("Human verification failed. Check your math.") );
    }

    return null;
}
add_filter( 'authenticate', 'my_custom_authenticate', 10, 3 );
