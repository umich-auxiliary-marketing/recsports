<?
// The custom.php file acts as an intermediary controller in order to play
// nicely with the other logic for module selection.

$filename = "page-module-templates/custom-" . $meta[$pcb . 'custom_module_to_display'][0] . '.php';
include(locate_template($filename, false, false));
?>
