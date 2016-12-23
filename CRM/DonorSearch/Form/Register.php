<?php

require_once 'CRM/Core/Form.php';

/**
 * Form controller class
 *
 * @see http://wiki.civicrm.org/confluence/display/CRMDOC43/QuickForm+Reference
 */
class CRM_DonorSearch_Form_Register extends CRM_Core_Form {

  /**
   * Donor Search API key
   *
   * @var string
   */
  protected $_apiKey;

  /**
   * Set variables up before form is built.
   */
  public function preProcess() {
    $this->_apiKey = Civi::settings()->get('ds_api_key');
  }

  /**
   * Set default values.
   *
   * @return array
   */
  public function setDefaultValues() {
    $defaults = array('api_key' => $this->_apiKey);
    return $defaults;
  }

  /**
   * Build the form object.
   */
  public function buildQuickForm() {
    $collapsible = empty($this->_apiKey) ? FALSE : TRUE;
    $this->assign('collapsible', $collapsible);

    $this->add('text', 'user', ts('Username'), array('class' => 'huge'));
    $this->add('password', 'pass', ts('Password'), array('class' => 'huge'));
    $this->add('password', 'api_key', ts('API Key'), array('class' => 'huge'));

    $this->addFormRule(array('CRM_DonorSearch_Form_Register', 'formRule'), $this);

    $this->addButtons(array(
      array(
        'type' => 'submit',
        'name' => ts('Submit'),
        'isDefault' => TRUE,
      ),
    ));

    parent::buildQuickForm();
  }

  /**
   * Global form rule.
   *
   * @param array $fields
   *   The input form values.
   * @param array $files
   *   The uploaded files if any.
   * @param $self
   *
   * @return bool|array
   *   true if no errors, else array of errors
   */
  public static function formRule($fields, $files, $self) {
    $errors = array();
    if (empty($fields['api_key'])) {
      $params = array(
        'user' => 'Username',
        'pass' => 'Password',
      );
      foreach($params as $name => $label) {
        if (empty($fields[$name])) {
          $errors[$name] = ts("Please enter %1", array(1 => $label));
        }
      }
    }

    return $errors;
  }

  /**
   * Process the form submission.
   */
  public function postProcess() {
    $values = $this->exportValues();
    $apiKey = CRM_Utils_Array::value('api_key', $values);
    if (empty($apiKey)) {
      $url = sprintf("https://www.donorlead.net/API/getKey.php?user=%s&pass=%s", $values['user'], $values['pass']);
      $httpClient = new CRM_Utils_HttpClient();
      list($status, $response) = $httpClient->get($url);
      if ($response == 'Error') {
        CRM_Core_Session::setStatus(ts("Invalid username and/or password provided OR<br /> API key is already generated"), ts('Error'), 'error');
        return;
      }
      elseif ($response == 'API key already created') {
        CRM_Core_Session::setStatus(ts("API Key already generated"), ts('Warning'));
        return;
      }
      $apiKey = $response;
    }

    Civi::settings()->set('ds_api_key', $apiKey);
    CRM_Core_Session::setStatus(ts("Donor Search API key registered"), ts('Success'), 'success');
  }

}
