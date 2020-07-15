<?php
/**
 * Plugin Name: VueReservation
 * Description: Reservations Plugin built by Austin Coleman.
 */
 
 
 //Register Scripts to use 
//Register scripts to use
function func_load_vuescripts() {
    wp_register_script('wpvue_vuejs', 'https://cdn.jsdelivr.net/npm/vue/dist/vue.js');
    // wp_register_script('rv_vue', 'http://localhost:8080');
    wp_register_script('my_vuecode', plugin_dir_url( __FILE__ ).'vuecode.js', 'wpvue_vuejs', true );
}
add_action('wp_enqueue_scripts', 'func_load_vuescripts');
 
 //Return string for shortcode
function func_wp_vue(){
  //Add Vue.js
  wp_enqueue_script('wpvue_vuejs');
  //Add my code to it
  wp_enqueue_script('my_vuecode');
  
//   wp_enqueue_script('rv_vue');

  //Build String
  $str= "<div id='divWpVue'>"
    ."Message from Vue: {{ message }}"
    ."</div>";

  //Return to display
  return $str;
} // end function

add_shortcode( 'wpvue', 'func_wp_vue' );
?>