<?php

require_once 'donorsearch.civix.php';
require_once 'CRM/DonorSearch/FieldInfo.php';

/**
 * Implements hook_civicrm_config().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function donorsearch_civicrm_config(&$config) {
  _donorsearch_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_xmlMenu().
 *
 * @param array $files
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_xmlMenu
 */
function donorsearch_civicrm_xmlMenu(&$files) {
  _donorsearch_civix_civicrm_xmlMenu($files);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function donorsearch_civicrm_install() {
  _donorsearch_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_uninstall
 */
function donorsearch_civicrm_uninstall() {
  _donorsearch_civix_civicrm_uninstall();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function donorsearch_civicrm_enable() {
  _donorsearch_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_disable
 */
function donorsearch_civicrm_disable() {
  _donorsearch_civix_civicrm_disable();
}

/**
 * disable/enable/delete Donor Search links
 */
function changeDSNavigation($action) {
  $names = array('ds_register_api', 'ds_view', 'ds_new');

  foreach ($names as $name) {
    if ($name == 'delete') {
      $id = civicrm_api3('Navigation', 'getvalue', array(
        'return' => "id",
        'name' => $name,
      ));
      if ($id) {
        civicrm_api3('Navigation', 'delete', array('id' => $id));
      }
    }
    else {
      $isActive = ($action == 'enable') ? 1 : 0;
      CRM_Core_BAO_Navigation::setIsActive(
        CRM_Core_DAO::getFieldValue('CRM_Core_DAO_Navigation', $name, 'id', 'name'),
        $isActive
      );
    }
  }

  CRM_Core_BAO_Navigation::resetNavigation();
}

/**
 * Implementation of hook_civicrm_permission
 *
 * @param array $permissions
 * @return void
 */
function donorsearch_civicrm_permission(&$permissions) {
  $permissions += array('access DonorSearch' => ts('Access DonorSearch', array('domain' => 'org.civicrm.donorsearch')));
}

/**
 * @inheritDoc
 */
function donorsearch_civicrm_pageRun(&$page) {
  if ($page->getVar('_name') == 'CRM_Contact_Page_View_CustomData') {
    $contactId = $page->getVar('_contactId');
    $count = civicrm_api3('DonorSearch', 'getcount', array('contact_id' => $contactId));
    if ($count) {
      CRM_Core_Region::instance('custom-data-view-DS_details')->add(array(
        'markup' => '
          <a class="no-popup button" target="_blank" href="' . CRM_Utils_System::url('civicrm/view/ds-profile', "cid=" . $contactId) . '">
            <span>' . ts('View Donor Search Profile', array('domain' => 'org.civicrm.donorsearch')) . '</span>
          </a>
        ',
      ));
    }
    else {
      CRM_Core_Region::instance('custom-data-view-DS_details')->add(array(
        'markup' => '
          <a class="no-popup button" href="' . CRM_Utils_System::url('civicrm/ds/open-search', array('reset' => 1, 'cid' => $contactId)) . '">
            <span>' . ts('New Donor Search', array('domain' => 'org.civicrm.donorsearch')) . '</span>
          </a>
        ',
      ));
    }
  }
}

/**
 * @inheritDoc
 */
function donorsearch_civicrm_tabset($link, &$allTabs, $context) {
  // hide custom group 'DonorSearch' if user doesn't have 'access DonorSearch' permission
  if (!CRM_Core_Permission::check('access DonorSearch') && $link == 'civicrm/contact/view') {
    $customGroupID = CRM_Core_DAO::getFieldValue('CRM_Core_DAO_CustomGroup', 'DS_details', 'id', 'name');
    $key = array_search("custom_$customGroupID", CRM_Utils_Array::collect('id', $allTabs));
    if (!empty($allTabs)) {
      unset($allTabs[$key]);
    }
  }
}

/**
 * @inheritDoc
 */
function donorSearch_civicrm_summaryActions(&$menu, $contactId) {
  // show action link 'View Donor Search Profile' if user have 'access DonorSearch' permission
  if (CRM_Core_Permission::check('access DonorSearch')) {
    $count = civicrm_api3('DonorSearch', 'getcount', array('contact_id' => $contactId));
    if ($count) {
      $menu += array(
        'view-ds-profile' => array(
          'title' => ts('Donor Search Profile', array('domain' => 'org.civicrm.donorsearch')),
          'ref' => 'ds-profile',
          'key' => 'view-ds-profile',
          'href' => CRM_Utils_System::url('civicrm/view/ds-profile', 'reset=1'),
          'weight' => 100,
          'class' => 'no-popup',
          'permissions' => array('access DonorSearch'),
        ),
      );
    }
    else {
      $menu += array(
        'add-ds-profile' => array(
          'title' => ts('New Donor Search', array('domain' => 'org.civicrm.donorsearch')),
          'ref' => 'ds-profile',
          'key' => 'add-ds-profile',
          'href' => CRM_Utils_System::url('civicrm/ds/open-search', array('reset' => 1, 'cid' => $contactId)),
          'weight' => 100,
          'class' => 'no-popup',
          'permissions' => array('access DonorSearch'),
        ),
      );
    }
  }
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @param $op string, the type of operation being performed; 'check' or 'enqueue'
 * @param $queue CRM_Queue_Queue, (for 'enqueue') the modifiable list of pending up upgrade tasks
 *
 * @return mixed
 *   Based on op. for 'check', returns array(boolean) (TRUE if upgrades are pending)
 *                for 'enqueue', returns void
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_upgrade
 */
function donorsearch_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _donorsearch_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_managed().
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_managed
 */
function donorsearch_civicrm_managed(&$entities) {
  _donorsearch_civix_civicrm_managed($entities);
}

/**
 * Implements hook_civicrm_entityTypes().
 */
function donorsearch_civicrm_entityTypes(&$entityTypes) {
  $entityTypes[] = array(
    'name' => 'DonorSearch',
    'class' => 'CRM_DonorSearch_DAO_SavedSearch',
    'table' => 'civicrm_ds_saved_search',
  );
}

/**
 * Implements hook_civicrm_alterAPIPermissions().
 */
function donorsearch_civicrm_alterAPIPermissions($entity, $action, $params, &$permissions) {
  $permissions['donor_search'] = array(
    'default' => array('access DonorSearch'),
  );
}

/**
 * Implements hook_civicrm_angularModules().
 *
 * Generate a list of Angular modules.
 *
 * Note: This hook only runs in CiviCRM 4.5+. It may
 * use features only available in v4.6+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function donorsearch_civicrm_angularModules(&$angularModules) {
  _donorsearch_civix_civicrm_angularModules($angularModules);
}

/**
 * Implements hook_civicrm_alterSettingsFolders().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_alterSettingsFolders
 */
function donorsearch_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _donorsearch_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

/**
 * Functions below this ship commented out. Uncomment as required.
 *

/**
 * Implements hook_civicrm_preProcess().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_preProcess
 *
function donorsearch_civicrm_preProcess($formName, &$form) {

} // */

/**
 * Implements hook_civicrm_navigationMenu().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_navigationMenu
 *
function donorsearch_civicrm_navigationMenu(&$menu) {
  _donorsearch_civix_insert_navigation_menu($menu, NULL, array(
    'label' => ts('The Page', array('domain' => 'org.civicrm.donorsearch')),
    'name' => 'the_page',
    'url' => 'civicrm/the-page',
    'permission' => 'access CiviReport,access CiviContribute',
    'operator' => 'OR',
    'separator' => 0,
  ));
  _donorsearch_civix_navigationMenu($menu);
} // */
