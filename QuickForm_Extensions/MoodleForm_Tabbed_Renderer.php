<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * MoodleQuickForm renderer
 *
 * A renderer for MoodleQuickForm that only uses XHTML and CSS and no
 * table tags, extends PEAR class HTML_QuickForm_Renderer_Tableless
 *
 * Stylesheet is part of standard theme and should be automatically included.
 *
 * @package   core_form
 * @copyright 2007 Jamie Pratt <me@jamiep.org>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once($CFG->dirroot.'/local/tabbedquickform/compatlib.php');

class MoodleQuickForm_Tabbed_Renderer extends HTML_QuickForm_Renderer_Tableless {

    /** @var array Element template array */
    var $_elementTemplates;

    /**
     * Template used when opening a hidden fieldset
     * (i.e. a fieldset that is opened when there is no header element)
     * @var string
     */
    var $_openHiddenFieldsetTemplate = "\n\t<fieldset class=\"hidden\"><div>";

    /** @var string Header Template string */
    var $_headerTemplate =
       "\n\t\t<legend class=\"{mask}\">{header}</legend>\n\t\t<div class=\"fcontainer clearfix\">\n\t\t";

    /** @var string Template used when opening a fieldset */
    var $_openFieldsetTemplate = "\n\t<fieldset class=\"{classes}\" {id}>";

    /** @var string Template used when closing a fieldset */
    var $_closeFieldsetTemplate = "\n\t\t</div></fieldset>";

    /** @var string Required Note template string */
    var $_requiredNoteTemplate =
        "\n\t\t<div class=\"fdescription required\">{requiredNote}</div>";

    var $_tabStartTemplateNav = 
        "\n\t\t<ul class=\"nav nav-tabs\">";

    var $_tabStartTemplate = 
        "\n\t\t<div class=\"tabtree\"><ul class=\"tabrow0\">";

    var $_tabTemplate = "\n\t\t<li id=\"{tabid}\" class=\"quickform-tab {active} {errors} \">
        <a href=\"Javascript:quickform_toggle_fieldset('{id}') \" alt=\"{alt}\"><span>{tab}</span></a></li>";

    var $_tabEndTemplate = "\n\t\t</ul></div>";

    var $_tabEndTemplateNav = "\n\t\t</ul>";

    var $_tabs = array();

    /**
     * Admin buttons for form mask setup string template.
     *
     * Note that the <span> will be converted as a link. This is done so that the link is not yet clickable
     * until the Javascript has been fully loaded.
     *
     * @var string
     */
    var $_configureButtonsTemplate = "\n\t<div class=\"configure-actions\"><a href=\"{link}\" class=\"formconfigure\"><input type=\"button\" class=\"{classes}\" value=\"{formconfigurelabel}\" /></a></div>";

    /**
     * Array whose keys are element names. If the key exists this is a advanced element
     *
     * @var array
     */
    var $_advancedElements = array();

    /**
     * Array whose keys are element names and the the boolean values reflect the current state. If the key exists this is a collapsible element.
     *
     * @var array
     */
    var $_collapsibleElements = array();

    /**
     * @var string Contains the collapsible buttons to add to the form.
     */
    var $_configureButtons = '';

    private $_fieldsetscount;

    private $_hasMaskedElements;

    var $_userFormUnfiltered;

    var $_stopFieldsetElements = array('buttonar', 'submitbutton');

