<?php
/*
 +--------------------------------------------------------------------+
 | CiviCRM version 4.7                                                |
 +--------------------------------------------------------------------+
 | Copyright CiviCRM LLC (c) 2004-2016                                |
 +--------------------------------------------------------------------+
 | This file is a part of CiviCRM.                                    |
 |                                                                    |
 | CiviCRM is free software; you can copy, modify, and distribute it  |
 | under the terms of the GNU Affero General Public License           |
 | Version 3, 19 November 2007 and the CiviCRM Licensing Exception.   |
 |                                                                    |
 | CiviCRM is distributed in the hope that it will be useful, but     |
 | WITHOUT ANY WARRANTY; without even the implied warranty of         |
 | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.               |
 | See the GNU Affero General Public License for more details.        |
 |                                                                    |
 | You should have received a copy of the GNU Affero General Public   |
 | License and the CiviCRM Licensing Exception along                  |
 | with this program; if not, contact CiviCRM LLC                     |
 | at info[AT]civicrm[DOT]org. If you have questions about the        |
 | GNU Affero General Public License or the licensing of CiviCRM,     |
 | see the CiviCRM license FAQ at http://civicrm.org/licensing        |
 +--------------------------------------------------------------------+
 */

require_once 'CRM/Core/Form.php';

/**
 * Form controller class
 *
 * @see http://wiki.civicrm.org/confluence/display/CRMDOC43/QuickForm+Reference
 */
class CRM_DonorSearch_Form_OpenSearch extends CRM_Core_Form {

  protected $_apiKey;

  /**
   * Set variables up before form is built.
   */
  public function preProcess() {
    $this->_apiKey = Civi::settings()->get('ds_api_key');

    if (empty($this->_apiKey)) {
      CRM_Core_Error::fatal(ts("Donor Search API key missing."));
    }
  }

  /**
   * Set default values.
   *
   * @return array
   */
  public function setDefaultValues() {
    $defaults = CRM_Core_BAO_Cache::getItem('donor search', 'previous search data');

    // load with sample search data if cached DS data not found
    if (empty($defaults)) {
      $defaults = array(
        'dFname' => 'Kevin',
        'dLname' => 'Plank',
        'dState' => 'MD',
      );
    }

    return $defaults;
  }

  public function buildQuickForm() {
    $this->add('text', 'dFname', ts('First Name'), array(), TRUE);
    $this->add('text', 'dMname', ts('Middle Name'));
    $this->add('text', 'dLname', ts('Last Name'), array(), TRUE);
    $this->add('text', 'dAddress', ts('Address'), array('maxlength' => 75));
    $this->add('text', 'dCity', ts('City'), array('maxlength' => 30));
    $this->add('text', 'dZip', ts('Zip'));
    $this->add('text', 'dState', ts('State'), array('size' => 2, 'maxlength' => 2), TRUE);
    $this->add('text', 'dSFname', ts('Spouse First Name'));
    $this->add('text', 'dSMname', ts('Spouse Middle Name'));
    $this->add('text', 'dSLname', ts('Spouse Last Name'));
    $this->add('text', 'dEmployer', ts('Employer'));

    $this->assign('donorFields', CRM_DonorSearch_FieldInfo::getBasicSearchFields());

    $this->addButtons(array(
      array(
        'type' => 'submit',
        'name' => ts('Search'),
        'isDefault' => TRUE,
      ),
    ));
  }

  public function postProcess() {
    $values = $this->exportValues();

    $searchFieldValues = $this->formatFormValue($values);

    CRM_Core_BAO_Cache::setItem($searchFieldValues, 'donor search', 'previous search data');

    list($isError, $response) = CRM_DonorSearch_API::singleton($searchFieldValues)->sendRequest('send');

    if ($isError) {
      $url = CRM_Utils_System::url('civicrm/ds/integrated-search', 'reset=1');
    }
    else {
      $xmlData = CRM_DonorSearch_Util::processDSData($response, $searchFieldValues['id']);
      $url = $xmlData['profile_link'];
    }

    CRM_Utils_System::redirect($url);
  }

  public function formatFormValue($values) {
    $searchFieldValues = array(
      'key' => $this->_apiKey,
      'id' => CRM_Core_Session::getLoggedInContactID(),
    );
    foreach (CRM_DonorSearch_FieldInfo::getBasicSearchFields() as $name) {
      if (!empty($values[$name])) {
        if (in_array($name, array('dAddress', 'dCity'))) {
          $values[$name] = str_replace(' ', '+', $values[$name]);
        }
        $searchFieldValues[$name] = $values[$name];
      }
    }

    return $searchFieldValues;
  }

}
