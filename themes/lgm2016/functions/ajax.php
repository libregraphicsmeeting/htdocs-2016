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

        }

        wp_send_json_success(array(
            'action' => $_POST['action'],
            'message' => 'State Was Set To ' . $state,
            'state' => $state,
            'ID' => $id
        )); // die
    }

    add_action('wp_ajax_save_schedule_builder_event', 'save_schedule_builder_event');
    add_action('wp_ajax_nopriv_save_schedule_builder_event', 'save_schedule_builder_event');

    /**
     * store the list of talks sent through ajax
     */
    function save_schedule_builder_event()
    {
        if( empty ($_POST['action']) || $_POST['action'] !== 'save_schedule_builder_event') {
            if (!empty ($fail_message)) {
                wp_send_json_error(array(
                    'message' => "Sorry!"
                )); // die
            }
        }

        $data = $_POST['data'];

        if (!empty($data) && is_array($data)) {

                 /**
                  * post_id: the id the talk
                  * for all talks
                  * meta_key: '_mem_start_date' // cf. documentation on github
                  * for talks with different lentght also store:
                  * meta_key: '_mem_end_date' // cf. documentation on github
                  */
                 foreach ($data as $item) {
                     if (is_array($item) && array_key_exists('post-id', $item)) {
                         $dateStart = null;
                         if (array_key_exists('start', $item)) {
                             // if we did not change the duration we have to check if an end is defined
                             // and if it is the case set the duration so that the end also gets moved
                             if (!array_key_exists('duration', $item)) {
                                 $end = get_post_meta($item['post-id'], '_mem_end_date', true);
                                 if ($end) {
                                     $dateEndObject = new DateTime($end);
                                     $start = get_post_meta($item['post-id'], '_mem_start_date', true);
                                     $dateStartObject = new DateTime($start);
                                     $diff = $dateStartObject->diff($dateEndObject);
                                     $item['duration'] = $diff->i;
                                 }
                             }

                             $dateStartObject = new DateTime($item['start']);
                             $dateStart = $dateStartObject->format('Y-m-d H:i');
                             update_post_meta($item['post-id'], '_mem_start_date', $dateStart);
                         }
                          // if duration is set, delete the end date if it's 20 minutes, or calculate
                          // and set an end date otherwise
                         if (array_key_exists('duration', $item)) {
                             if ($item['duration'] == 20) {
                                 delete_post_meta($item['post-id'], '_mem_end_date');
                             } else {
                                 if (is_null($dateStart)) {
                                     $dateStart = get_post_meta($item['post-id'], '_mem_start_date', true);
                                 }
                                 $dateObject = new DateTime($dateStart);
                                 $dateObject->add(new DateInterval('PT'.$item['duration'].'M'));
                                 $dateEnd = $dateObject->format('Y-m-d H:i');
                                 update_post_meta($item['post-id'], '_mem_end_date', $dateEnd);
                             }
                         }
                     }
                 }

                wp_send_json_success(array(
                    'action' => $_POST['action'],
                    // 'message' => 'update disabled '.print_r($_POST, 1),
                    // 'message' => 'update disabled '.print_r($end, 1),
                    'message' => 'Saved '.print_r($dateStart, 1),
                    'state' => true,
                ));
        }

        wp_send_json_success(array(
            'action' => $_POST['action'],
            'message' => 'No items to be saved',
            'state' => true,
        )); // die
    }

    add_action('wp_ajax_lgm_get_talk_detail', 'lgm_get_talk_detail');
    add_action('wp_ajax_nopriv_lgm_get_talk_detail', 'lgm_get_talk_detail');


    function lgm_get_talk_detail() {
        $result = [];
        if (array_key_exists('post-id', $_POST) && $_POST['post-id']) {
            include(get_stylesheet_directory().'/page-schedule-class.php');
            $pageSchedule = new LGMPageSchedule();
            if ($talk = $pageSchedule->getTalk($_POST['post-id'])) {
                $result = [];
                $result[] = "<div class=\"talk\">";
                $result[] = "<h2>".$talk['title']."</h2>";
                $result[] = "<h3>".$talk['speakers']."</h3>";
                $result[] = "<p class=\"time\">".sprintf("%s (%s')", $talk['time'], $talk['duration'])."</p>";
                $result[] = '<p class="details-link"><a href="'.$talk['url'].'">Details...</a></p>';
                $result[] = "</div>";
            }
        }
        echo (empty($result) ? "<p>Talk not found.</p>" : implode("\n", $result));
        wp_die();
    }

});