    /**
     * Constructor
     */
    function __construct() {
        // switch next two lines for ol li containers for form items.
        //        $this->_elementTemplates=array('default'=>"\n\t\t".'<li class="fitem"><label>{label}{help}<!-- BEGIN required -->{req}<!-- END required --></label><div class="qfelement<!-- BEGIN error --> error<!-- END error --> {type}"><!-- BEGIN error --><span class="error">{error}</span><br /><!-- END error -->{element}</div></li>');
        $this->_elementTemplates = array(
        'default' => "\n\t\t".'<div id="{id}" class="fitem {advanced}<!-- BEGIN required --> required<!-- END required --> fitem_{type} {emptylabel} {mask}" {aria-live}>{maskbutton}<div class="fitemtitle"><label>{label}<!-- BEGIN required -->{req}<!-- END required -->{advancedimg} </label>{help}</div><div class="felement {type}<!-- BEGIN error --> error<!-- END error -->"><!-- BEGIN error --><span class="error">{error}</span><br /><!-- END error -->{element}</div></div>',

        'actionbuttons' => "\n\t\t".'<div id="{id}" class="fitem fitem_actionbuttons fitem_{type}"><div class="felement {type}">{element}</div></div>',

        'fieldset' => "\n\t\t".'<div id="{id}" class="fitem {advanced}<!-- BEGIN required --> required<!-- END required --> fitem_{type} {emptylabel} {mask}">{maskbutton}<div class="fitemtitle"><div class="fgrouplabel"><label>{label}<!-- BEGIN required -->{req}<!-- END required -->{advancedimg} </label>{help}</div></div><fieldset class="felement {type}<!-- BEGIN error --> error<!-- END error -->"><!-- BEGIN error --><span class="error">{error}</span><br /><!-- END error -->{element}</fieldset></div>',

        'static' => "\n\t\t".'<div class="fitem {advanced} {emptylabel} {mask}"><div class="fitemtitle"><div class="fstaticlabel"><label>{label}<!-- BEGIN required -->{req}<!-- END required -->{advancedimg} </label>{help}</div></div><div class="felement fstatic <!-- BEGIN error --> error<!-- END error -->"><!-- BEGIN error --><span class="error">{error}</span><br /><!-- END error -->{element}</div></div>',

        'warning' => "\n\t\t".'<div class="fitem {advanced} {emptylabel}">{element}</div>',

        'nodisplay' => '');

        $this->_fieldsetscount = 0;
        $this->_hasMaskedElements = false;
        $this->_userFormUnfiltered = false;
        $this->_currentHeader = '';

        parent::__construct();
    }

    /**
     * Set element's as adavance element
     *
     * @param array $elements form elements which needs to be grouped as advance elements.
     */
    function setAdvancedElements($elements) {
        $this->_advancedElements = $elements;
    }

    /**
     * Setting collapsible elements
     *
     * @param array $elements
     */
    function setCollapsibleElements($elements) {
        $this->_collapsibleElements = $elements;
    }

