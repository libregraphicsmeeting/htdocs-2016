## Plugins

For the case where we run a test version of the site in a localhost environment, the Formidable plugin needs some changes to avoid the need of authentication:

change 1:

in the file formidable/pro/classes/controllers/FrmUpdatesController.php, go to line 78, and add one line:

```
function pro_is_authorized($force_check=false){
	return true; // <- must be added
```


change 2:

in the file formidable/pro/classes/controllers/FrmProEddController.php, add: 

```
function pro_is_authorized() {
	return true;
```

## Themes

- in `wp-config.php` set 
      `define('WP_DEBUG', true);`
  or compile the css files with `grunt`...

