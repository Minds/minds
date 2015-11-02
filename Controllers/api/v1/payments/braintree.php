<?php
/**
 * Minds Payments API:: braintree
 *
 * @version 1
 * @author Mark Harding
 */
namespace Minds\Controllers\api\v1\payments;

use Minds\Core;
use Minds\Helpers;
use Minds\Interfaces;
use Minds\Api\Factory;
use Minds\Core\Payments;

class braintree implements Interfaces\Api
{
    /**
   * Returns merchant information
   * @param array $pages
   *
   * API:: /v1/merchant/:slug
   */
  public function get($pages)
  {
      $response = array();

      switch ($pages[0]) {
      case "token":
        $response['token'] = Payments\Factory::build('braintree')->getToken();
        break;
    }

      return Factory::response($response);
  }

    public function post($pages)
    {
        $response = array();

        switch ($pages[0]) {
      case "charge":
        $amount = $_POST['amount'];
        $fee = $amount * 0.05 + 0.30; //5% + $.30

        if (!isset($_POST['merchant'])) {
            $merchant = Core\Session::getLoggedInUser();
        }

        $sale = (new Payments\Sale())
          ->setAmount($amount)
          ->setMerchant($merchant)
          ->setFee($fee)
          ->setCustomerId(Core\Session::getLoggedInUser()->guid)
          ->setNonce($_POST['nonce']);

        try {
            $result = Payments\Factory::build('braintree')->setSale($sale);
            var_dump($result);
            exit;
        } catch (\Exception $e) {
            $response['status'] = "error";
            $response['message'] = $e->getMessage();
        }

        break;
    }

        return Factory::response($response);
    }

    public function put($pages)
    {
        return Factory::response(array());
    }

    public function delete($pages)
    {
        return Factory::response(array());
    }
}
