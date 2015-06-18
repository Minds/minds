<?php
/**
 * Minds analytics  pages
 */
namespace minds\pages;

use Minds\Core;
use Minds\Helpers;
use minds\interfaces;

class analytics extends core\page implements interfaces\page{

    public function get($pages){


            if(!elgg_is_admin_logged_in())
                return $this->forward("/");

            $db = new Core\Data\Call('entities_by_time');

            $guids = $db->getRow("analytics:open", array('limit'=>5));
            $users = Core\entities::get(array('guids'=>array_keys($guids), 'limit'=>5));
            $user_count = $db->countRow("analytics:open");

            $requests = array(
                0 => (int) Helpers\RequestMetrics::get("api", time()),
                5 => (int) Helpers\RequestMetrics::get("api", time() - 300),
                10 => (int) Helpers\RequestMetrics::get("api", time() - 600)
            );

            $rps = ($requests[0] + $requests[5] + $requests[10]) / (600 + (Helpers\RequestMetrics::buildTS(time()) - time())); 

            $boost_guids = $db->getRow("boost:newsfeed", array('limit'=>1000));
            $boost_impressions = 0;
            $boost_impressions_met = 0;
            foreach($boost_guids as $guid => $impressions){
                $boost_impressions = $boost_impressions + $impressions; 
                $boost_impressions_met = $boost_impressions_met + Helpers\Counters::get($guid, "boost_impressions", false); 
            }

            $boosts = array(
                'approved' => count($boost_guids),
                'impressions' => $boost_impressions,
                'impressions_met' => $boot_impressions_met
            );
      
            $boost_guids = $db->getRow("boost:suggested", array('limit'=>1000));
            $boost_impressions = 0;
            $boost_impressions_met = 0;
            foreach($boost_guids as $guid => $impressions){
                $boost_impressions = $boost_impressions + $impressions;
                $boost_impressions_met = $boost_impressions_met + Helpers\Counters::get($guid, "boost_impressions", false);
            } 
      
            $boosts_suggested = array(
                'approved' => count($boost_guids),
                'impressions' => $boost_impressions,
                'impressions_met' => $boot_impressions_met
            );


            /**
             * This page is getting messy!
             */
            $cql = "SELECT * FROM counters WHERE metric = :metric LIMIT 1000000 ALLOW FILTERING";
            $values = array(
                'metric' => 'points',
                'limit' => 10000
            );

            $client = Core\Data\Client::build('Cassandra');
            $prepared = new Core\Data\Cassandra\Prepared\Custom();
            $results = (array) $client->request($prepared->query($cql,$values));

            //find who has the most points
            usort($results, array($this, "sortResults"));
            $leaderboard = array();
            $i = 0;
            foreach($results as $result){
                if($i++ > 25)
                    break;
                $user = new \Minds\entities\user($result['guid']);
                $username = "@$user->username";
                $count = $result['count'];
                $leaderboard[] = array(
                    'user'=>$user,
                    'points' => $count
                );
            }

            $content = elgg_view('analytics/dashboard', array('users' => $users, 'user_count'=>$user_cound, 'requests'=>$requests, 'rps' => $rps, 'globals'=>array('boosts'=>Helpers\Counters::get(0, 'boost_impressions', false)), 'boosts' => $boosts, 'boosts_suggested'=> $boosts_suggested, 'leaderboard'=>$leaderboard));

            $body = \elgg_view_layout('one_sidebar', array(
                'title'=> 'Analytics',
                'content'=>$content,
                'sidebar_class' => 'elgg-sidebar-alt cms-sidebar-wrapper',
                'hide_ads'=>true
            ));

            elgg_extend_view('page/elements/foot', 'cms/footer');

            echo $this->render(array('body'=>$body));
    }

    public function sortResults($a, $b){
                        if($a['count'] == $b['count'])
                                                   return 0;
                                         return ($a['count'] < $b['count']) ? 1 : -1;
                                    }


    public function post($pages){
    }

    public function put($pages){
        throw new \Exception('Sorry, the put method is not supported for the page');
    }

    public function delete($pages){
        throw new \Exception('Sorry, the delete method is not supported for the page');
    }

}
