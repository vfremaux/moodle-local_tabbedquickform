<?php

if ($hassiteconfig) { 
    // Needs this condition or there is error on login page.
    $settings = new admin_settingpage('local_tabbedquickform', get_string('pluginname', 'local_tabbedquickform'));
    $ADMIN->add('localplugins', $settings);

    $settings->add(new admin_setting_configcheckbox('local_tabbedquickform/enable', get_string('localtabbedquickformenable', 'local_tabbedquickform'), get_string('localtabbedquickformenabledesc', 'local_tabbedquickform'), 0));

    $options = array(0 => get_string('simple', 'local_tabbedquickform'), 1 => get_string('complete', 'local_tabbedquickform'));
    $settings->add(new admin_setting_configselect('local_tabbedquickform/defaultmode', get_string('localtabbedquickformdefaultmode', 'local_tabbedquickform'), get_string('localtabbedquickformdefaultmodedesc', 'local_tabbedquickform'), 0, $options));
}
