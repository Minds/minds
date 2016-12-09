<?php
/**
 * Minds Merchant API
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
use Minds\Entities;

class export implements Interfaces\Api, Interfaces\ApiIgnorePam
{

  public function get($pages)
  {
      Factory::isLoggedIn();

      $response = [];

      $merchant = (new Payments\Merchant)
        ->setId(Core\Session::getLoggedInUser()->getMerchant()['id']);

        $guid = Core\Session::getLoggedInUser()->guid;
        $stripe = Core\Di\Di::_()->get('StripePayments');


        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=data.csv');


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
  }

    public function post($pages)
    {
        return Factory::response([]);
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
