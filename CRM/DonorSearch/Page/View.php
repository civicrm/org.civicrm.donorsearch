<?php

require_once 'CRM/Core/Page.php';

class CRM_DonorSearch_Page_View extends CRM_Core_Page {

  static $_links = null;

  function &links() {
      if (!(self::$_links)) {
        self::$_links = array(
          CRM_Core_Action::VIEW => array(
            'name'  => ts('Integrated Search'),
            'url'   => 'civicrm/ds/integrated-search',
            'qs'    => 'id=%%id%%&reset=1',
            'title' => ts('Donor Integrated Search'),
          ),
          CRM_Core_Action::UPDATE => array(
            'name'  => ts('Edit'),
            'url'   => 'civicrm/ds/open-search',
            'qs'    => 'id=%%id%%&reset=1',
            'title' => ts('Edit Donor Search'),
          ),
          CRM_Core_Action::DELETE => array(
            'name'  => ts('Delete'),
            'url'   => 'civicrm/ds/delete',
            'qs'    => 'id=%%id%%',
            'title' => ts('Delete Donor Search'),
          ),
        );
      }
      return self::$_links;
    }

  public function run() {
    CRM_Utils_System::setTitle(ts('List of Donor Searches'));

    $dao = new CRM_DonorSearch_DAO_SavedSearch();
    $headers = array(
      ts('Searched For'),
      ts('Donor Name'),
      ts('Address'),
      ts('State'),
      ts('Donor\'s Spouse Name'),
      ts('Employer'),
      ts('Actions'),
    );
    $this->assign('headers', $headers);

    $donorSearches = array();
    $dao->find();
    while ($dao->fetch()) {
      $criteria = unserialize($dao->search_criteria);
      $donorSearches[$dao->id] = array(
        'searched_for' => sprintf("<a href=%s>%s</a>",
          CRM_Utils_System::url('civicrm/contact/view/', "id=" . $criteria['id']),
          CRM_Contact_BAO_Contact::displayName($criteria['id'])
        )
      );
      $donorSearches[$dao->id]['donor_name'] = sprintf('%s %s %s',
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
      $action = array_sum(array_keys($this->links()));
      $donorSearches[$dao->id]['more'] = CRM_Core_Action::formLink(self::links(),
        $action,
        array('id' => $dao->id),
        ts('More'),
        TRUE,
        'donorsearch.configure.actions',
        'DonorSearch',
        $dao->id
      );
    }
    $this->assign('rows', $donorSearches);

    parent::run();
  }
}
