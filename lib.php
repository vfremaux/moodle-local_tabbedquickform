<?php


set_include_path(get_include_path().PATH_SEPARATOR.$CFG->dirroot.'/lib/pear');
require_once 'HTML/QuickForm.php';
require_once 'HTML/QuickForm/DHTMLRulesTableless.php';
require_once 'HTML/QuickForm/Renderer/Tableless.php';
require_once $CFG->dirroot.'/local/tabbedquickform/QuickForm_Extensions/MoodleForm_Tabbed_Renderer.php';
$GLOBALS['_HTML_QuickForm_default_renderer'] = new MoodleQuickForm_Tabbed_Renderer();