<?php

/**
 * Collection of upgrade steps.
 */
class CRM_DonorSearch_Upgrader extends CRM_DonorSearch_Upgrader_Base {

  // By convention, functions that look like "function upgrade_NNNN()" are
  // upgrade tasks. They are executed in order (like Drupal's hook_update_N).

  /**
   * Example: Run an external SQL script when the module is installed.
   */
  public function install() {
    civicrm_api3('Navigation', 'create', array(
      'label' => ts('Register Donor Search API Key', array('domain' => 'org.civicrm.donorsearch')),
      'name' => 'ds_register_api',
      'url' => 'civicrm/ds/register?reset=1',
      'domain_id' => CRM_Core_Config::domainID(),
      'is_active' => 1,
      'parent_id' => civicrm_api3('Navigation', 'getvalue', array(
        'return' => "id",
        'name' => "System Settings",
      )),
      'permission' => 'administer CiviCRM',
    ));

    civicrm_api3('Navigation', 'create', array(
      'id' => civicrm_api3('Navigation', 'getvalue', array(
        'return' => "id",
        'name' => "Find and Merge Duplicate Contacts",
      )),
      'has_separator' => 1,
    ));
    $params = array(
      array(
        'label' => ts('View Donor Search', array('domain' => 'org.civicrm.donorsearch')),
        'name' => 'ds_view',
        'url' => 'civicrm/ds/view?reset=1',
      ),
      array(
        'label' => ts('New Donor Search', array('domain' => 'org.civicrm.donorsearch')),
        'name' => 'ds_new',
        'url' => 'civicrm/ds/open-search?reset=1',
      ),
    );
    foreach ($params as $param) {
      civicrm_api3('Navigation', 'create', array_merge($param,
        array(
          'domain_id' => CRM_Core_Config::domainID(),
          'is_active' => 1,
          'parent_id' => civicrm_api3('Navigation', 'getvalue', array(
            'return' => "id",
            'name' => "Contacts",
          )),
          'permission' => 'access DonorSearch',
        )
      ));
    }

    $customGroup = civicrm_api3('custom_group', 'create', array(
      'title' => ts('Donor Search', array('domain' => 'org.civicrm.donorsearch')),
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
    CRM_DonorSearch_FieldInfo::getXMLToCustomFieldNameMap();
  }

  /**
   * Example: Run an external SQL script when the module is uninstalled.
   */
  public function uninstall() {
    self::changeNavigation('delete');

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

    // delete 'donor search' cache
    CRM_Core_BAO_Cache::deleteGroup('donor search');

    // delete Donor Search API key
    Civi::settings()->revert('ds_api_key');
  }

  /**
   * Example: Run a simple query when a module is enabled.
   */
  public function enable() {
    self::changeNavigation('enable');
  }

  /**
   * Example: Run a simple query when a module is disabled.
   */
  public function disable() {
    self::changeNavigation('disable');
  }

  /**
   * disable/enable/delete Donor Search links
   *
   * @param string $action
   * @throws \CiviCRM_API3_Exception
   */
  public static function changeNavigation($action) {
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
   * Example: Run a couple simple queries.
   *
   * @return TRUE on success
   * @throws Exception
   *
  public function upgrade_4200() {
    $this->ctx->log->info('Applying update 4200');
    CRM_Core_DAO::executeQuery('UPDATE foo SET bar = "whiz"');
    CRM_Core_DAO::executeQuery('DELETE FROM bang WHERE willy = wonka(2)');
    return TRUE;
  } // */


  /**
   * Example: Run an external SQL script.
   *
   * @return TRUE on success
   * @throws Exception
  public function upgrade_4201() {
    $this->ctx->log->info('Applying update 4201');
    // this path is relative to the extension base dir
    $this->executeSqlFile('sql/upgrade_4201.sql');
    return TRUE;
  } // */


  /**
   * Example: Run a slow upgrade process by breaking it up into smaller chunk.
   *
   * @return TRUE on success
   * @throws Exception
  public function upgrade_4202() {
    $this->ctx->log->info('Planning update 4202'); // PEAR Log interface

    $this->addTask(ts('Process first step'), 'processPart1', $arg1, $arg2);
    $this->addTask(ts('Process second step'), 'processPart2', $arg3, $arg4);
    $this->addTask(ts('Process second step'), 'processPart3', $arg5);
    return TRUE;
  }
  public function processPart1($arg1, $arg2) { sleep(10); return TRUE; }
  public function processPart2($arg3, $arg4) { sleep(10); return TRUE; }
  public function processPart3($arg5) { sleep(10); return TRUE; }
  // */


  /**
   * Example: Run an upgrade with a query that touches many (potentially
   * millions) of records by breaking it up into smaller chunks.
   *
   * @return TRUE on success
   * @throws Exception
  public function upgrade_4203() {
    $this->ctx->log->info('Planning update 4203'); // PEAR Log interface

    $minId = CRM_Core_DAO::singleValueQuery('SELECT coalesce(min(id),0) FROM civicrm_contribution');
    $maxId = CRM_Core_DAO::singleValueQuery('SELECT coalesce(max(id),0) FROM civicrm_contribution');
    for ($startId = $minId; $startId <= $maxId; $startId += self::BATCH_SIZE) {
      $endId = $startId + self::BATCH_SIZE - 1;
      $title = ts('Upgrade Batch (%1 => %2)', array(
        1 => $startId,
        2 => $endId,
      ));
      $sql = '
        UPDATE civicrm_contribution SET foobar = whiz(wonky()+wanker)
        WHERE id BETWEEN %1 and %2
      ';
      $params = array(
        1 => array($startId, 'Integer'),
        2 => array($endId, 'Integer'),
      );
      $this->addTask($title, 'executeSql', $sql, $params);
    }
    return TRUE;
  } // */

}
