<?php
/**
 * Payment service interface
 */
namespace Minds\Core\Payments;

interface PaymentServiceInterface {

  public function getToken();

  public function setSale($sale);

  public function updateMerchant($merchant);
  public function addMerchant($merchant);
  public function confirmMerchant($merchant);

}
