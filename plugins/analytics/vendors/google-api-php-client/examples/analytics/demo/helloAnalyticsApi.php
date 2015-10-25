<?php
/*
 * Copyright 2012 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

/**
 * Main demo class. Retrieves the authorized users first profile ID by
 * traversing the Google Analytics Management API. Then uses that ID to
 * make a query to the Core Reporting API for the top 25 organic search
 * terms.
 *
 * Note: The apiAnalyticsService parameter in the constructor accepts requires
 * an apiClient object to be instantiated. This all happens outside of this
 * class. This client must be configured to $apiClient->setUseObjects(true);
 * so that the library returns an object representation of the API response
 * instead of the default representation of associative arrays.
 * @author Nick Mihailovski <api.nickm@gmail.com>
 */
class HelloAnalyticsApi {

  /** @var apiAnalyticsService $analytics */
  var $analytics;

  /** @var string $error */
  var $error = null;

  /**
   * Constructor.
   * @param apiAnalyticsService The analytics service object to make
   *     requests to the API.
   */
  function __construct(&$analytics) {
    $this->analytics = $analytics;
  }

  /**
   * Tries to the get users first profile ID then uses that profile id
   * to query the core reporting API. The results from the API are formatted
   * and returned. If any error occurs, $this->error gets set.
   * @return string The formatted API response.
   */
  public function getHtmlOutput() {
    try {
      $profileId = $this->getFirstProfileId();
      if (isset($profileId)) {
        $results = $this->queryCoreReportingApi($profileId);
        return $this->getFormattedResults($results);
      }

    } catch (Google_ServiceException $e) {
      // Error from the API.
      $this->error = $e->getMessage();

    } catch (demoException $e) {
      // Error running this demo.
      $this->error = $e->getMessage();
    }
    return '';
  }

  /**
   * Returns the users first profile ID by traversing the Management API. If
   * any of the collections have no items, the traversal stops and an error
   * is throws to halt demo execution.
   * @throws demoException If any of the Management API collections had no
   *     items.
   * @return string The user's first profile ID.
   */
  private function getFirstprofileId() {
    $accounts =
        $this->analytics->management_accounts->listManagementAccounts();

    if (count($accounts->getItems()) > 0) {
      $items = $accounts->getItems();
      $firstAccountId = $items[0]->getId();

      $webproperties = $this->analytics->management_webproperties
          ->listManagementWebproperties($firstAccountId);

      if (count($webproperties->getItems()) > 0) {
        $items = $webproperties->getItems();
        $firstWebpropertyId = $items[0]->getId();

        $profiles = $this->analytics->management_profiles
            ->listManagementProfiles($firstAccountId, $firstWebpropertyId);

        if (count($profiles->getItems()) > 0) {
          $items = $profiles->getItems();
          return $items[0]->getId();

        } else {
          throw new demoException('No profiles found for this user.');
        }
      } else {
        throw new demoException('No webproperties found for this user.');
      }
    } else {
      throw new demoException('No accounts found for this user.');
    }
  }

  /**
   * Queries the Core Reporting API and returns the top 25 organic
   * search terms.
   * @param string $profileId The profileId to use in the query.
   * @return GaData the results from the Core Reporting API.
   */
  private function queryCoreReportingApi($profileId) {

    return $this->analytics->data_ga->get(
        'ga:' . $profileId,
        '2010-01-01',
        '2010-01-15',
        'ga:visits',
        array(
            'dimensions' => 'ga:source,ga:keyword',
            'sort' => '-ga:visits,ga:keyword',
            'filters' => 'ga:medium==organic',
            'max-results' => '25'));
  }

  /**
   * Formats the results from the Core Reporting API into some nice
   * HTML. The profile name is printed as a header. The results of
   * the query is printed as a table. Note, all the results from the
   * API are html escaped to prevent malicious code from running on the
   * page.
   * @param GaData $results The Results from the Core Reporting API.
   * @return string The nicely formatted results.
   */
  private function getFormattedResults($results) {
    $profileName = $results->getProfileInfo()->getProfileName();
    $output = '<h3>Results for profile: '
        . htmlspecialchars($profileName, ENT_NOQUOTES)
        . '</h3>';

    if (count($results->getRows()) > 0) {
      $table = '<table>';

      // Print headers.
      $table .= '<tr>';

      foreach ($results->getColumnHeaders() as $header) {
        $table .= '<th>' . $header->getName() . '</th>';
      }
      $table .= '</tr>';

      // Print table rows.
      foreach ($results->getRows() as $row) {
        $table .= '<tr>';
          foreach ($row as $cell) {
            $table .= '<td>'
                   . htmlspecialchars($cell, ENT_NOQUOTES)
                   . '</td>';
          }
        $table .= '</tr>';
      }
      $table .= '</table>';

    } else {
      $table = '<p>No results found.</p>';
    }
    return $output . $table;
  }

  /**
   * Returns any errors encountered in this script.
   * @return string The error message.
   */
  public function getError() {
    return $this->error;
  }
}

// Exceptions that the Demo can throw.
class demoException extends Exception {}

