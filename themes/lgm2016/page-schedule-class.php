<?php

class LGMPageSchedule {
	/**
     * https://developer.wordpress.org/reference/functions/the_content/
     */
    function getContent($content = null, $more_link_text = null, $strip_teaser = false) {
        if (isset($content)) {
            $result = $content;
        } else {
            $result = get_the_content( $more_link_text, $strip_teaser );
        }
		$result = apply_filters( 'the_content', $result );
		return str_replace( ']]>', ']]&gt;', $result );
    }

    function getTalk($post_id) {
        $result = [];
        $post = get_post($post_id, ARRAY_A);
        // echo("<pre>".print_r($post, 1)."</pre>");
        if ($post) {
            $result = [
                'id' => $post_id,
                'title' => $post['post_title'],
                'content' => $this->getContent($post['post_content']),
                'url' => ($post['post_name'] ? get_site_url().'/talk/'.$post['post_name'] : $post['guid']),
                'timestamp' => get_the_modified_time(),
            ];
            $result += $this->getMetaFields($post['ID']);
        }
        return $result;
    }
    function getQueryAll() {
        if (is_user_logged_in() || (false === ($custom_query = get_transient('page_schedule_grid')))) {
            $custom_query = new WP_Query(array(
              'post_type' => 'talk',
              'post_status' => 'any',
              'posts_per_page' => -1,
              // 'orderby' => 'date',
              'orderby' => 'meta_value',
              'meta_key' => '_mem_start_date',
              'order' => 'ASC',
            ));
                            
            set_transient('lgm16_schedule_grid', $custom_query, 12 * HOUR_IN_SECONDS);
        } 
        return $custom_query;
    }

    function getMetaFields($id) {
        $startDate = '';
        $startDay = '';
        $weekday = '';
        $startTime = '';
        $duration = 0;
        $startMeta = get_post_meta( $id, '_mem_start_date', true);
        $startDatetime = '';
        $endDatetime = '';
        if ($startMeta) {
            $startDatetime = $startMeta;
            $startDate = new DateTime($startMeta);
            $startTime = $startDate->format('H:i');
            $startDay = $startDate->format('d');
            $weekday = $startDate->format('l');
            $endMeta = get_post_meta( $id, '_mem_end_date', true);
            $duration = 20;
            if ($endMeta) {
                $endDatetime = $endMeta;
                $endDate = new DateTime($endMeta);
                $diff = $startDate->diff($endDate);
                // echo("<pre>".print_r($diff, 1)."</pre>");
                $duration = ($diff->h * 60) + $diff->i;
            } else {
                $endDatetime = $startDate->add(new DateInterval('PT20M'))->format('Y-m-d H:i:s');
            }
        }

        $result = [
            'firstname' => get_post_meta($id, 'lgm_speaker_firstname', true),
            'lastname' => get_post_meta($id, 'lgm_speaker_lastname', true),
            'speaker-additional' => get_post_meta($id, 'lgm_additional_speakers', true),
            'day' => $startDay,
            'weekday' => $weekday,
            'time' => $startTime,
            'duration' => $duration,
            'start' => $startDatetime,
            'end' => $endDatetime,
        ];
        $result['speakers'] = implode(', ', array_filter([$result['firstname'].' '.$result['lastname'], $result['speaker-additional']]));
        return $result;
    }

    /**
     * get each talk, sorted by time
     */
    function next() {
        $result = null;
        static $query = null;
        if (is_null($query)) {
            $query = $this->getQueryAll();
        }
        if ($query->have_posts()) {
            // TODO: repeat until we get a valid startMeta but not do it more than there are posts
            $query->the_post();
            $id = get_the_ID();
            
            $result = [
                'id' => $id,
                'title' => get_the_title(),
                'content' => $this->getContent(),
                'url' => get_permalink(),
                'timestamp' => date('Y-m-d H:i:s', get_the_modified_time('U')),
            ];

            $result += $this->getMetaFields($id);

        }
        return $result;
    }
}
