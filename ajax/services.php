<?php

require('../../../config.php');

require_login();
if (!is_siteadmin()) {
    exit;
}

$action = optional_param('what', '', PARAM_TEXT);

if ($action == 'maskformitem') {
    $elementid = required_param('fitemid', PARAM_TEXT);
    $bodyid = required_param('bodyid', PARAM_TEXT);
    $bodyid = str_replace('-', '_', $bodyid);
    $maskkey = 'mask_'.$bodyid.'_'.$elementid;
    set_config($maskkey, 1, 'local_tabbedquickform');
    echo 'masked';
}
if ($action == 'unmaskformitem') {
    $elementid = required_param('fitemid', PARAM_TEXT);
    $bodyid = required_param('bodyid', PARAM_TEXT);
    $bodyid = str_replace('-', '_', $bodyid);
    $maskkey = 'mask_'.$bodyid.'_'.$elementid;
    set_config($maskkey, null, 'local_tabbedquickform');
    echo 'unmasked';
}