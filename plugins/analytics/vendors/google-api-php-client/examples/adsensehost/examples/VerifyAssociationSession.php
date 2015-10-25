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

// Require the base class.
require_once __DIR__ . "/../BaseExample.php";

// The token returned in the association callback.
define('TOKEN', 'INSERT_TOKEN_HERE');

/**
 * This example verifies an association session callback token.
 *
 * Tags: associationsessions.verify
 *
 * @author Sérgio Gomes <sgomes@google.com>
 */
class VerifyAssociationSession extends BaseExample {
  public function render() {
    // Retrieve report.
    $result = $this->adSenseHostService->associationsessions->verify(TOKEN);

    $format = 'Association for account "%s" has status "%s" and ID "%s".';
    $content = sprintf($format, $result['accountId'], $result['status'],
        $result['id']);

    print $content;
  }
}

