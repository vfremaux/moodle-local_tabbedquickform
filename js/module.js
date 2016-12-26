/**
 * Quickform js helpers.
 */
// jshint undef:false, unused:false

function quickform_toggle_mask(bodyid, fid, isgroup, themepixmasked, themepixunmasked) {

    if (isgroup) {
        prefix = 'fgroup';
    } else {
        prefix = 'fitem';
    }

    if ($('#' + prefix + '_' + fid).hasClass('quickform-mask-selectable')) {
        what = 'maskformitem';
    } else {
        what = 'unmaskformitem';
    }

    var elm = $('#' + fid);
    if (elm.is('input')) {
        if (elm.attr('type') === 'checkbox') {
            var value = elm.is(':checked') ? 1 : 0;
        } else {
            var value = elm.val();
        }
        elm.css('background-color', '#CCFABE');
    } else if (elm.is('select')) {
        var value = elm.val();
        elm.css('background-color', '#CCFABE');
    } else if (elm.is('textarea')) {
        var value = elm.val();
        elm.css('background-color', '#CCFABE');
    } else {
        var value = '%UNSET%';
    }

    url = M.cfg.wwwroot + '/local/tabbedquickform/ajax/services.php?what=' + what + '&bodyid=' + bodyid + '&fitemid=' + fid;
    url += '&value=' + value;

    $.get(url, function(data) {
        if (data === 'masked') {
            $('#' + prefix + '_' + fid).removeClass('quickform-mask-selectable');
            $('#' + prefix + '_' + fid).addClass('quickform-mask-selected');
            $('#mask_' + fid).attr('src', themepixmasked);
        } else {
            $('#' + prefix + '_' + fid).removeClass('quickform-mask-selected');
            $('#' + prefix + '_' + fid).addClass('quickform-mask-selectable');
            $('#mask_' + fid).attr('src', themepixunmasked);
        }
    }, 'html');

}
