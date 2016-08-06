
function quickform_toggle_mask(wwwroot, bodyid, fid, isgroup, themepixmasked, themepixunmasked) {

    if (isgroup) {
        prefix = 'fgroup';
    } else {
        prefix = 'fitem';
    }

    if ($('#'+prefix+'_'+fid).hasClass('quickform-mask-selectable')) {
        what = 'maskformitem';
    } else {
        what = 'unmaskformitem';
    }

    url = wwwroot+'/local/tabbedquickform/ajax/services.php?what='+what+'&bodyid='+bodyid+'&fitemid='+fid;

    $.get(url, function(data) {
        if (data == 'masked') {
            $('#'+prefix+'_'+fid).removeClass('quickform-mask-selectable');
            $('#'+prefix+'_'+fid).addClass('quickform-mask-selected');
            $('#mask_'+fid).attr('src', themepixmasked);
        } else {
            $('#'+prefix+'_'+fid).removeClass('quickform-mask-selected');
            $('#'+prefix+'_'+fid).addClass('quickform-mask-selectable');
            $('#mask_'+fid).attr('src', themepixunmasked);
        }
    }, 'html');

}