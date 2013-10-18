<?php
/**
 * Licensed to the Apache Software Foundation (ASF) under one
 * or more contributor license agreements.  See the NOTICE file
 * distributed with this work for additional information
 * regarding copyright ownership.  The ASF licenses this file
 * to you under the Apache License, Version 2.0 (the
 * "License"); you may not use this file except in compliance
 * with the License.  You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing,
 * software distributed under the License is distributed on an
 * "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY
 * KIND, either express or implied.  See the License for the
 * specific language governing permissions and limitations
 * under the License.
 */

class ApiMediaFileUploadTest extends BaseTest {
  public function testMediaFile() {
    $media = new Google_MediaFileUpload('image/png', base64_decode('data:image/png;base64,a'));

    $this->assertEquals(0, $media->progress);
    $this->assertEquals('image/png', $media->mimeType);

    $payload = null;
    $params = null;
    $this->assertEquals(false,
      Google_MediaFileUpload::process(null, $payload, $params));
    $this->assertEquals(false,
      Google_MediaFileUpload::process(array(), $payload, $params));
  }

  public function testGetUploadType() {
    $payload = null;

    $media = new Google_MediaFileUpload('image/png', 'a', true);
    $params = array('mediaUpload' => array('value' => $media));
    $this->assertEquals('resumable',
      Google_MediaFileUpload::getUploadType(null, $payload, $params));

    // Test custom uploadType values.
    $params = array('uploadType' => array('value' => 'foo'));
    $this->assertEquals('foo',
      Google_MediaFileUpload::getUploadType(null, $payload, $params));

    // No data available for the upload.
    $params = array();
    $this->assertEquals(false,
      Google_MediaFileUpload::getUploadType(false, $payload, $params));

    // Test file uploads
    $params = array('file' => array('value' => '@/foo'));
    $this->assertEquals('media',
      Google_MediaFileUpload::getUploadType(null, $payload, $params));

    // Test data *only* uploads
    $params = array('data' => array('value' => 'foo'));
    $this->assertEquals('media',
      Google_MediaFileUpload::getUploadType(null, $payload, $params));

    // Test multipart uploads
    $params = array('data' => array('value' => 'foo'));
    $this->assertEquals('multipart',
      Google_MediaFileUpload::getUploadType(array('a' => 'b'), $payload, $params));
  }

  public function testProcessFile() {
    $this->assertEquals(array('postBody' => array('file' => '@/tmp')),
      Google_MediaFileUpload::processFileUpload('@/tmp', false));

    $this->assertEquals(array('postBody' => array('file' => '@/tmp')),
      Google_MediaFileUpload::processFileUpload('/tmp', false));

    $this->assertEquals(array('postBody' => array('file' => '@../tmp')),
      Google_MediaFileUpload::processFileUpload('../tmp', false));

    $this->assertEquals(array(),
      Google_MediaFileUpload::processFileUpload('', false));
  }

  public function testProcess() {
    // Test data *only* uploads.
    $params = array('data' => array('value' => 'foo'));
    $val = Google_MediaFileUpload::process(null, $params);
    $this->assertTrue(array_key_exists('postBody', $val));
    $this->assertEquals('foo', $val['postBody']);

    // Test only metadata.
    $params = array();
    $val = Google_MediaFileUpload::process(null, $params);
    $this->assertEquals(false, $val);

    // Test multipart (metadata & upload data).
    $params = array(
        'data' => array('value' => 'foo'),
        'boundary' => array('value' => 'a'),
    );

    $val = Google_MediaFileUpload::process(array('a'), $params);
    $this->assertEquals('multipart/related; boundary=a', $val['content-type']);

    $expected = '--aContent-Type: application/json; charset=UTF-8'
        . '["a"]'
        . '--aContent-Type: Content-Transfer-Encoding: base64Zm9v--a--';
    $this->assertEquals($expected, str_replace("\r\n", '', $val['postBody']));


    // Multipart
    $params = array(
        'data' => array('value' => 'foo'),
        'boundary' => array('value' => 'a""'),
        'mimeType' => array('value' => 'image/png')
    );

    $val = Google_MediaFileUpload::process(array('a'), $params);
    $this->assertEquals('multipart/related; boundary=a', $val['content-type']);

    $expected = '--aContent-Type: application/json; charset=UTF-8'
        . '["a"]'
        . '--aContent-Type: image/pngContent-Transfer-Encoding: base64Zm9v--a--';
    $this->assertEquals($expected, str_replace("\r\n", '', $val['postBody']));
  }
}