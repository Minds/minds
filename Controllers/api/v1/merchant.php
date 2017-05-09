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
use Minds\Entities;

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
      Factory::isLoggedIn();

      $response = [];

      switch ($pages[0]) {
        case "status":
            $merchants = Core\Di\Di::_()->get('Monetization\Merchants');
            $merchants->setUser(Core\Sandbox::user(Core\Session::getLoggedInUser(), 'merchant'));

            $isMerchant = (bool) $merchants->getId();
            $canBecomeMerchant = !$merchants->isBanned();

            return Factory::response([
                'isMerchant' => $isMerchant,
                'canBecomeMerchant' => $canBecomeMerchant,
            ]);
            break;

        case "sales":
          $merchant = (new Payments\Merchant)
            ->setId(Core\Session::getLoggedInUser()->getMerchant()['id']);

          $guid = Core\Session::getLoggedInUser()->guid;
          $stripe = Core\Di\Di::_()->get('StripePayments');

          try{
              $sales = $stripe->getSales($merchant);

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
          } catch (\Exception $e) {
              $response['status'] = 'error';
          }

          break;
        case "export":
           $merchant = (new Payments\Merchant)
            ->setId(Core\Session::getLoggedInUser()->getMerchant()['id']);

            $guid = Core\Session::getLoggedInUser()->guid;
            $stripe = Core\Di\Di::_()->get('StripePayments');

            $out = fopen('php://output', 'w');

            try {
                $balance = $stripe->getBalance($merchant, ['limit' => 100]);

                fputcsv($out, [
                  'id',
                  'type',
                  'status',
                  'description',
                  'created',
                  'amount',
                  'currency',
                  'available'
                ]);

                foreach($balance->data as $record){
                    // Get the required charge information and assign to variables
                    $id = $record->id;
                    $type = $record->type;
                    $status = $record->status;
                    $description = $record->description;
                    $created = gmdate('Y-m-d H:i', $record->created); // Format the time
                    $amount = $record->amount/100; // Convert amount from cents to dollars
                    $currency = $record->currency;
                    $available = gmdate('Y-m-d H:i', $record->available_on);

                    // Create an array of the above charge information
                    $report = array(
                                $id,
                                $type,
                                $status,
                                $description,
                                $created,
                                $amount,
                                $currency,
                                $available
                      );


                    fputcsv($out, $report);
                }
            } catch (\Exception $e) {
            }

            fclose($out);

            exit;
            break;
        case "balance":
          break;
        case "settings":

          $stripe = Core\Di\Di::_()->get('StripePayments');
          try {
              $merchant = $stripe->getMerchant(Core\Session::getLoggedInUser()->getMerchant()['id']);
          } catch (\Exception $e) {
              return Factory::response([
                'status' => 'error',
                'message' => $e->getMessage()
              ]);
          }

          if (!$merchant) {
              return Factory::response([
                'status' => 'error',
                'message' => 'Not a merchant account'
              ]);
          }

          $response['merchant'] = $merchant->export();

          break;
        }

      return Factory::response($response);
  }

    public function post($pages)
    {
        Factory::isLoggedIn();

        $response = array();

        switch ($pages[0]) {
          case "onboard":
            $merchant = (new Payments\Merchant())
              ->setGuid(Core\Session::getLoggedInUser()->guid)
              ->setDestination('bank')
              ->setCountry($_POST['country'])
              ->setSSN($_POST['ssn'] ? str_pad((string) $_POST['ssn'], 4, '0', STR_PAD_LEFT) : '')
              ->setPersonalIdNumber($_POST['personalIdNumber'])
              ->setFirstName($_POST['firstName'])
              ->setLastName($_POST['lastName'])
              ->setGender($_POST['gender'])
              ->setDateOfBirth($_POST['dob'])
              ->setStreet($_POST['street'])
              ->setCity($_POST['city'])
              ->setState($_POST['state'])
              ->setPostCode($_POST['postCode'])
              ->setPhoneNumber($_POST['phoneNumber'])
              ->setAccountNumber($_POST['accountNumber'])
              ->setRoutingNumber($_POST['routingNumber']);

            try {
                $stripe = Core\Di\Di::_()->get('StripePayments');
                $result = $stripe->addMerchant($merchant);
                $response['id'] = $result->id;

                $user = Core\Session::getLoggedInUser();
                $user->merchant = [
                  'service' => 'stripe',
                  'id' => $result->id
                ];

                //save public and private keys in lookup
                $lu = Core\Di\Di::_()->get('Database\Cassandra\Lookup');
                $guid = Core\Session::getLoggedInUser()->guid;
                $lu->set("{$guid}:stripe", [
                  'public' => $result->keys['publishable'],
                  'secret' => $result->keys['secret']
                ]);

                //now setup exclusive
                $stripe->createPlan((object) [
                  'id' => "exclusive",
                  'amount' => 10 * 100,
                  'merchantId' => $result->id
                ]);

                $merchant = $user->merchant;
                $merchant['exclusive'] = [
                  'enabled' => true,
                  'amount' => 10
                ];
                $user->setMerchant($merchant); //because double assoc array doesn't work

                $user->save();

            } catch (\Exception $e) {
                $response['status'] = "error";
                $response['message'] = $e->getMessage();
            }

            break;
          case "verification":

              try {
                  $stripe = Core\Di\Di::_()->get('StripePayments');
                  $stripe->verifyMerchant(Core\Session::getLoggedInUser()->getMerchant()['id'], $_FILES['file']);
              } catch (\Exception $e) {
                  $response['status'] = "error";
                  $response['message'] = $e->getMessage();
              }
              break;
          case "update":
            $merchant = (new Payments\Merchant())
              ->setId(Core\Session::getLoggedInUser()->getMerchant()['id'])
              ->setFirstName($_POST['firstName'])
              ->setLastName($_POST['lastName'])
              ->setGender($_POST['gender'])
              ->setDateOfBirth($_POST['dob'])
              ->setStreet($_POST['street'])
              ->setCity($_POST['city'])
              ->setState($_POST['state'])
              ->setPostCode($_POST['postCode'])
              ->setPhoneNumber($_POST['phoneNumber']);

              if ($_POST['ssn']) {
                $merchant->setSSN($_POST['ssn'] ? str_pad((string) $_POST['ssn'], 4, '0', STR_PAD_LEFT) : '');
              }

              if ($_POST['personalIdNumber']) {
                $merchant->setPersonalIdNumber($_POST['personalIdNumber']);
              }

              if ($_POST['accountNumber']) {
                $merchant->setAccountNumber($_POST['accountNumber']);
              }

              if ($_POST['routingNumber']) {
                $merchant->setRoutingNumber($_POST['routingNumber']);
              }

            try {
                $stripe = Core\Di\Di::_()->get('StripePayments');
                $result = $stripe->updateMerchant($merchant);
            } catch (\Exception $e) {
                $response['status'] = "error";
                $response['message'] = $e->getMessage();
            }
            break;
          case "exclusive":
            try {
                $user = Core\Session::getLoggedInUser();
                $lu = Core\Di\Di::_()->get('Database\Cassandra\Lookup');

                $stripe = Core\Di\Di::_()->get('StripePayments');

                try {
                  $stripe->deletePlan("exclusive", $user->getMerchant()['id']);
                } catch(\Exception $e){}

                $stripe->createPlan((object) [
                  'id' => "exclusive",
                  'amount' => $_POST['amount'] * 100,
                  'merchantId' => $user->getMerchant()['id']
                ]);

                $merchant = $user->getMerchant();
                $merchant['exclusive'] = [
                  'enabled' => !!$_POST['enabled'],
                  'amount' => $_POST['amount'],
                  'intro' => $_POST['intro']
                ];

                $user->setMerchant($merchant);
                $user->save();

            } catch (\Exception $e) {
                $response['status'] = "error";
                $response['message'] = $e->getMessage();
            }
            break;
          case "exclusive-preview":
              $user = Core\Session::getLoggedInUser();
              if (is_uploaded_file($_FILES['file']['tmp_name'])) {
                  $file = new Entities\File();
                  $file->owner_guid = $user->guid;
                  $file->setFilename("paywall-preview.jpg");
                  $file->open('write');
                  $file->write(file_get_contents($_FILES['file']['tmp_name']));
                  $file->close();

                  $response['uploaded'] = true;
              }
              break;
          case "charge":
            $sale = (new Payments\Sale)
              ->setId($pages[1]);

            try {
                Payments\Factory::build('braintree', ['gateway'=>'merchants'])->chargeSale($sale);
            } catch (\Exception $e) {
                var_dump($e);
                exit;
            }
            exit;
            break;
          case "void":
            $sale = (new Payments\Sale)
              ->setId($pages[1]);
            Payments\Factory::build('braintree', ['gateway'=>'merchants'])->voidSale($sale);
            break;
          case "refund":
            $sale = (new Payments\Sale)
              ->setId($pages[1]);
            Payments\Factory::build('braintree', ['gateway'=>'merchants'])->refundSale($sale);
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
