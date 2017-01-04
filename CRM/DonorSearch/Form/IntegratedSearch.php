<?php

require_once 'CRM/Core/Form.php';

/**
 * Form controller class
 *
 * @see http://wiki.civicrm.org/confluence/display/CRMDOC43/QuickForm+Reference
 */
class CRM_DonorSearch_Form_IntegratedSearch extends CRM_Core_Form {

  /**
   * Set variables up before form is built.
   */
  public function preProcess() {
    $this->assign('id', CRM_Utils_Request::retrieve('id', 'Positive', $this, TRUE));
  }

}