    /**
     * What to do when starting the form
     *
     * @param MoodleQuickForm $form reference of the form
     */
    function startForm(&$form) {
        global $PAGE, $SESSION, $DB, $USER;

        $this->_reqHTML = $form->getReqHTML();
        $this->_elementTemplates = str_replace('{req}', $this->_reqHTML, $this->_elementTemplates);
        $this->_advancedHTML = $form->getAdvancedHTML();
        $this->_collapseButtons = '';
        $this->_form = $form; // memorize it for further reference in element filtering.
        $this->_errors = $form->_errors;
        $formid = $form->getAttribute('id');

        $alternatemode = optional_param('alternateformmode', '', PARAM_INT);
        if ($alternatemode == 1) {
            if ($oldrec = $DB->get_record('user_preferences', array('userid' => $USER->id, 'name' => 'alternateformmode'))) {
                $oldrec->value = 1;
                $DB->update_record('user_preferences', $oldrec);
            } else {
                $record = new StdClass;
                $record->userid = $USER->id;
                $record->name = 'alternateformmode';
                $record->value = 1;
                $DB->insert_record('user_preferences', $record);
            }
        } else if ($alternatemode === 0) {
            if ($oldrec = $DB->get_record('user_preferences', array('userid' => $USER->id, 'name' => 'alternateformmode'))) {
                $oldrec->value = 0;
                $DB->update_record('user_preferences', $oldrec);
            } else {
                $record = new StdClass;
                $record->userid = $USER->id;
                $record->name = 'alternateformmode';
                $record->value = 0;
                $DB->insert_record('user_preferences', $record);
            }
        }
        $config = get_config('local_tabbedquickform');
        if (!$DB->record_exists('user_preferences', array('userid' => $USER->id, 'name' => 'alternateformmode'))) {
            $this->_userFormUnfiltered = (isset($config->defaultmode)) ? $config->defaultmode : 'simple';
        } else {
            $userpref = $DB->get_field('user_preferences', 'value', array('userid' => $USER->id, 'name' => 'alternateformmode'));
            if ($config->defaultmode) {
                $this->_userFormUnfiltered = $userpref;
            } else {
                // inverts the user pref meaning.
                $this->_userFormUnfiltered = ($userpref) ? 0 : 1;
            }
        }

        parent::startForm($form);

        $PAGE->requires->js('/local/tabbedquickform/js/module.js');
        $PAGE->requires->yui_module('moodle-local_tabbedquickform-tabbedformerrors',
                                    'Y.M.local_tabbedquickform.tabbedformerrors.init', null, null, true);

        if ($form->isFrozen()) {
            $this->_formTemplate = "\n<div class=\"mform frozen\">\n{content}\n</div>";
        } else {
            // $this->_formTemplate = "\n<form{attributes}>\n\t<div style=\"display: none;\">{hidden}</div>\n{collapsebtns}\n{content}\n</form>";
            $this->_formTemplate = "\n<form{attributes}>\n\t<div style=\"display: none;\">{hidden}</div>\n{formconfigure}\n{content}\n</form>";
            $this->_hiddenHtml .= $form->_pageparams;
        }

        if ($form->is_form_change_checker_enabled()) {
            $PAGE->requires->yui_module('moodle-core-formchangechecker',
                    'M.core_formchangechecker.init',
                    array(array(
                        'formid' => $formid
                    ))
            );
            $PAGE->requires->string_for_js('changesmadereallygoaway', 'moodle');
        }

        $systemcontext = context_system::instance();
        if (is_siteadmin() || has_capability('local/tabbedquickform:configure', $systemcontext)) {
            // change state.
            $configure = optional_param('configure', '', PARAM_BOOL);
            if ($configure) {
                $SESSION->adminmaskediting = true;
            } else if ($configure === 0) {
                $SESSION->adminmaskediting = false;
            }
            $this->_configureButtons = $this->_configureButtonsTemplate;
            if (empty($SESSION->adminmaskediting)) {
                $this->_configureButtons = str_replace('{formconfigurelabel}', get_string('enterconfigure', 'local_tabbedquickform'), $this->_configureButtons);
                $link = new moodle_url(qualified_me(), ['configure' => true, 'sesskey' => sesskey()]);
                $this->_configureButtons = str_replace('{link}', $link, $this->_configureButtons);
                $this->_configureButtons = str_replace('{classes}', 'quickform-configure-off', $this->_configureButtons);
            } else {
                $this->_configureButtons = str_replace('{formconfigurelabel}', get_string('exitconfigure', 'local_tabbedquickform'), $this->_configureButtons);
                $link = new moodle_url(qualified_me(), ['configure' => false, 'sesskey' => sesskey()]);
                $this->_configureButtons = str_replace('{link}', $link, $this->_configureButtons);
                $this->_configureButtons = str_replace('{classes}', 'quickform-configure-on', $this->_configureButtons);
            }
        }

        if (empty($SESSION->adminmaskediting) && $this->has_filters() && has_capability('local/tabbedquickform:canswitchfeatured', context_system::instance())) {
            if ($this->_userFormUnfiltered == 0) {
                $this->_configureButtons .= $this->_configureButtonsTemplate;
                $this->_configureButtons = str_replace('{formconfigurelabel}', get_string('fullfeatured', 'local_tabbedquickform'), $this->_configureButtons);
                $link = new moodle_url(qualified_me(), ['alternateformmode' => ($config->defaultmode) ? 1 : 0, 'sesskey' => sesskey()]);
                $this->_configureButtons = str_replace('{link}', $link, $this->_configureButtons);
                $this->_configureButtons = str_replace('{classes}', '', $this->_configureButtons);
            } else {
                $this->_configureButtons .= $this->_configureButtonsTemplate;
                $this->_configureButtons = str_replace('{formconfigurelabel}', get_string('filterfeatures', 'local_tabbedquickform'), $this->_configureButtons);
                $link = new moodle_url(qualified_me(), ['alternateformmode' => ($config->defaultmode) ? 0 : 1, 'sesskey' => sesskey()]);
                $this->_configureButtons = str_replace('{link}', $link, $this->_configureButtons);
                $this->_configureButtons = str_replace('{classes}', '', $this->_configureButtons);
            }
        }

        /*
        if (!empty($this->_collapsibleElements)) {
            if (count($this->_collapsibleElements) > 1) {
                $this->_collapseButtons = $this->_collapseButtonsTemplate;
                $this->_collapseButtons = str_replace('{strexpandall}', get_string('expandall'), $this->_collapseButtons);
                $PAGE->requires->strings_for_js(array('collapseall', 'expandall'), 'moodle');
            }
            $PAGE->requires->yui_module('moodle-form-shortforms', 'M.form.shortforms', array(array('formid' => $formid)));
        }
        */
        if (!empty($this->_advancedElements)) {
            // Rework Advanced Elements. We need get a workable register of advanced elements so that
            // hidden fields can be addressed.
            $PAGE->requires->strings_for_js(array('showmore', 'showless'), 'form');
            local_tabbedquickform_require_js($formid);
        }
    }

