<?php


class CRM_DonorSearch_Util {

  public static function updateRecord() {
    $apiKey = Civi::settings()->get('ds_api_key');
    if (empty($apiKey)) {
      CRM_Core_Error::fatal(ts("Donor Search API key missing."));
    }

    $url = "https://www.donorlead.net/API/get.php?id=1&key=" . $apiKey;
    $httpClient = new CRM_Utils_HttpClient();
    list($status, $response) = $httpClient->get($url);
  }


}
