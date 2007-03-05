<?php
/*
  $Id: zone_groups_entries.php 1016 2006-10-18 11:38:59Z hpdl $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2006 osCommerce

  Released under the GNU General Public License
*/
?>

<h1><?php echo osc_link_object(osc_href_link(FILENAME_DEFAULT, $osC_Template->getModule()), $osC_Template->getPageTitle()); ?></h1>

<?php
  if ($osC_MessageStack->size($osC_Template->getModule()) > 0) {
    echo $osC_MessageStack->output($osC_Template->getModule());
  }
?>

<p align="right"><?php echo '<input type="button" value="' . IMAGE_BACK . '" onclick="document.location.href=\'' . osc_href_link_admin(FILENAME_DEFAULT, $osC_Template->getModule() . '&page=' . $_GET['page']) . '\';" class="infoBoxButton" /> <input type="button" value="' . IMAGE_INSERT . '" onclick="document.location.href=\'' . osc_href_link_admin(FILENAME_DEFAULT, $osC_Template->getModule() . '=' . $_GET[$osC_Template->getModule()] . '&page=' . $_GET['page'] . '&action=entrySave') . '\';" class="infoBoxButton" />'; ?></p>

<?php
  $Qentries = $osC_Database->query('select z2gz.association_id, z2gz.zone_country_id, c.countries_name, z2gz.zone_id, z2gz.geo_zone_id, z2gz.last_modified, z2gz.date_added, z.zone_name from :table_zones_to_geo_zones z2gz left join :table_countries c on (z2gz.zone_country_id = c.countries_id) left join :table_zones z on (z2gz.zone_id = z.zone_id) where z2gz.geo_zone_id = :geo_zone_id order by c.countries_name, z.zone_name');
  $Qentries->bindTable(':table_zones_to_geo_zones', TABLE_ZONES_TO_GEO_ZONES);
  $Qentries->bindTable(':table_zones', TABLE_ZONES);
  $Qentries->bindTable(':table_countries', TABLE_COUNTRIES);
  $Qentries->bindInt(':geo_zone_id', $_GET[$osC_Template->getModule()]);
  $Qentries->execute();
?>

<table border="0" width="100%" cellspacing="0" cellpadding="2">
  <tr>
    <td><?php echo sprintf(TEXT_DISPLAY_NUMBER_OF_ENTRIES, ($Qentries->numberOfRows() > 0 ? 1 : 0), $Qentries->numberOfRows(), $Qentries->numberOfRows()); ?></td>
  </tr>
</table>

<form name="batch" action="#" method="post">

<table border="0" width="100%" cellspacing="0" cellpadding="2" class="dataTable">
  <thead>
    <tr>
      <th><?php echo TABLE_HEADING_COUNTRY; ?></th>
      <th><?php echo TABLE_HEADING_COUNTRY_ZONE; ?></th>
      <th width="150"><?php echo TABLE_HEADING_ACTION; ?></th>
      <th align="center" width="20"><?php echo osc_draw_checkbox_field('batchFlag', null, null, 'onclick="flagCheckboxes(this);"'); ?></th>
    </tr>
  </thead>
  <tfoot>
    <tr>
      <th align="right" colspan="3"><?php echo '<input type="image" src="' . osc_icon_raw('trash.png') . '" title="' . IMAGE_DELETE . '" onclick="document.batch.action=\'' . osc_href_link_admin(FILENAME_DEFAULT, $osC_Template->getModule() . '=' . $_GET[$osC_Template->getModule()] . '&page=' . $_GET['page'] . '&action=batchDeleteEntries') . '\';" />'; ?></th>
      <th align="center" width="20"><?php echo osc_draw_checkbox_field('batchFlag', null, null, 'onclick="flagCheckboxes(this);"'); ?></th>
    </tr>
  </tfoot>
  <tbody>

<?php
  while ($Qentries->next()) {
?>

    <tr onmouseover="rowOverEffect(this);" onmouseout="rowOutEffect(this);">
      <td><?php echo (($Qentries->valueInt('zone_country_id') > 0) ? $Qentries->value('countries_name') : TEXT_ALL_COUNTRIES); ?></td>
      <td><?php echo (($Qentries->valueInt('zone_id') > 0) ? $Qentries->value('zone_name') : PLEASE_SELECT); ?></td>
      <td align="right">

<?php
    echo osc_link_object(osc_href_link_admin(FILENAME_DEFAULT, $osC_Template->getModule() . '=' . $_GET[$osC_Template->getModule()] . '&page=' . $_GET['page'] . '&zeID=' . $Qentries->valueInt('association_id') . '&action=entrySave'), osc_icon('configure.png', IMAGE_EDIT)) . '&nbsp;' .
         osc_link_object(osc_href_link_admin(FILENAME_DEFAULT, $osC_Template->getModule() . '=' . $_GET[$osC_Template->getModule()] . '&page=' . $_GET['page'] . '&zeID=' . $Qentries->valueInt('association_id') . '&action=entryDelete'), osc_icon('trash.png', IMAGE_DELETE));
?>

      </td>
      <td align="center"><?php echo osc_draw_checkbox_field('batch[]', $Qentries->valueInt('association_id'), null, 'id="batch' . $Qentries->valueInt('association_id') . '"'); ?></td>
    </tr>

<?php
    }
?>

  </tbody>
</table>

</form>

<table border="0" width="100%" cellspacing="0" cellpadding="2">
  <tr>
    <td style="opacity: 0.5; filter: alpha(opacity=50);"><?php echo '<b>' . TEXT_LEGEND . '</b> ' . osc_icon('configure.png', IMAGE_EDIT) . '&nbsp;' . IMAGE_EDIT . '&nbsp;&nbsp;' . osc_icon('trash.png', IMAGE_DELETE) . '&nbsp;' . IMAGE_DELETE; ?></td>
  </tr>
</table>