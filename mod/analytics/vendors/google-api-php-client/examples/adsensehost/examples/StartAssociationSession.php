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

/**
 * This example starts an association session.
 *
 * Tags: associationsessions.start
 *
 * @author Sérgio Gomes <sgomes@google.com>
 */
class StartAssociationSession extends BaseExample {
  public function render() {
    // Retrieve report.
    $result = $this->adSenseHostService->associationsessions
        ->start('AFC', 'www.example.com/blog');

    $format = 'Association with ID "%s" and redirect URL "%s" was started.';
    $content = sprintf($format, $result['id'], $result['redirectUrl']);

    print $content;
  }
}

