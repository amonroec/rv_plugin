<?php
/**
 * Plugin Name: VueReservation
 * Description: Reservations Plugin built by Austin Coleman.
 */
 
 //Register Scripts to use 
//Register scripts to use
function func_load_vuescripts() {
    // wp_register_script('wpvue_vuejs', 'https://cdn.jsdelivr.net/npm/vue/dist/vue.js');
    // wp_register_script('rv_vue', get_stylesheet_directory_uri().'rv_plugin/dist/build.js');
    // wp_register_script('rv_vue', plugin_dir_url(__FILE__).'rv_plugin/dist/build.js');
    // wp_register_script('rv_vue', 'https://rvselfpark.com/wp-content/plugins/rv_plugin/rv_plugin/dist/build.js', true);
    wp_register_script('rv_vue', plugin_dir_url( __FILE__ ).'rv_plugin/dist/build.js', 'wpvue_vuejs', true );
    // wp_register_script('my_vuecode2', plugin_dir_url( __FILE__ ).'rv_test/src/main.js', 'wpvue_vuejs', true );

}
add_action('wp_enqueue_scripts', 'func_load_vuescripts');
 
 //Return string for shortcode
function func_wp_vue(){
  //Add Vue.js
  // wp_enqueue_script('wpvue_vuejs');
  //Add my code to it
  // wp_enqueue_script('my_vuecode');
  // wp_enqueue_script('my_vuecode2');
  
  wp_enqueue_script('rv_vue');

  //Build String
  // $str= "<div id='vue-reservation-app'>"
  //   ."</div>";

  $str = 'Hello World';
  //Return to display
  return $str;
} // end function

// function func_reservations_table() {
//   // wp_enqueue_script('wpvue_vuejs');
//   wp_enqueue_script('rv_vue');
//   $str= "<div id='vue-reservation-table'>"
//     ."</div>";

//   //Return to display
//   return $str;
// }

add_shortcode( 'wpvue', 'func_wp_vue' );
// add_shortcode( 'reservationstable', 'func_reservations_table');
?>