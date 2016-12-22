<?php

require_once 'donorsearch.civix.php';

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
  $customGroup = civicrm_api3('custom_group', 'create', array(
    'title' => 'Donor Search details',
    'name' => 'DS_details',
    'extends' => 'Contact',
    'domain_id' => CRM_Core_Config::domainID(),
    'style' => 'Tab',
    'is_active' => 1,
    'collapse_adv_display' => 0,
    'collapse_display' => 0
  ));

  foreach (CRM_DonorSearch_FieldInfo::getAttributes() as $param) {
    civicrm_api3('custom_field', 'create', array_merge($param, array(
      'custom_group_id' => $customGroup['id'],
      'is_searchable' => 1,
      'is_view' => 1,
    )));
  }
  _donorsearch_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_uninstall
 */
function donorsearch_civicrm_uninstall() {
  $customGroupID = civicrm_api3('custom_group', 'getvalue', array(
    'name' => 'DS_details',
    'return' => 'id',
  ));
  if (!empty($customGroupID)) {
    foreach (CRM_DonorSearch_FieldInfo::getAttributes() as $param) {
      $customFieldID = civicrm_api3('custom_field', 'getvalue', array(
        'custom_group_id' => $customGroupID,
        'name' => $param['name'],
        'return' => 'id',
      ));
      if (!empty($customFieldID)) {
        civicrm_api3('custom_field', 'delete', array('id' => $customFieldID));
      }
    }
    civicrm_api3('custom_group', 'delete', array('id' => $customGroupID));
  }
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
 * Implements hook_civicrm_caseTypes().
 *
 * Generate a list of case-types.
 *
 * @param array $caseTypes
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function donorsearch_civicrm_caseTypes(&$caseTypes) {
  _donorsearch_civix_civicrm_caseTypes($caseTypes);
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
