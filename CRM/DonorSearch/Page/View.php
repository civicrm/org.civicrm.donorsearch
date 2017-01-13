<?php

require_once 'CRM/Core/Page.php';

class CRM_DonorSearch_Page_View extends CRM_Core_Page {

  public $useLivePageJS = TRUE;

  public function run() {
    $dao = new CRM_DonorSearch_DAO_SavedSearch();
    $headers = array(
      '',
      '',
      ts('Donor Name'),
      ts('Address'),
      ts('State'),
      ts('Donor\'s Spouse Name'),
      ts('Employer'),
      ts('Search performed by'),
    );
    $this->assign('headers', $headers);

    $donorSearches = array();
    $dao->find();
    while ($dao->fetch()) {
      $criteria = unserialize($dao->search_criteria);
      $donorSearches[$dao->id] = array(
        'IS' => sprintf("<a href=%s title='Integrated Search' class='action-item medium-popup'><i class=\"crm-i fa-pencil\"></i></a>",
          CRM_Utils_System::url('civicrm/ds/integrated-search', "id=" . $dao->id)
        ),
        'delete' => sprintf("<a href=%s title='Delete'><i class=\"crm-i fa-trash\"></i></a>",
          CRM_Utils_System::url('civicrm/ds/delete', "id=" . $dao->id)
        ),
        'searched_for' => sprintf("<a href=%s>%s</a>",
          CRM_Utils_System::url('civicrm/contact/view', "cid=" . $criteria['id']),
          CRM_Contact_BAO_Contact::displayName($criteria['id'])
        ),
      );
      $donorSearches[$dao->id]['donor_name'] = sprintf('<a href=%s title="View DonorSearch details" class="action-item">%s %s %s</a>',
        CRM_DonorSearch_Util::getDonorSearchDetailsLink($criteria['id']),
        $criteria['dFname'],
        CRM_Utils_Array::value('dMname', $criteria, ''),
        $criteria['dLname']
      );
      $donorSearches[$dao->id]['address'] = sprintf('%s <br />%s %s',
        CRM_Utils_Array::value('dAddress', $criteria, ''),
        CRM_Utils_Array::value('dCity', $criteria, ''),
        CRM_Utils_Array::value('dZip', $criteria, '')
      );
      $donorSearches[$dao->id]['state'] = $criteria['dState'];
      $donorSearches[$dao->id]['donor_spouse_name'] = sprintf('%s %s %s',
        CRM_Utils_Array::value('dSFname', $criteria, ''),
        CRM_Utils_Array::value('dSMname', $criteria, ''),
        CRM_Utils_Array::value('dSLname', $criteria, '')
      );
      $donorSearches[$dao->id]['employer'] = CRM_Utils_Array::value('dEmployer', $criteria, '');
    }
    $this->assign('rows', $donorSearches);

    parent::run();
  }
}
