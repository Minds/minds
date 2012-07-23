<?php

/* * ************************************************
 * PluginLotto.com                                 *
 * Copyrights (c) 2005-2010. iZAP                  *
 * All rights reserved                             *
 * **************************************************
 * @author iZAP Team "<support@izap.in>"
 * @link http://www.izap.in/
 * @version {version} $Revision: {revision}
 * Under this agreement, No one has rights to sell this script further.
 * For more information. Contact "Tarun Jangra<tarun@izap.in>"
 * For discussion about corresponding plugins, visit http://www.pluginlotto.com/pg/forums/
 * Follow us on http://facebook.com/PluginLotto and http://twitter.com/PluginLotto
 */

class IzapDiskQuota {

  private $max_allowed_space = 2147483648; // Default: 1 GB //
  private $current_user = False;
  private $current_upload_size = 0;
  private $total_size_used = 0;

  /**
   * 
   * @param ElggUser $user 
   */
  public function __construct($user = NULL) {

    // set the user
    if ($user instanceof ElggUser) {
      $this->current_user = $user;
    } else if (elgg_is_logged_in()) {
      $this->current_user = elgg_get_logged_in_user_entity();
    }

    // determine how much a user has used
    $this->total_size_used = (int) $this->current_user->izap_disk_used;

    // check the maximun space for the user
    $user_diskquota = IzapBase::mb2byte($this->current_user->izap_disk_quota);
    if ($user_diskquota) {
      $this->max_allowed_space = $user_diskquota;
    } else { // else allow the global_space
      $global_max_allowed_space = IzapBase::pluginSetting(array(
                  'plugin' => GLOBAL_IZAP_DISKQUOTA_PLUGIN,
                  'name' => 'izap_allowed_diskspace',
                  'value' => 1024,
              ));

      elgg_set_plugin_setting($name, $value);

      if ((int) $global_max_allowed_space) {
        $this->max_allowed_space = IzapBase::mb2byte($global_max_allowed_space);
      }
    }

    // calculate the current upload size
    $this->calculateCurrentUpload();
  }

  /**
   * calculates the size of the uploaded file
   */
  public function calculateCurrentUpload() {
    if (sizeof($_FILES)) {
      $total = 0;

      foreach ($_FILES as $name => $values) {
        foreach ($values as $key => $value) {
          if (!is_array($value)) {

            if ($key == 'error') {
              $error = $value;
            }

            if ($error == 0 && $key == 'size') {
              $total += $value;
            }
          } else {

            if ($key == 'error') {
              foreach ($value as $ke => $val) {
                if ($val == 0)
                  $good_keys[] = $ke;
              }
            }

            if ($key == 'size') {
              foreach ($good_keys as $keee)
                $total += $value[$keee];
            }
          }
        }
      }
    }

    if ($total > 0) {
      $this->current_upload_size = $total;
    }
  }

  /**
   * gets the size of uploaded file
   * @return type int
   */
  public function getCurrentUploadSize() {
    return (int) $this->current_upload_size;
  }

  /**
   * validates the upoaloaded file wheather user has space left or not
   * @return type boolean
   */
  public function validate() {
    if (!$this->getCurrentUploadSize()) {
      return True;
    }

    if (!$this->current_user) {
      return False;
    }

    if (($this->total_size_used + $this->current_upload_size) > $this->max_allowed_space) {
      return False;
    }

    $this->current_user->izap_disk_used = $this->total_size_used + $this->current_upload_size;
    return True;
  }

  /**
   * gets user quota of space in MB
   * @return float user space
   */
  public function getUserDiskquotaInMB() {
    $space = (float) $this->current_user->izap_disk_quota;
    if (!$space) {
      $space = (float) IzapBase::byteToMb($this->max_allowed_space);
    }

    return $space;
  }

  /**
   * gets user quota of space in Bytes
   * @return type float user space
   */
  public function getUserDiskquotaInB() {
    $space = (float) IzapBase::mb2byte($this->current_user->izap_disk_quota);
    if (!$space) {
      $space = (float) $this->max_allowed_space;
    }

    return $space;
  }

  /**
   * gets user used space in MB
   * @return type float user space
   */
  public function getUserUsedSpaceInMB() {
    return (float) round(IzapBase::byteToMb($this->current_user->izap_disk_used), 2);
  }

  /**
   * gets user used space in Bytes
   * @return type float user space
   */
  public function getUserUsedSpaceInB() {
    return (float) $this->current_user->izap_disk_used;
  }

  /**
   * gets user used space in %
   * @return type float user space
   */
  public function getUserUsedSpaceInPercent() {
    $total_used = $this->getUserUsedSpaceInB();
    $allowed_space = $this->getUserDiskquotaInB();

    return (float) round(($total_used / $allowed_space) * 100, 2);
  }

  /**
   * release the used space after deletion
   * @param ElggEntity $entity
   */
  public function releaseSpace(ElggEntity $entity) {
    $space_used = (int) $entity->izap_diskspace_used;
    if ($space_used) {
      $this->current_user->izap_disk_used = $this->current_user->izap_disk_used - $space_used;
    }
  }

}
