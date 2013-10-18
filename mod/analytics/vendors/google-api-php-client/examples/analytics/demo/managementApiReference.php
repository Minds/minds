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
 * Main demo class to illustrate how to access all the values returned by
 * the Management API using the Google APIs PHP client library. The demo
 * traverses through the Management API hierarchy by starting at the account
 * collection, printing the entire collection, then using the first account ID
 * to query the next level in the hierarchy, the webproperties collection.
 * The following Management API collections are accessed and rendered into
 * HTML: Accounts, Webproperties, Profiles, Goals, Segments.
 *
 * Note: The apiAnalyticsService parameter in the constructor accepts requires
 * an apiClient object to be instantiated. This all happens outside of this
 * class. This client must be configured to $apiClient->setUseObjects(true);
 * so that the library returns an object representation of the API response
 * instead of the default representation of associative arrays.
 * @author Nick Mihailovski <api.nickm@gmail.com>
 */
class ManagementApiReference {

  /** @var Google_AnalyticsService $analytics */
  private $analytics;

  /** @var string $error */
  private $error = null;

  /**
   * Constructor.
   * @param $analytics
   * @internal param \Google_AnalyticsService $analytics The analytics service
   *     object to make requests to the API.
   */
  function __construct(&$analytics) {
    $this->analytics = $analytics;
  }

  /**
   * Returns an HTML string representation of the Management API hierarchy
   * traversal. Lots of queries occur in the traversal. If any errors occur,
   * the exceptions are caught and the message is stored in the error.
   * @return string The HTML representation of the Management API traversal.
   */
  function getHtmlOutput() {
    $output = '';

    try {
      $output = $this->getTraverseManagementApiHtml();
    } catch (Google_ServiceException $e) {
      $this->error = $e->getMessage();
    } catch (Google_Exception $e) {
      $this->error = $e->getMessage();
    } 
    return $output;
  }

  /**
   * Traverses the Management API. A query is made to the Accounts collection.
   * The first account ID is used to then query the webproperties collection.
   * The first webproperty ID is used to query the profiles collection. The
   * first profile is used to query the goals collection. Finally the segments
   * collection is queries. At each level, an HTML representation is rendered
   * of the entire collection. If one of the levels has no entities to query
   * for a child level, then traversal stops.
   * @return string The HTML representation of the Management API traversal.
   */
  private function getTraverseManagementApiHtml() {
    $accounts = $this->analytics->management_accounts
                     ->listManagementAccounts();

    $html = $this->getAccountsHtml($accounts);

    if (count($accounts->getItems()) > 0) {

      $firstAccountId = $this->getFirstId($accounts);
      $webproperties = $this->analytics->management_webproperties
                            ->listManagementWebproperties($firstAccountId);
      $html .= $this->getWebpropertiesHtml($webproperties);

      if (count($webproperties->getItems()) > 0) {

        $firstWebpropertyId = $this->getFirstId($webproperties);
        $profiles = $this->analytics->management_profiles
                         ->listManagementProfiles($firstAccountId,
                                                  $firstWebpropertyId);
        $html .= $this->getProfilesHtml($profiles);

        if (count($profiles->getItems()) > 0) {
          $firstProfileId = $this->getFirstId($profiles);
          $goals = $this->analytics->management_goals
                        ->listManagementGoals($firstAccountId,
                                              $firstWebpropertyId,
                                              $firstProfileId);
          $html .= $this->getGoalsHtml($goals);

        }
      }
    }

    $segments = $this->analytics->management_segments
                     ->listManagementSegments();

    $html .= $this->getSegmentsHtml($segments);
    return $html;
  }

  /**
   * Returns the first ID of an item in any of the Management API
   * collections. This was added to make the traversal code easier
   * to read.
   * @param collection $collection Any Management API collection.
   * @return string The ID of the first item in a collection.
   */
  private function getFirstId(&$collection) {
    $items = $collection->getItems();
    return $items[0]->getId();
  }

  /**
   * Returns important information from the accounts collection.
   * @param Accounts $accounts The result from the API.
   * @return string An HTML representation.
   */
  private function getAccountsHtml(&$accounts) {
    $html = '<h3>Accounts Collection</h3>' .
            $this->getCollectionInfoHtml($accounts);

    $items = $accounts->getItems();

    if (count($items) == 0) {
      $html .= '<p>No Accounts Found</p>';

    } else {
      foreach($items as &$account) {
        $html .= <<<HTML
<hr>
<pre>
Account ID   = {$account->getId()}
Kind         = {$account->getKind()}
Self Link    = {$account->getSelfLink()}
Account Name = {$account->getName()}
Created      = {$account->getCreated()}
Updated      = {$account->getUpdated()}
</pre>
HTML;
      }
    }
    return $html;
  }

