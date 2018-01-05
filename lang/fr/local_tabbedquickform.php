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
 * @package   local_tabbedquickform
 * @category  blocks
 * @author    Valery Fremaux (valery.fremaux@gmail.com)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();

$string['tabbedquickform:canswitchfeatured'] = 'Peut basculer en version complète';
$string['tabbedquickform:configure'] = 'Peut cponfigurer les formulaires';

$string['allowmaskingmandatories'] = 'Permettre de masquer les champs obligatoires';
$string['complete'] = 'Complet';
$string['deleterange'] = 'Motif de sélection (LIKE)';
$string['enterconfigure'] = 'Configurer le formulaire';
$string['excludepagetypes'] = 'Types de page exclus';
$string['exitconfigure'] = 'Quitter la configuration';
$string['export'] = 'Exporter les masques';
$string['exportprofiles'] = 'Suppression des masques';
$string['exportprofilespro'] = 'Import/export de profils de masques';
$string['filterfeatures'] = 'Accéder au mode simplifié';
$string['fullfeatured'] = 'Accéder au mode complet';
$string['hasmaskeditems'] = 'Ce formulaire a des champs masqués';
$string['import'] = 'Importer les masques';
$string['localtabbedquickformdefaultmode'] = 'Mode par défaut';
$string['localtabbedquickformenable'] = 'Activer';
$string['maskkeys'] = 'Clefs de masques (un par ligne)';
$string['masksinsite'] = 'Ce site a {$a} masques de formulaires actifs.';
$string['plugindist'] = 'Distribution du plugin';
$string['pluginname'] = 'Formulaires à onglets';
$string['reset'] = 'Réinitialiser les masques';
$string['sectionhaserrors'] = 'Il y a des erreurs dans cet onglet.';
$string['simple'] = 'Simplifié';

$string['allowmaskingmandatories_desc'] = '
Si activé, les administrateurs peuvent masquer les champs même s\'ils sont obligatoires. Il doivent cependant
prendre garde à ce qu\'une valeur par défaut de la propriété soit disponible.
';

$string['excludepagetypes_desc'] = '
Entrez une liste d\identifiants de page (un par ligne) qui joueront les formulaires sous forme de sections verticales.
';

$string['exportprofiles_desc'] = '
Vous pouvez <a href="{$a->reseturl}">réinitialiser tout ou partie</a> des masques actuellement enregistrés dans le site.
';

$string['exportprofilespro_desc'] = '
Vous pouvez <a href="{$a->exporturl}">exporter</a> et <a href="{$a->importurl}">importer</a> des définitions
de masque comme des simples listes de clefs. Vous pouvez également  <a href="{$a->reseturl}">réinitialiser tout ou
partie</a> des masques actuellement enregistrés dans le site.
';

$string['localtabbedquickformdefaultmode_desc'] = '
Détermine l\'état des formulaires pour le profil utilisateur par défaut
';

$string['localtabbedquickformenable_desc'] = '
Activer le rendu à onglets et le filtrage fonctionnel des formulaires. Pour rendre cette fonction opérationnelle, vous
devez mettre en oeuvre la modification de la librairie /lib/formslib.php telle que décrite dans le fichier README.txt.
';

$string['plugindist_desc'] = '
<p>Ce plugin est distribué dans la communauté Moodle pour l\'évaluation de ses fonctions centrales
correspondant à une utilisation courante du plugin. Une version "professionnelle" de ce plugn existe et est distribuée
sous certaines conditions, afin de soutenir l\'effort de développement, amélioration; documentation et suivi des versions.</p>
<p>Contactez un distributeur pour obtenir la version "Pro" et son support.</p>
<ul><li><a href="http://service.activeprolearn.com/local/shop/front/view.php?id=1">ActiveProLearn SAS</a></li>
<li><a href="http://www.edunao.com">Edunao SAS</a></li></ul>
';
