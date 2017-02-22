<?php

class CRM_DonorSearch_Page_View extends CRM_Core_Page {

  public $useLivePageJS = TRUE;

  public function run() {
    $dao = new CRM_DonorSearch_DAO_SavedSearch();
    $headers = array(
      ts('Donor Name'),
      ts('Address'),
      ts('State'),
      ts('Spouse Name'),
      ts('Employer'),
      ts('Search Performed by'),
      '',
    );
    $this->assign('headers', $headers);

    $donorSearches = array();
    $dao->find();
    while ($dao->fetch()) {
      $criteria = unserialize($dao->search_criteria);
      $donorSearches[$dao->id] = array(
        'IS' => CRM_Utils_System::url('civicrm/ds/integrated-search', "id=" . $dao->id),
      );
      if ($dao->creator_id) {
        $donorSearches[$dao->id]['creator'] = sprintf("<a href=%s>%s</a>",
          CRM_Utils_System::url('civicrm/contact/view', array("cid" => $dao->creator_id)),
          CRM_Contact_BAO_Contact::displayName($dao->creator_id)
        );
      }
      $donorSearches[$dao->id]['donor_name'] = sprintf('<a href=%s title="View DonorSearch details" class="action-item">%s %s %s</a>',
        CRM_DonorSearch_Util::getDonorSearchDetailsLink($criteria['id']),
        $criteria['dFname'],
        CRM_Utils_Array::value('dMname', $criteria, ''),
        $criteria['dLname']
      );
      $donorSearches[$dao->id]['address'] = sprintf('%s<br />%s',
        CRM_Utils_Array::value('dAddress', $criteria, ''),
        CRM_Utils_Array::value('dCity', $criteria, '')
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
