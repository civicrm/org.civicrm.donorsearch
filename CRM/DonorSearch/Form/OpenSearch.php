<?php

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

    $searchArgs = array(
      "key=$this->_apiKey",
      "id=1",
    );
    $searchFieldValues = $searchArgs;
    foreach (CRM_DonorSearch_FieldInfo::getBasicSearchFields() as $name) {
      if (!empty($values[$name])) {
        if (in_array($name, array('dAddress', 'dCity'))) {
          $values[$name] = str_replace(' ', '+', $values[$name]);
        }
        $searchArgs[] = sprintf('%s=%s', $name, $values[$name]);
        $searchFieldValues[$name] = $values[$name];
      }
    }

    CRM_Core_BAO_Cache::setItem($searchFieldValues, 'donor search', 'previous search data');

    $url = "https://www.donorlead.net/API/display.php?" . implode('&', $searchArgs);
    CRM_Utils_System::redirect($url);
  }

}
