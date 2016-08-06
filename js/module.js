
function quickform_toggle_mask(wwwroot, bodyid, fid, themepixmasked, themepixunmasked) {

    if ($('#fitem_'+fid).hasClass('quickform-mask-selectable')) {
        what = 'maskformitem';
    } else {
        what = 'unmaskformitem';
    }

    url = wwwroot+'/local/tabbedquickform/ajax/services.php?what='+what+'&bodyid='+bodyid+'&fitemid='+fid;

    $.get(url, function(data) {
        if (data == 'masked') {
            $('#fitem_'+fid).removeClass('quickform-mask-selectable');
            $('#fitem_'+fid).addClass('quickform-mask-selected');
            $('#mask_'+fid).attr('src', themepixmasked);
        } else {
            $('#fitem_'+fid).removeClass('quickform-mask-selected');
            $('#fitem_'+fid).addClass('quickform-mask-selectable');
            $('#mask_'+fid).attr('src', themepixunmasked);
        }
    }, 'html');

}