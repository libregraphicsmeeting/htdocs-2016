<?php 

// Code from: 
// http://wordpress.stackexchange.com/questions/212212/how-to-create-a-form-button-that-executes-a-function/
// kindly provided by Jesse Graupmann - https://github.com/jgraup

add_action('init', function () {

    // Register AJAX handlers

    add_action('wp_ajax_set_pending_item_state', 'set_pending_item_state');
    add_action('wp_ajax_nopriv_set_pending_item_state', 'set_pending_item_state');

    // AJAX handler (PRIV / NO PRIV)

    function set_pending_item_state()
    {
        if( empty ($_POST['action']) || $_POST['action'] !== 'set_pending_item_state') {
            if (!empty ($fail_message)) {
                wp_send_json_error(array(
                    'message' => "Sorry!"
                )); // die
            }
        }

        $id = $_POST['id'];
        $state = $_POST['state'];
        
        if ( !empty($state) ) {
        
        		wp_set_object_terms( 
        		  $id, // ID of the selected talk, submitted by form
        		  $state, // term: "accepted", submitted by form
        		  'talk-status', // $taxonomy, 
        		  false // $append 
        		);
        
        } //

        wp_send_json_success(array(
            'action' => $_POST['action'],
            'message' => 'State Was Set To ' . $state,
            'state' => $state,
            'ID' => $id
        )); // die
    }
    
});

