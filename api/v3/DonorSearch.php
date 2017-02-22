<?php
/*
 +--------------------------------------------------------------------+
 | CiviCRM version 4.7                                                |
 +--------------------------------------------------------------------+
 | Copyright CiviCRM LLC (c) 2004-2017                                |
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
 * This api exposes CiviCRM DonorSearch records.
 *
 * @package CiviCRM_APIv3
 */

/**
 * Save an DonorSearch.
 *
 * @param array $params
 *
 * @return array
 *   API result array
 */
function civicrm_api3_donor_search_create($params) {
  if (isset($params['search_criteria']) && is_array($params['search_criteria'])) {
    $params['search_criteria'] = serialize($params['search_criteria']);
  }
  return _civicrm_api3_basic_create(_civicrm_api3_get_BAO(__FUNCTION__), $params);
}

/**
 * This is used for documentation and validation.
 *
 * @param array $spec description of fields supported by this API call
 * @return void
 * @see http://wiki.civicrm.org/confluence/display/CRMDOC/API+Architecture+Standards
 */
function _civicrm_api3_donor_search_create_spec(&$spec) {
  $spec['creator_id']['api.default'] = 'user_contact_id';
}

/**
 * Get an DonorSearch.
 *
 * @param array $params
 *
 * @return array
 *   API result array
 */
function civicrm_api3_donor_search_get($params) {
  $results = _civicrm_api3_basic_get(_civicrm_api3_get_BAO(__FUNCTION__), $params);
  if (!empty($results['values']) && is_array($results['values'])) {
    foreach ($results['values'] as &$val) {
      if (isset($val['search_criteria'])) {
        $val['search_criteria'] = unserialize($val['search_criteria']);
      }
    }
  }
  return $results;
}

/**
 * Delete an DonorSearch.
 *
 * @param array $params
 *
 * @return array
 *   API result array
 */
function civicrm_api3_donor_search_delete($params) {
  return _civicrm_api3_basic_delete(_civicrm_api3_get_BAO(__FUNCTION__), $params);
}