    /**
     * Create advance group of elements
     *
     * @param object $group Passed by reference
     * @param bool $required if input is required field
     * @param string $error error message to display
     */
    function startGroup(&$group, $required, $error) {
        global $CFG, $SESSION, $OUTPUT;

        // Make sure the element has an id.
        $group->_generateId();

        if (method_exists($group, 'getElementTemplateType')) {
            $html = $this->_elementTemplates[$group->getElementTemplateType()];
        } else {
            $html = $this->_elementTemplates['default'];

        }

        // Deal with feature masking
        if ($visible = $this->filterFeatures($html, $group, true)) {
            // Jump over form action buttons
            if ($group->_name != 'buttonar') {
                if (!isset($this->_tabs[$this->_currentHeader])) {
                    $this->_tabs[$this->_currentHeader] = new StdClass;
                }
                $this->_tabs[$this->_currentHeader]->hasVisibleElements = true;
            }
        }

        if (isset($this->_advancedElements[$group->getName()]) && $visible) {
            $html = str_replace(' {advanced}', ' advanced', $html);
            $html = str_replace('{advancedimg}', $this->_advancedHTML, $html);
            $this->_tabs[$this->_currentHeader]->hasAdvancedVisibleElements = true;
        } else {
            $html = str_replace(' {advanced}', '', $html);
            $html = str_replace('{advancedimg}', '', $html);
        }

        if (method_exists($group, 'getHelpButton')) {
            $html = str_replace('{help}', $group->getHelpButton(), $html);
        } else {
            $html = str_replace('{help}', '', $html);
        }
        $html = str_replace('{id}', 'fgroup_' . $group->getAttribute('id'), $html);
        $html = str_replace('{name}', $group->getName(), $html);
        $html = str_replace('{type}', 'fgroup', $html);
        $emptylabel = '';
        if ($group->getLabel() == '') {
            $emptylabel = 'femptylabel';
        }
        $html = str_replace('{emptylabel}', $emptylabel, $html);

        if ($group->_name != 'buttonar') {
            $this->_tabs[$this->_currentHeader]->hasElements = true;
        }

        $html = str_replace('{mask}', '', $html);

        if (isset($this->_errors[$group->getName()])) {
            $this->_tabs[$this->_currentHeader]->hasErrors = true;
        }

        $this->_templates[$group->getName()] = $html;
        // Fix for bug in tableless quickforms that didn't allow you to stop a
        // fieldset before a group of elements.
        // if the element name indicates the end of a fieldset, close the fieldset
        if (in_array($group->getName(), $this->_stopFieldsetElements)
            && $this->_fieldsetsOpen > 0
           ) {
            $this->_html .= $this->_closeFieldsetTemplate;
            $this->_fieldsetsOpen--;
        }
        parent::startGroup($group, $required, $error);
    }

