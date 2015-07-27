<?php

use Minds\Helpers;

class AnalyticsTest extends \Minds_PHPUnit_Framework_TestCase {

      public function testTsBuild(){
					/**
					 * Timestamp of day
					 */
          $ts = Helpers\Analytics::buildTS("day", 1437983151);
          $this->assertEquals(1437955200, $ts);
					/**
					 * Timestamp of yesterday
					 */
          $ts = Helpers\Analytics::buildTS("yesterday", 1437983151);
          $this->assertEquals(1437868800, $ts);
					/**
					 * Timestamp of month
					 */
					$ts = Helpers\Analytics::buildTS("month", 1437983151);
          $this->assertEquals(1435708800, $ts);
					/**
					 * Timestamp of last month&
					 */
					$ts = Helpers\Analytics::buildTS("last-month", 1437983151);
					$this->assertEquals(1433116800, $ts);
      }

}
