# moodle-local_tabbedquickform
Organise the forms by tabs and allow filtering forms from undesired entries (Needs 1 patch).

====== Install the patchs ========

Needed Patchs are packaged into a standard dual meta directory to help integrators to process
automated patch integration. 

__patch directory contains the patched files that are used for the current version of Moodle. Patched
files are given into a consistant relative path from moodle root installation. 

__reference directory contains original core files that were used originally when patch has been
designed. Reference files are given with consistant relative path from moodle installation root. As
Moodle core is constantly changing more or less, the file you may have in your version may not be
exactly matching the location of the given patchs. You will use those reference files to better
identify the patch location.

To identify patchs, we use an additional // PATCH+ and // PATCH- marking. You may use the report_patches
plugin to help maintaining a patch catalog into your moodle.
In case a patch file does not have a reference counterpart, this will mean this is an additional file
that does not previously exist in core.

- diff the __patch and the __reference files to identify patch points
- report into your moodle files version

==== ensuring jquery in all screens / Theme change

Some themes may not provide jquery on all screens. Tabbed quickform needs jquery and is not restricted
to any subcontext nor domain.

If you experiment some misfunctionning of some tabs, you will need to add :

// Tabbed quickform addition for generalizing the Jquery.
global $PAGE;
if (!empty($PAGE) && !$PAGE->state) {
    $PAGE->requires->jquery();
}

at the end of your theme config.php file.

2017071400 : Adds the configure capability.