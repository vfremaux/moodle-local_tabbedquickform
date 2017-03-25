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

/*
 * An event processing when a form has errors to make some signalling to
 * user.
 *
 * @module     moodle-local-tabbedquickform-tabbedformerrors
 * @package    local_tabbedquickform
 * @copyright  2017 Valery Fremaux <valery.fremaux@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @main       moodle-local_tabbedquickform-tannedformerrors
 */

/*
 * @namespace M.local_tabbedquickform
 * @class tabbedformerrors
 */

// jshint unused:false, undef:false

function TabbedformErrors() {
    TabbedformErrors.superclass.constructor.apply(this, arguments);
}

var LOGNAME = 'moodle-local_tabbedquickform-tabbedformerrors';

Y.extend(TabbedformErrors, Y.Base, {
    initializer: function() {
        // Subscribe to form error event, when there's an error in a tab.
        if (M.core.globalEvents) {
            Y.Global.on(M.core.globalEvents.FORM_ERROR, this.report_errors, this);
        }
    },

    report_errors: function(e) {
        var errorElement = e.currentTarget;

        // Add marking class to the corresponding tab.
        fieldsetElement = errorElement.ancestor('fieldset');
        fieldsetid = fieldsetElement.getAttribute('id');
        tabid = 'tab-' + fieldsetid.replace(/id-/, '');
        tabElement = Y.node('#' + tabid);
        tabElement.addClass('tabbedform-error');
    },

}, {
    NAME: 'tabbedformErrors',
    ATTRS: {
    }
});

var NS = Y.namespace('M.local_tabbedquickform.tabbedformerrors');
NS.init = function(config) {
    return new TabbedformErrors(config);
};