  /**
   * Returns important information from the webproperties collection.
   * @param Google_Webproperties $webproperties The result from the API.
   * @return string An HTML representation.
   */
  private function getWebpropertiesHtml(&$webproperties) {
    $html = '<h3>Webproperties Collection</h3>' .
            $this->getCollectionInfoHtml($webproperties);

    $items = $webproperties->getItems();

    if (count($items) == 0) {
      $html .= '<p>No Web Properties Found</p>';

    } else {
      foreach ($items as &$webproperty) {
        $html .= <<<HTML
<hr>
<pre>
Kind                    = {$webproperty->getKind()}
Account ID              = {$webproperty->getAccountId()}
Webproperty ID          = {$webproperty->getId()}
Internal Webproperty ID = {$webproperty->getInternalWebPropertyId()}
Website URL             = {$webproperty->getWebsiteUrl()}
Created                 = {$webproperty->getCreated()}
Updated                 = {$webproperty->getUpdated()}
Self Link               = {$webproperty->getSelfLink()}
Parent Link
Parent link href        = {$webproperty->getParentLink()->getHref()}
Parent link type        = {$webproperty->getParentLink()->getType()}
Child Link
Child link href         = {$webproperty->getChildLink()->getHref()}
Child link type         = {$webproperty->getChildLink()->getType()}
</pre>
HTML;
      }
    }
    return $html;
  }

  /**
   * Returns important information from the profiles collection.
   * @param Profiles $profiles The result from the API.
   * @return string An HTML representation.
   */
  private function getProfilesHtml(&$profiles) {
    $html = '<h3>Profiles Collections</h3>' . 
            $this->getCollectionInfoHtml($profiles);

    $items = $profiles->getItems();

    if (count($items) == 0) {
      $html .= '<p>No Profiles Found</p>';

    } else {
      foreach ($items as &$profile) {
        $html .= <<<HTML
<hr>
<pre>
Kind                     = {$profile->getKind()}
Account ID               = {$profile->getAccountId()}
Web Property ID          = {$profile->getWebPropertyId()}
Internal Web Property ID = {$profile->getInternalWebPropertyId()}
Profile ID               = {$profile->getId()}
Profile Name             = {$profile->getName()}

Currency                 = {$profile->getCurrency()}
Timezone                 = {$profile->getTimezone()}
Default Page             = {$profile->getDefaultPage()}

Exclude Query Parameters = {$profile->getExcludeQueryParameters()}
Site Search Category Parameters = {$profile->getSiteSearchCategoryParameters()}
Site Search Query Parameters = {$profile->getSiteSearchQueryParameters()}

Created   = {$profile->getCreated()}
Updated   = {$profile->getUpdated()}

Self Link = {$profile->getSelfLink()}
Parent Link
Parent Link href = {$profile->getParentLink()->getHref()}
Parent link type = {$profile->getParentLink()->getType()}
Child Link
Child link href  = {$profile->getChildLink()->getHref()}
Child link type  = {$profile->getChildLink()->getType()}
</pre>
HTML;
      }
    }
    return $html;
  }

  /**
   * Returns important information from the goals collection.
   * @param Goals $goals The result from the API.
   * @return string An HTML representation.
   */
  private function getGoalsHtml(&$goals) {
    $html = '<h3>Goals Collections</h3>' .
            $this->getCollectionInfoHtml($goals);

    $items = $goals->getItems();

    if (count($items) == 0) {
      $html .= '<p>No Goals Found</p>';

    } else {
      foreach ($items as &$goal) {
        $html .= <<<HTML
<hr>
<pre>
Goal ID   = {$goal->getId()}
Kind      = {$goal->getKind()}
Self Link = {$goal->getSelfLink()}

Account ID               = {$goal->getAccountId()}
Web Property ID          = {$goal->getWebPropertyId()}
Internal Web Property ID = {$goal->getInternalWebPropertyId()}
Profile ID

Goal Name   = {$goal->getName()}
Goal Value  = {$goal->getValue()}
Goal Active = {$goal->getActive()}
Goal Type   = {$goal->getType()}

Created = {$goal->getCreated()}
Updated = {$goal->getUpdated()}

Parent Link
Parent link href = {$goal->getParentLink()->getHref()}
Parent link type = {$goal->getParentLink()->getHref()}
</pre>
HTML;

        // Now get the HTML for the type of goal.
        switch($goal->getType()) {
          case 'URL_DESTINATION':
            $html .= $this->getUrlDestinationDetailsHtml(
                $goal->getUrlDestinationDetails());
            break;
          case 'VISIT_TIME_ON_SITE':
            $html .= $this->getVisitTimeOnSiteDetailsHtml(
                $goal->getVisitTimeOnSiteDetails());
            break;
          case 'VISIT_NUM_PAGES':
            $html .= $this->getVisitNumPagesDetailsHtml(
                $goal->getVisitNumPagesDetails());
            break;
          case 'EVENT':
            $html .= $this->getEventDetailsHtml(
                $goal->getEventDetails());
            break;
        }
      }
    }
    return $html;
  }

