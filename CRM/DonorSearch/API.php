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

class CRM_DonorSearch_API {

  public static $_singleton = NULL;

  protected $_searchParams;

  protected $_httpClient;

  public function __construct($searchParams) {
    $this->_searchParams = $searchParams;
    $this->_httpClient = new CRM_Utils_HttpClient();
  }

  public static function &singleton($searchParams) {
    if (self::$_singleton === NULL) {
      self::$_singleton = new CRM_DonorSearch_API($searchParams);
    }
    return self::$_singleton;
  }

  public function sendRequest($apiName) {
    $searchArgs = array();
    if ($apiName == 'get') {
      foreach (array('key', 'id') as $arg) {
        $searchArgs[] = "$arg=" . $this->_searchParams[$arg];
      }
    }
    else {
      if ($apiName == 'send') {
        $this->_searchParams['Redirect'] = 1;
      }
      foreach ($this->_searchParams as $arg => $value) {
        $searchArgs[] = "$arg=$value";
      }
    }

    $url = sprintf("https://www.donorlead.net/API/%s.php?%s", $apiName, implode('&', $searchArgs));
    list($status, $response) = $this->_httpClient->get($url);

    return array(
      CRM_DonorSearch_Util::throwDSError($response),
      $response,
    );
  }

}
