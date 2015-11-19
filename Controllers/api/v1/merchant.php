<?php
/**
 * Minds Merchant API
 *
 * @version 1
 * @author Mark Harding
 */
namespace Minds\Controllers\api\v1;

use Minds\Core;
use Minds\Helpers;
use Minds\Interfaces;
use Minds\Api\Factory;
use Minds\Core\Payments;

class merchant implements Interfaces\Api
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
      case "sales":
        $merchant = (new Payments\Merchant)
          ->setGuid(Core\Session::getLoggedInUser()->guid);
        $sales = Payments\Factory::build('braintree')->getSales($merchant);

        foreach ($sales as $sale) {
            $response['sales'][] = [
            'id' => $sale->getId(),
            'status' => $sale->getStatus(),
            'amount' => $sale->getAmount(),
            'fee' => $sale->getFee(),
            'orderId' => $sale->getOrderId(),
            'customerId' => $sale->getCustomerId()
          ];
        }

        break;
      case "balance":
        break;
      case "settings":
        $merchant = Payments\Factory::build('braintree')->getMerchant(Core\Session::getLoggedInUser()->guid);

        $response['merchant'] = array(
          'status' => $merchant->getStatus(),
          'firstName' => $merchant->getFirstName(),
          'lastName' => $merchant->getLastName(),
          'email' => $merchant->getEmail(),
          'ssn' => $merchant->getSSN(),
          'venmo' => $merchant->getDestination() == 'email',
          'accountNumber' => $merchant->getAccountNumber(),
          'routingNumber' => $merchant->getRoutingNumber()
        );

        break;
    }

      return Factory::response($response);
  }

    public function post($pages)
    {
        $response = array();

        switch ($pages[0]) {
          case "onboard":
            $merchant = (new Payments\Merchant())
              ->setGuid(Core\Session::getLoggedInUser()->guid)
              ->setFirstName($_POST['firstName'])
              ->setLastName($_POST['lastName'])
              ->setEmail($_POST['email'])
              ->setDateOfBirth($_POST['dob'])
              ->setSSN($_POST['ssn'])
              ->setStreet($_POST['street'])
              ->setCity($_POST['city'])
              ->setRegion($_POST['region'])
              ->setPostCode($_POST['postCode'])
              ->setAccountNumber($_POST['accountNumber'])
              ->setRoutingNumber($_POST['routingNumber']);

            try {
                $id = Payments\Factory::build('braintree')->addMerchant($merchant);
                $response['id'] = $id;

                $user = Core\Session::getLoggedInUser();
                $user->merchant = true;
                $user->{"merchant_status"} = 'processing';
                $user->save();
            } catch (\Exception $e) {
                $response['status'] = "error";
                $response['message'] = $e->getMessage();
            }

            break;
          case "update":
            $merchant = (new Payments\Merchant())
              ->setGuid(Core\Session::getLoggedInUser()->guid)
              ->setFirstName($_POST['firstName'])
              ->setLastName($_POST['lastName'])
              ->setEmail($_POST['email'])
              //->setDateOfBirth($_POST['dob'])
              ->setSSN($_POST['ssn'])
              //->setStreet($_POST['street'])
              //->setCity($_POST['city'])
              //->setRegion($_POST['region'])
              //->setPostCode($_POST['postCode'])
              ->setAccountNumber($_POST['accountNumber'])
              ->setRoutingNumber($_POST['routingNumber']);

            try {
                $id = Payments\Factory::build('braintree')->updateMerchant($merchant);
                $response['id'] = $id;

                $user = Core\Session::getLoggedInUser();
                $user->merchant = true;
                $user->save();
            } catch (\Exception $e) {
                $response['status'] = "error";
                $response['message'] = $e->getMessage();
            }
            break;
          case "charge":
            $sale = (new Payments\Sale)
              ->setId($pages[1]);

            try {
                Payments\Factory::build('braintree')->chargeSale($sale);
            } catch (\Exception $e) {
                var_dump($e);
                exit;
            }
            exit;
            break;
          case "void":
            $sale = (new Payments\Sale)
              ->setId($pages[1]);
            Payments\Factory::build('braintree')->voidSale($sale);
            break;
          case "refund":
            $sale = (new Payments\Sale)
              ->setId($pages[1]);
            Payments\Factory::build('braintree')->refundSale($sale);
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
