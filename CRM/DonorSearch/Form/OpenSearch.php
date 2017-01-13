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

  protected $_id;

  /**
   * Set variables up before form is built.
   */
  public function preProcess() {
    $this->_id = CRM_Utils_Request::retrieve('id', 'Positive', $this, FALSE, NULL, 'GET');
    $this->_apiKey = Civi::settings()->get('ds_api_key');

    if (empty($this->_apiKey)) {
      CRM_Core_Error::fatal(ts("Donor Search API key missing. Navigate to Administer >> System Settings >> Register Donor Search API Key to register API key"));
    }

  }

  /**
   * Set default values.
   *
   * @return array
   */
  public function setDefaultValues() {
    $defaults = array();

    if ($this->_id) {
      $defaults = unserialize(CRM_Core_DAO::getFieldValue('CRM_DonorSearch_DAO_SavedSearch', $this->_id, 'search_criteria'));
    }

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

  /**
   * Build the form object.
   */
  public function buildQuickForm() {
    $this->addEntityRef('id', ts('Searched for'), array('create' => TRUE), TRUE);
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

  /**
   * Process the form submission.
   */
  public function postProcess() {
    $values = $this->exportValues();
    // format form values
    $searchFieldValues = $this->formatFormValue($values);

    $dao = new CRM_DonorSearch_DAO_SavedSearch();
    if ($this->_id) {
      $dao->id = $this->_id;
    }
    $dao->search_criteria = serialize($searchFieldValues);
    $dao->save();

    // execute DS send API with provided search parameters
    list($isError, $response) = CRM_DonorSearch_API::singleton($searchFieldValues)->send();
    // if there's any error redirect to integrated-search page
    if ($isError) {
      $url = CRM_Utils_System::url('civicrm/ds/view', 'reset=1');
    }
    // on successful submission populate the custom fields with desired DS data and redirect to DS profile
    else {
      CRM_DonorSearch_Util::processDSData($response, $searchFieldValues['id']);
      $url = CRM_DonorSearch_Util::getDonorSearchDetailsLink($searchFieldValues['id']);
    }

    CRM_Utils_System::redirect($url);
  }

  /**
   * Format form-values
   *
   * @param array $values
   *
   * @return array
   */
  public function formatFormValue($values) {
    $searchFieldValues = array('key' => $this->_apiKey);
    foreach (CRM_DonorSearch_FieldInfo::getBasicSearchFields() as $name) {
      if (!empty($values[$name])) {
        $searchFieldValues[$name] = $values[$name];
      }
    }

    return $searchFieldValues;
  }

}
