<?php

class ElggSiteTest extends Minds_PHPUnit_Framework_TestCase {

        protected function setUp() {
                // required by ElggEntity when setting the owner/container
                //_elgg_services()->setValue('session', new ElggSession(new Elgg_Http_MockSessionStorage()));
        }

        public function testCanConstructWithoutArguments() {
                $this->assertNotNull(new ElggSite());
        }
		
		public function testSave(){
			$site = new ElggSite();
			$site->name = "site test";
			$site->description = "testing";
			$site->email = "test@minds.com";
			$this->assertInternalType('integer', $site->save());
		}

}