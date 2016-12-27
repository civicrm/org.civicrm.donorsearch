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

class CRM_DonorSearch_Util {

  public static function updateRecord() {
    $apiKey = Civi::settings()->get('ds_api_key');
    if (empty($apiKey)) {
      CRM_Core_Error::fatal(ts("Donor Search API key missing."));
    }

    $previousDSparams = CRM_Core_BAO_Cache::getItem('donor search', 'previous search data');
    if (empty($previousDSparams['id'])) {
      $previousDSparams['id'] = CRM_Core_Session::getLoggedInContactID();
    }
    if (empty($previousDSparams['key'])) {
      $previousDSparams['key'] = $apiKey;
    }

    $apiRequest = CRM_DonorSearch_API::singleton($previousDSparams);
    list($isError, $response) = $apiRequest->sendRequest('get');

    if ($isError && (trim($response) == 'No records found')) {
      if (!empty($previousDSparams)) {
        list($isError, $response) = $apiRequest->sendRequest('send');
      }
    }

    if (!$isError) {
      self::processDSData($response, $previousDSparams['id']);
    }

    CRM_Core_Session::setStatus(ts("DS Record updated for Contact ID - " . $previousDSparams['id']), ts('Success'), 'success');
    CRM_Utils_System::redirect(CRM_Utils_System::url('civicrm/ds/integrated-search', 'reset=1'));
  }

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

  public static function processDSData($response, $contactID) {
    $response = html_entity_decode(str_replace('<pre>', '', $response));
    list($xml, $error) = CRM_Utils_XML::parseString($response);
    if ($error) {
      CRM_Core_Error::fatal(ts($error));
    }

    $xmlToFieldMap = CRM_DonorSearch_FieldInfo::getXMLToCustomFieldNameMap();
    $xmlData = CRM_Utils_XML::xmlObjToArray($xml);

    $param = array('id' => $contactID);
    foreach ($xmlData as $xmlName => $value) {
      // as per the documentation there are few attributes which are optional and can be ignored
      if (!array_key_exists($xmlName, $xmlToFieldMap)) {
        continue;
      }
      $param[$xmlToFieldMap[$xmlName]] = $value;
    }

    civicrm_api3('Contact', 'create', $param);

    return $xmlData;
  }
}
