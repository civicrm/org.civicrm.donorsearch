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
 * One place to store frequently used Donor Search variables.
 *
 * @package CRM
 * @copyright CiviCRM LLC (c) 2004-2016
 * $Id$
 *
 */
class CRM_DonorSearch_FieldInfo {

  /**
   * Return array of Donor Search fields where key is the XML name and value got attributes of corrosponding custom field
   *
   * @return array
   */
  public static function getAttributes() {
    return array(
      'DS_Rating' => array(
        'name' => 'ds_rating',
        'label' => ts('DS Rating', array('domain' => 'org.civicrm.donorsearch')),
        'text_length' => 5,
        'data_type' => 'String',
        'html_type' => 'Text',
      ),
      'Overall' => array(
        'name' => 'overall',
        'label' => ts('Overall', array('domain' => 'org.civicrm.donorsearch')),
        'text_length' => 5,
        'data_type' => 'Float',
        'html_type' => 'Text',
        'is_search_range' => 1,
      ),
      'LargestGiftLow' => array(
        'name' => 'largest_gift_low',
        'label' => ts('Largest gift found lower range', array('domain' => 'org.civicrm.donorsearch')),
        'text_length' => 20,
        'data_type' => 'Money',
        'html_type' => 'Text',
        'is_search_range' => 1,
      ),
      'LargestGiftHigh' => array(
        'name' => 'largest_gift_high',
        'label' => ts('Largest gift found higher range', array('domain' => 'org.civicrm.donorsearch')),
        'text_length' => 20,
        'data_type' => 'Money',
        'html_type' => 'Text',
        'is_search_range' => 1,
      ),
      'CapacityRange' => array(
        'name' => 'capacity_range',
        'label' => ts('Wealth capacity range', array('domain' => 'org.civicrm.donorsearch')),
        'text_length' => 35,
        'data_type' => 'String',
        'html_type' => 'Text',
      ),
      'RealEstateEst' => array(
        'name' => 'real_estate_est',
        'label' => ts('Real estate estimate', array('domain' => 'org.civicrm.donorsearch')),
        'text_length' => 20,
        'data_type' => 'Money',
        'html_type' => 'Text',
        'is_search_range' => 1,
      ),
      'profile_link' => array(
        'name' => 'profile_link',
        'label' => ts('Profile', array('domain' => 'org.civicrm.donorsearch')),
        'data_type' => 'Link',
        'html_type' => 'Link',
      ),
      'submit_time' => array(
        'name' => 'submit_time',
        'label' => ts('Submit time', array('domain' => 'org.civicrm.donorsearch')),
        'data_type' => 'Date',
        'html_type' => 'Select Date',
        'date_format' => 'yy-mm-dd',
        'time_format' => 2,
        'is_search_range' => 1,
      ),
      'RealEstateCount' => array(
        'name' => 'real_estate_count',
        'label' => ts('Real estate count', array('domain' => 'org.civicrm.donorsearch')),
        'data_type' => 'Int',
        'html_type' => 'Text',
        'is_search_range' => 1,
      ),
      'LikelyMatchesCount' => array(
        'name' => 'likely_matches_count',
        'label' => ts('Count of likely matches', array('domain' => 'org.civicrm.donorsearch')),
        'data_type' => 'Int',
        'html_type' => 'Text',
        'is_search_range' => 1,
      ),
      'LikelyMatchesTotal' => array(
        'name' => 'real_estate_total',
        'label' => ts('Total of likely matches', array('domain' => 'org.civicrm.donorsearch')),
        'text_length' => 20,
        'data_type' => 'Money',
        'html_type' => 'Text',
        'is_search_range' => 1,
      ),
      'FndBoard' => array(
        'name' => 'fnd_board',
        'label' => ts('Foundation board', array('domain' => 'org.civicrm.donorsearch')),
        'text_length' => 1,
        'data_type' => 'String',
        'html_type' => 'Text',
      ),
      'GSBoard' => array(
        'name' => 'gs_board',
        'label' => ts('Grant seeking board', array('domain' => 'org.civicrm.donorsearch')),
        'text_length' => 1,
        'data_type' => 'String',
        'html_type' => 'Text',
      ),
      'PoliticalLikelyCount' => array(
        'name' => 'political_likely_count',
        'label' => ts('Political count', array('domain' => 'org.civicrm.donorsearch')),
        'data_type' => 'Int',
        'html_type' => 'Text',
        'is_search_range' => 1,
      ),
      'PoliticalLikelyTotal' => array(
        'name' => 'political_likely_total',
        'label' => ts('Political total', array('domain' => 'org.civicrm.donorsearch')),
        'text_length' => 20,
        'data_type' => 'Money',
        'html_type' => 'Text',
        'is_search_range' => 1,
      ),
      'BusinessRevenues' => array(
        'name' => 'business_revenues',
        'label' => ts('Business revenues', array('domain' => 'org.civicrm.donorsearch')),
        'text_length' => 20,
        'data_type' => 'Money',
        'html_type' => 'Text',
        'is_search_range' => 1,
      ),
      'SECStockValue' => array(
        'name' => 'sec_stock_value',
        'label' => ts('SEC stock value', array('domain' => 'org.civicrm.donorsearch')),
        'text_length' => 20,
        'data_type' => 'Money',
        'html_type' => 'Text',
        'is_search_range' => 1,
      ),
      'SECInsider' => array(
        'name' => 'sec_stock_insider',
        'label' => ts('SEC stock or insider', array('domain' => 'org.civicrm.donorsearch')),
        'text_length' => 1,
        'data_type' => 'String',
        'html_type' => 'Text',
      ),
      'MarketGuide' => array(
        'name' => 'mkt_guide',
        'label' => ts('Market guide', array('domain' => 'org.civicrm.donorsearch')),
        'text_length' => 1,
        'data_type' => 'String',
        'html_type' => 'Text',
      ),
      'IRS990PF' => array(
        'name' => 'IRS990PF',
        'label' => ts('IRS 990PF', array('domain' => 'org.civicrm.donorsearch')),
        'text_length' => 1,
        'data_type' => 'String',
        'html_type' => 'Text',
      ),
      'RealEstateTrust' => array(
        'name' => 'real_est_trust',
        'label' => ts('Real estate trust', array('domain' => 'org.civicrm.donorsearch')),
        'text_length' => 35,
        'data_type' => 'String',
        'html_type' => 'Text',
      ),
      'BusinessAffiliation' => array(
        'name' => 'business_affiliation',
        'label' => ts('Business affiliation', array('domain' => 'org.civicrm.donorsearch')),
        'text_length' => 1,
        'data_type' => 'String',
        'html_type' => 'Text',
      ),
      'Pilot' => array(
        'name' => 'pilot',
        'label' => ts('FAA Pilot license', array('domain' => 'org.civicrm.donorsearch')),
        'text_length' => 1,
        'data_type' => 'String',
        'html_type' => 'Text',
      ),
      'AirplaneOwner' => array(
        'name' => 'airplane_owner',
        'label' => ts('Airplane Owner', array('domain' => 'org.civicrm.donorsearch')),
        'text_length' => 1,
        'data_type' => 'String',
        'html_type' => 'Text',
      ),
      'Boat' => array(
        'name' => 'boat',
        'label' => ts('Boat', array('domain' => 'org.civicrm.donorsearch')),
        'text_length' => 1,
        'data_type' => 'String',
        'html_type' => 'Text',
      ),
    );
  }

  public static function getXMLToCustomFieldNameMap($xmlName = NULL) {
    $xmlToCustomFieldMap = CRM_Core_BAO_Cache::getItem('donor search', 'xml to custom field map');
    if (!$xmlToCustomFieldMap) {
      $xmlToCustomFieldMap = array();
      foreach (self::getAttributes() as $xmlName => $fieldInfo) {
        $customFieldID = civicrm_api3('custom_field', 'getvalue', array(
          'name' => $fieldInfo['name'],
          'return' => 'id',
        ));
        $xmlToCustomFieldMap[$xmlName] = 'custom_' . $customFieldID;
      }
      CRM_Core_BAO_Cache::setItem($xmlToCustomFieldMap, 'donor search', 'xml to custom field map');
    }
    return CRM_Utils_Array::value($xmlName, $xmlToCustomFieldMap, $xmlToCustomFieldMap);
  }

  public static function getBasicSearchFields() {
    return array(
      'id',
      'dFname',
      'dMname',
      'dLname',
      'dAddress',
      'dCity',
      'dZip',
      'dState',
      'dSFname',
      'dSMname',
      'dSLname',
      'dEmployer',
    );
  }

}
