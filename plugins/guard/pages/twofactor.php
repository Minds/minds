<?php
/**
 * Two factor pages
 */
namespace minds\plugin\guard\pages;

use Minds\Core;
use Minds\Interfaces;
use Minds\Entities;
use minds\plugin\guard\lib;

class twofactor extends core\page implements Interfaces\page
{
    public $csrf = false;
    
    /**
     * Get requests
     */
    public function get($pages)
    {
        \elgg_set_context('settings');
        
        $twofactor = new lib\twofactor();
        
        $content .= \elgg_view_form('guard/twofactor/setup', array('action'=>\elgg_get_site_url().'settings/twofactor/setup'));
                
        $body = \elgg_view_layout('content', array('title'=>\elgg_echo('guard:twofactor'), 'content'=>$content));
        
        echo $this->render(array('body'=>$body));
    }
    
    public function post($pages)
    {
        invalidate_cache_for_entity(\elgg_get_logged_in_user_guid());
        $user = \elgg_get_logged_in_user_entity();
        $twofactor = new lib\twofactor();
        
        switch ($pages[0]) {
            
            case 'setup':
                if (\get_input('disable')) {
                    $user->twofactor = false;
                    $user->save();
                    return $this->forward(REFERRER);
                    ;
                }
                $user = \elgg_get_logged_in_user_entity();
                $user->telno = \get_input('tel');
                $user->save();
                
                \minds\plugin\guard\start::sendSMS($user->telno, $twofactor->getCode($secret));
                
                $content = 'We just sent you a text message. Please enter the code below';
                $content .= \elgg_view_form('guard/twofactor/check', array('action'=>\elgg_get_site_url().'settings/twofactor/check/'.$secret));

                break;
        
            case 'check':
                
                $secret = $pages[1];
                $code = \get_input('code');
                if ($twofactor->verifyCode($secret, $code, 1)) {
                    $content = 'Success! You are now setup for two-factor authentication';
                    $user->twofactor = true;
                } else {
                    $content = 'Something didn\'t go to plan.. Please try again.';
                    $user->twofactor = false;
                }
                $user->save();
                break;
            default:
                return false;
            
        }

        $body = \elgg_view_layout('content', array('content'=>$content));
        echo $this->render(array('body'=>$body));
    }
    
    public function put($pages)
    {
    }
    public function delete($pages)
    {
    }
}
