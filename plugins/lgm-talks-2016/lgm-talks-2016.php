<?php
/*
Plugin Name: LGM Talks 2016
Plugin URI: 
Description: Functionality for talk submission and management.
Version: 1.0.0
Author: LGM Infrastructure Team
Author URI: https://github.com/libregraphicsmeeting/infrastructure/
*/


/*
 *

What this plugin does:

- Registers a "talks" custom post type".
- Creates a "speaker" user role.

 */
 
 // Custom Post Types
  include_once (plugin_dir_path(__FILE__).'lgm-custom-post-types.php');
 
 
 // User Roles
 // include_once (plugin_dir_path(__FILE__).'lgm-user-roles.php');
 