    /**
     * Renders element
     *
     * @param HTML_QuickForm_element $element element
     * @param bool $required if input is required field
     * @param string $error error message to display
     */
    function renderElement(&$element, $required, $error) {
        global $PAGE;

        // Make sure the element has an id.
        $element->_generateId();

        // Adding stuff to place holders in template.
        // Check if this is a group element first.
        if (($this->_inGroup) and !empty($this->_groupElementTemplate)) {
            // So it gets substitutions for *each* element.
            $html = $this->_groupElementTemplate;
        } else if (method_exists($element, 'getElementTemplateType')) {
            $html = $this->_elementTemplates[$element->getElementTemplateType()];
        } else {
            $html = $this->_elementTemplates['default'];
        }

        // Deal with feature masking only if plain elements.
        if (!$this->_inGroup) {
            if ($visible = $this->filterFeatures($html, $element)) {
                if (!($element->_type == 'submit')) {
                    // Setting _currentHeader to new object because of element->getName().
                    if (!isset($this->_tabs[$this->_currentHeader])) {
                        $this->_tabs[$this->_currentHeader] = new StdClass;
                    }
                    $this->_tabs[$this->_currentHeader]->hasVisibleElements = true;
                }
            }

            if (!($element->_type == 'submit')) {
                // Adding element->getName().' to '.$this->_currentHeader.
                $this->_tabs[$this->_currentHeader]->hasElements = true;
            }
        } else {
            $visible = true;
        }

        if (isset($this->_errors[$element->getName()])) {
            $this->_tabs[$this->_currentHeader]->hasErrors = true;
        }

        if ($visible) {
            if (isset($this->_advancedElements[$element->getName()])) {
                $html = str_replace(' {advanced}', ' advanced', $html);
                $html = str_replace(' {aria-live}', ' aria-live="polite"', $html);
                $this->_tabs[$this->_currentHeader]->hasAdvancedVisibleElements = true;
            } else {
                $html = str_replace(' {advanced}', '', $html);
                $html = str_replace(' {aria-live}', '', $html);
            }
            if (isset($this->_advancedElements[$element->getName()])||$element->getName() == 'mform_showadvanced') {
                $html = str_replace('{advancedimg}', $this->_advancedHTML, $html);
            } else {
                $html = str_replace('{advancedimg}', '', $html);
            }
        }
        $html = str_replace('{id}', 'fitem_' . $element->getAttribute('id'), $html);
        $html = str_replace('{type}', 'f'.$element->getType(), $html);
        $html = str_replace('{name}', $element->getName(), $html);
        $emptylabel = '';

        if ($element->getLabel() == '') {
            $emptylabel = 'femptylabel';
        }
        $html = str_replace('{emptylabel}', $emptylabel, $html);
        if (method_exists($element, 'getHelpButton')) {
            $html = str_replace('{help}', $element->getHelpButton(), $html);
        } else {
            $html = str_replace('{help}', '', $html);
        }

        // Remove all unprocessed placeholders.
        $html = str_replace('{maskbutton}', '', $html);
        $html = str_replace('{mask}', '', $html);
        $html = str_replace('{advanced}', '', $html);
        $html = str_replace('{advancedimg}', '', $html);
        $html = str_replace('{aria-live}', '', $html);

        if (($this->_inGroup) and !empty($this->_groupElementTemplate)) {
            $this->_groupElementTemplate = $html;
        } else if (!isset($this->_templates[$element->getName()])) {
            $this->_templates[$element->getName()] = $html;
        }

        // @TODO : inject mask default value if there is any.
        $fitemid = $element->getAttribute('id');
        $bodyid = str_replace('-', '_', $PAGE->bodyid);
        $maskkey = 'mask_'.$bodyid.'_'.$fitemid;
        $config = get_config('local_tabbedquickform');
        if (isset($config->$maskkey) &&
                ($config->$maskkey != '%UNSET%') &&
                        ($this->_userFormUnfiltered == 0)) {
            // If we have an explicit setable value, have a mask and are in simple mode, then apply.
            $element->setValue($config->$maskkey);
        }

        parent::renderElement($element, $required, $error);
    }

