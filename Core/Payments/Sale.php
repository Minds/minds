<?php
/**
 * Payment Sale Entity
 */
namespace Minds\Core\Payments;

use Minds\Entities\User;

class Sale{

  private $amount;
  private $fee;
  private $merchant;
  private $nonce;

  public function __construct(){

  }

  public function setAmount($amount){
    $this->amount = $amount;
    return $this;
  }

  public function getAmount(){
    return $this->amount;
  }

  public function setFee($fee){
    $this->fee = $fee;
    return $this;
  }

  public function getFee(){
    return $this->fee;
  }

  public function setMerchant(User $user){
    $this->merchant = $user;
    return $this;
  }

  public function getMerchant(){
    return $this->merchant;
  }

  public function setNonce($nonce){
    $this->nonce = $nonce;
    return $this;
  }

  public function getNonce(){
    return $this->nonce;
  }

}
