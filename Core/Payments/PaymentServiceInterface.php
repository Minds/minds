<?php
/**
 * Payment service interface
 */
namespace Minds\Core\Payments;

interface PaymentServiceInterface
{
    public function getToken();

    public function setSale(Sale $sale);
    public function getSales(Merchant $merchant, array $options = array());
    public function chargeSale(Sale $sale);
    public function voidSale(Sale $sale);
    public function refundSale(Sale $sale);

    public function updateMerchant(Merchant $merchant);
    public function addMerchant(Merchant $merchant);
    public function confirmMerchant(Merchant $merchant);
    public function getMerchant($id);
}