    /**
     * Called when visiting a form, after processing all form elements
     * Adds required note, form attributes, validation javascript and form content.
     *
     * @global moodle_page $PAGE
     * @param moodleform $form Passed by reference
     */
    function finishForm(&$form) {
        global $PAGE, $SESSION;

        if ($form->isFrozen()) {
            $this->_hiddenHtml = '';
        }

        parent::finishForm($form);

        $this->_html = str_replace('{formconfigure}', $this->_configureButtons, $this->_html);
        // $this->_html = str_replace('{collapsebtns}', '', $this->_html);

        if (!$form->isFrozen()) {
            $args = $form->getLockOptionObject();
            if (count($args[1]) > 0) {
                $PAGE->requires->js_init_call('M.form.initFormDependencies', $args, true, moodleform::get_js_module());
            }
        }

        $standardtabs = $this->hasStandardTabs();

        // Finally add tabs to the top.
        if (!empty($this->_tabs) && (count($this->_tabs) > 1)) {
            if (!empty($standardtabs)) {
                $tabs = $this->_tabStartTemplate;
            } else {
                $tabs = $this->_tabStartTemplateNav;
            }
            $active = true;
            $postformjquery = '';
            foreach ($this->_tabs as $tab) {

                if ('stdClass' == get_class($tab)) {
                    continue;
                }
                if (!$tab->hasVisibleElements && $tab->hasElements && empty($SESSION->adminmaskediting)) {
                    continue;
                }

                $tabstr = $this->_tabTemplate;

                $tabname = $tab->getName();
                $tabstr = str_replace('{tabid}', 'tab-'.$tabname, $tabstr);
                $tabstr = str_replace('{tab}', $tab->toHtml(), $tabstr);
                // Set first tab as active.
                if ($active) {
                    $tabstr = str_replace('{active}', 'active here', $tabstr);
                    $tabstr = str_replace('{id}', $tab->getAttribute('id'), $tabstr);
                    $active = false;
                } else {
                    $tabstr = str_replace('{active}', '', $tabstr);
                    $tabstr = str_replace('{id}', $tab->getAttribute('id'), $tabstr);
                }
                if (@$tab->hasErrors) {
                    $tabstr = str_replace('{errors}', 'tabbedform-error', $tabstr);
                    $tabstr = str_replace('{alt}', get_string('sectionhaserrors', 'local_tabbedquickform'), $tabstr);
                } else {
                    $tabstr = str_replace('{errors}', '', $tabstr);
                    $tabstr = str_replace('{alt}', '', $tabstr);
                }
                if ($tabname == 'modstandardelshdr') {
                    // Add a convenient tab separator when an activity module.
                    if (!empty($standardtabs)) {
                        $tabs .= '</ul><ul class="tabrow0">';
                    } else {
                        $tabs .= '</ul><ul class="nav nav-tabs">';
                    }
                }
                $tabs .= $tabstr;

                if (!$tab->hasAdvancedVisibleElements) {
                    $postformjquery .= 'quickform_toggle_hidden(\''.$tab->getAttribute('id').'\');'."\n";
                }
            }
            if (!empty($standardtabs)) {
                $tabs .= $this->_tabEndTemplate;
            } else {
                $tabs .= $this->_tabEndTemplateNav;
            }
            $tabs .= '<script type="text/javascript">';
            $tabs .= 'function quickform_toggle_fieldset(fid, nofire) {
                $(\'.quickform-fieldset\').addClass(\'quickform-hidden-tab\');
                $(\'.quickform-tab\').removeClass(\'active\');
                $(\'.quickform-tab\').removeClass(\'here\');

                if (!$(\'#\' + fid).length) {
                    fid = $(\'.quickform-tab:first\').attr(\'id\').replace(\'tab-\', \'id_\');
                }
                shortfid = fid.replace(\'id_\', \'\');
                $(\'#\'+fid).removeClass(\'quickform-hidden-tab\');
                $(\'#tab-\' + shortfid).addClass(\'active\');
                $(\'#tab-\' + shortfid).addClass(\'here\');

                // Just fire field choice to keep it in session for further reloads.
                if (nofire == undefined) {
                    url = M.cfg.wwwroot + \'/local/tabbedquickform/ajax/services.php?what=keepfield&fid=\' + fid;
                    $.get(url, function(data){} );
                }
            }'."\n";
            $tabs .= 'function quickform_toggle_hidden(elmid) {
        $(\'#\' + elmid + \' .fitem.moreless-actions\').toggleClass(\'quickform-mask-hidden\');
}';
            $tabs .= '</script>';

            $postform = '';
            $postform = '<script type="text/javascript">';
            if (!empty($postformjquery)) {
                $postform .= $postformjquery;
            }
            if (!empty($SESSION->formactivefield)) {
                // If some field is stored in session try active it.
                $postform .= 'quickform_toggle_fieldset(\''.$SESSION->formactivefield.'\', true);'."\n";
            }
            $postform .= '</script>';

            $this->_html = $tabs.$this->_html.$postform;

            $context = context_system::instance();
            if ((is_siteadmin() ||
                    has_capability('local/tabbedquickform:canswitchfeatured', $context)) &&
                            $this->_hasMaskedElements && !$this->_userFormUnfiltered) {
                $this->_html .= '<div class="quickform-mask-notice fa fa-exclamation-circle">';
                $this->_html .= get_string('hasmaskeditems', 'local_tabbedquickform');
                $this->_html .= '</div>';
            }
        }
    }

    function hasStandardTabs() {

        // Print a fake tab rendering and check what is inside.
        $tabrow[0][] = new tabobject('fake', 'http://foo.com', 'fake');
        $result = print_tabs($tabrow, null, null, null, true);

        return preg_match('/tabtree/', $result);
    }

   /**
    * Called when visiting a header element
    *
    * @param HTML_QuickForm_header $header An HTML_QuickForm_header element being visited
    * @global moodle_page $PAGE
    */
    function renderHeader(&$header) {
        global $PAGE;

        $header->_generateId();
        $name = $header->getName();

        $id = empty($name) ? '' : ' id="' . $header->getAttribute('id') . '"';
        if (is_null($header->_text)) {
            $header_html = '';
        } else if (!empty($name) && isset($this->_templates[$name])) {
            $header_html = str_replace('{header}', $header->toHtml(), $this->_templates[$name]);
        } else {
            $header_html = str_replace('{header}', $header->toHtml(), $this->_headerTemplate);
        }

        if ($this->_fieldsetsOpen > 0) {
            $this->_html .= $this->_closeFieldsetTemplate;
            $this->_fieldsetsOpen--;
            $this->_fieldsetscount++;
        }

        // Define collapsible classes for fieldsets.
        $arialive = '';
        $fieldsetclasses = array('clearfix', 'quickform-fieldset');

        /*
        if (isset($this->_collapsibleElements[$header->getName()])) {
            $fieldsetclasses[] = 'collapsible';
            if ($this->_collapsibleElements[$header->getName()]) {
                $fieldsetclasses[] = 'collapsed';
            }
        }
        */

        if (isset($this->_advancedElements[$name])) {
            $fieldsetclasses[] = 'containsadvancedelements';
        }

        if ($this->_fieldsetscount) {
            $fieldsetclasses[] = 'quickform-hidden-tab';
        }

        $openFieldsetTemplate = str_replace('{id}', $id, $this->_openFieldsetTemplate);
        $openFieldsetTemplate = str_replace('{classes}', join(' ', $fieldsetclasses), $openFieldsetTemplate);
        $header_html = str_replace('{mask}', '', $header_html);

        $this->_html .= $openFieldsetTemplate . $header_html;
        $this->_currentHeader = $name;
        $this->_fieldsetsOpen++;

        // Register header in tabs.
        $header->hasVisibleElements = 0;
        $header->hasAdvancedVisibleElements = 0;
        $header->hasElements = 0;
        $this->_tabs[$name] = $header;
    }

    /**
     * Return Array of element names that indicate the end of a fieldset
     *
     * @return array
     */
    function getStopFieldsetElements() {
        return $this->_stopFieldsetElements;
    }

    function filterFeatures(&$html, &$element, $isgroup = false) {
        global $SESSION, $OUTPUT, $CFG, $PAGE;

        static $config;

        if (!isset($config)) {
            $config = get_config('local_tabbedquickform');
        }

        $fitemid = $element->getAttribute('id');
        $bodyid = str_replace('-', '_', $PAGE->bodyid);
        $maskkey = 'mask_'.$bodyid.'_'.$fitemid;
        if (empty($SESSION->adminmaskediting)) {
            if (isset($config->$maskkey) && ($this->_userFormUnfiltered == 0)) {
                /*
                 * Normal use of the for will add masking css to hide the element if masked.
                 */
                $this->_hasMaskedElements = true;
                $html = str_replace('{mask}', 'quickform-mask-hidden', $html);
                return false;
            } else {
                // Unmasked elements. Just remove the mask placeholder.
                $html = str_replace('{mask}', '', $html);
            }
            // Remove any mask switch button.
            $html = str_replace('{maskbutton}', '', $html);
            return true;
        } else {
            // Admin is editing the form featuring.
            if (!in_array(@$element->_attributes['name'], $this->_form->_required) || @$config->allowmaskingmandatories) {
                $maskedpix = $OUTPUT->image_url('masked', 'local_tabbedquickform');
                $unmaskedpix = $OUTPUT->image_url('unmasked', 'local_tabbedquickform');
                $groupflag = ($isgroup) ? 'true' : 'false' ;
                if (!isset($config->$maskkey)) {
                    $html = str_replace('{mask}', 'quickform-mask-selectable', $html);
                    $jscall = 'javascript:quickform_toggle_mask(\''.$bodyid.'\', \''.$fitemid.'\', '.$groupflag.', \''.$maskedpix.'\', \''.$unmaskedpix.'\')';
                    $html = str_replace('{maskbutton}', '<div class="quickform-mask-button"><a href="'.$jscall.'"><img id="mask_'.$fitemid.'" src="'.$unmaskedpix.'"></a></div>', $html);
                } else {
                    $html = str_replace('{mask}', 'quickform-mask-selected', $html);
                    $jscall = 'javascript:quickform_toggle_mask(\''.$bodyid.'\', \''.$fitemid.'\', '.$groupflag.', \''.$maskedpix.'\', \''.$unmaskedpix.'\')';
                    $html = str_replace('{maskbutton}', '<div class="quickform-mask-button"><a href="'.$jscall.'"><img id="mask_'.$fitemid.'" src="'.$maskedpix.'"></a></div>', $html);
                }
            } else {
                $html = str_replace('{mask}', '', $html);
                $html = str_replace('{maskbutton}', '', $html);
            }
        }
        return true;
    }

    /**
     * checks if there are some masks registered in global plugin config. this will speedup
     * form construction if there are no masks recorded.
     * @return boolean
     */
    function has_filters() {
        global $PAGE, $DB;

        $bodyid = str_replace('-', '_', $PAGE->bodyid);
        $maskkey = 'mask_'.$bodyid.'_%';

        return $DB->record_exists_select('config_plugins', ' plugin = ? AND name LIKE ? ', array('local_tabbedquickform', $maskkey));
    }
}
