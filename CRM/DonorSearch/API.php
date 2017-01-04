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

/**
 * Class to send Donor Search API request
 */
class CRM_DonorSearch_API {

  /**
   * Instance of this object.
   *
   * @var CRM_DonorSearch_API
   */
  public static $_singleton = NULL;

  /**
   * Search parameters later formated into API url arguments
   *
   * @var array
   */
  protected $_searchParams;

  /**
   * Instance of CRM_Utils_HttpClient
   *
   * @var CRM_Utils_HttpClient
   */
  protected $_httpClient;

  /**
   * The constructor sets search parameters and instantiate CRM_Utils_HttpClient
   */
  public function __construct($searchParams) {
    $this->_searchParams = $searchParams;
    $this->_httpClient = new CRM_Utils_HttpClient();
  }

  /**
   * Singleton function used to manage this object.
   *
   * @param array $searchParams
   *   Donor Search parameters
   *
   * @return CRM_DonorSearch_API
   */
  public static function &singleton($searchParams) {
    if (self::$_singleton === NULL) {
      self::$_singleton = new CRM_DonorSearch_API($searchParams);
    }
    return self::$_singleton;
  }

  /**
   * Function to make Donor Search send API request
   */
  public function get() {
    return $this->sendRequest('get');
  }

  /**
   * Function to make Donor Search send API request
   */
  public function send() {
    return $this->sendRequest('send');
  }

  /**
   * Function to make Donor Search getKey API request
   */
  public function getKey() {
    return $this->sendRequest('getKey');
  }

  /**
   * Function used to make Donor Search API request
   *
   * @param string $apiName
   *   Donor Search API names which are get, getKey, send and display
   *
   * @return array
   */
  public function sendRequest($apiName) {
    $searchArgs = array();
    // for Get API consider only api key and search id as search arguments
    if ($apiName == 'get') {
      foreach (array('key', 'id') as $arg) {
        $searchArgs[] = "$arg=" . $this->_searchParams[$arg];
      }
    }
    else {
      // for Send API add 'redirect = 1' parameter for getting DS data in return
      // later used to update contact
      if ($apiName == 'send') {
        $this->_searchParams['Redirect'] = 1;
      }
      // Format search parameters into url arguments i.e. array(attr => value) to 'attr=value'
      foreach ($this->_searchParams as $arg => $value) {
        $searchArgs[] = "$arg=$value";
      }
    }

    // send API request with desired search arguments
    $url = sprintf("https://www.donorlead.net/API/%s.php?%s", $apiName, str_replace(' ', '+', implode('&', $searchArgs)));
    list($status, $response) = $this->_httpClient->get($url);

    return array(
      self::throwDSError($response),
      $response,
    );
  }

  /**
   * Show error/warning if there's anything wrong in $response
   *
   * @param string $response
   *   fetched data from DS API
   *
   * @return bool
   *   Found error ? TRUE or FALSE
   */
  public static function throwDSError($response) {
    $isError = TRUE;
    switch (trim($response)) {
      case 'Key not Valid':
        CRM_Core_Session::setStatus(ts("Donor Search API Key is not valid"), ts('Error'), 'error');
        break;

      case 'API key already created':
        CRM_Core_Session::setStatus(ts("API Key already generated"), ts('Warning'));
        break;

      case 'Error':
        CRM_Core_Session::setStatus(ts("Invalid username and/or password provided OR<br /> API key is already generated"), ts('Error'), 'error');
        break;

      case 'No records found':
        CRM_Core_Session::setStatus(ts("No Donor Search record found"), ts('Warning'));
        break;

      default:
        $isError = FALSE;
        break;
    }

    return $isError;
  }

}