  /**
   * Returns important information for url destination type goals.
   * @param GoalUrlDestinationDetails $details The result from the API.
   * @return string An HTML representation.
   */
  private function getUrlDestinationDetailsHtml(&$details) {
    $html = '<h4>Url Destination Goal</h4>';
    $html .= <<<HTML
<pre>
Goal URL            = {$details->getUrl()}
Case Sensitive      = {$details->getCaseSensitive()}
Match Type          = {$details->getMatchType()}
First Step Required = {$details->getFirstStepRequired()}
</pre>
HTML;

  $html .= '<h4>Destination Goal Steps</h4>';
  $steps = $details->getSteps();
  if (count($steps) == 0) {
    $html .= '<p>No Steps Configured</p>';

  } else {
    foreach ($steps as &$step) {
      $html .= <<<HTML
<pre>
Step Number = {$step->getNumber()}
Step Name   = {$step->getName()}
Step URL    = {$step->getUrl()}
</pre>
HTML;
    }
  }

  return $html;
  }

  /**
   * Returns important information for visit time on site type goals.
   * @param GoalVisitTimeOnSiteDetails $details The result from the API.
   * @return string An HTML representation.
   */
  private function getVisitTimeOnSiteDetailsHtml(&$details) {
    $html = '<h4>Visit Time On Site Goal</h4>';
    $html .= <<<HTML
<pre>
Comparison Type  = {$details->getComparisonType()}
Comparison Value = {$details->getComparisonValue()}
</pre>
HTML;
    return $html;
  }

  /**
   * Returns important information for visit number of pages goals.
   * @param Google_GoalVisitNumPagesDetails $details The result from the API.
   * @return string An HTML representation.
   */
  private function getVisitNumPagesDetailsHtml(&$details) {
    $html = '<h4>Visit Num Pages Goal</h4>';
    $html .= <<<HTML
<pre>
Comparison Type  = {$details->getComparisonType()}
Comparison Value = {$details->getComparisonValue()}
</pre>
HTML;
    return $html;
  }

  /**
   * Returns important information for event goals.
   * @param Google_GoalEventDetails $details The result from the API.
   * @return string An HTML representation.
   */
  private function getEventDetailsHtml(&$details) {
    $html = '<h4>Event Goal</h4><pre>' .
            'Use Event Value = ' . $details->getUseEventValue();

    // Get all the event goal conditions.
    $conditions = $details->getEventConditions();

    // String condition types.
    $stringTypes = array('CATEGORY', 'ACTION', 'LABEL');

    foreach ($conditions as &$condition) {
      $html .= "Event Type = $condition->getEventType()";

      $eventType = $condition->getType();
      if (in_array($eventType, $stringTypes)) {
        // Process CATEGORY, ACTION, LABEL.
        $html .= "Match Type = $condition->getMatchType()" .
                 "Expression = $condition->getExpression()";
      } else {
        // Process VALUE.
        $html .= "Comparison Type  = $condition->getComparisonType()" .
                 "Comparison Value = $condition->getComparisonValue()";
      }
    }

    return $html . '</pre>';
  }

  /**
   * Returns important information from the segments collection.
   * @param Google_Segments $segments The result from the API.
   * @return string An HTML representation.
   */
  private function getSegmentsHtml(&$segments) {
    $html = '<h3>Segments Collection</h3>' .
            $this->getCollectionInfoHtml($segments);

    $items = $segments->getItems();

    if (count($items) == 0) {
      $html .= '<p>No Segments Found</p>';
    } else {
      foreach ($items as &$segment) {
        $html .= <<<HTML
<hr>
<pre>
Segment ID = {$segment->getId()}
Kind       = {$segment->getKind()}
Self Link  = {$segment->getSelfLink()}
Name       = {$segment->getName()}
Definition = {$segment->getDefinition()}
Created    = {$segment->getCreated()}
Updated    = {$segment->getUpdated()}
</pre>
HTML;
      }
    }
    return $html;
  }

  /**
   * Returns important information common to each collection in the API.
   * Most of this data can be used to paginate through the results.
   * @param collection $collection The result from a Management API request.
   * @return string An HTML representation.
   */
  private function getCollectionInfoHtml(&$collection) {
    $previousLink = $collection->getPreviousLink()
                    ? $collection->getPreviousLink() : 'none';

    $nextLink = $collection->getNextLink()
                ? $collection->getNextLink() : 'none';

    return <<<HTML
<pre>
Username       = {$collection->getUsername()}
Items Per Page = {$collection->getItemsPerPage()}
Total Results  = {$collection->getTotalResults()}
Start Index    = {$collection->getStartIndex()}
Previous Link  = {$previousLink}
Next Link      = {$nextLink}
</pre>
HTML;
  }

  /** @return string Any errors that occurred. */
  function getError() {
    return $this->error;
  }
}
