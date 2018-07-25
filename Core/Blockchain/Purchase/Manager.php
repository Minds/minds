<?php
/**
 *  Token Purchase Manager
 */
namespace Minds\Core\Blockchain\Purchase;

use Minds\Core\Queue;
use Minds\Core\Events\Dispatcher;
use Minds\Core\Di\Di;
use Minds\Entities\User;
use Minds\Core\Blockchain\Transactions\Transaction;

class Manager
{

    /** @var Repository $repo */
    private $repo;

    /** @var \Minds\Core\Blockchain\Transactions\Manager */
    protected $txManager;

    /** @var \Minds\Core\Config\Config */
    protected $config;

    /** @var Delegates\IssueTokens */
    private $issueTokens;

    /** @var Delegates\NewPurchaseNotification */
    private $newPurchaseNotification;

    /** @var Delegates\ApprovedPurchaseNotification */
    private $approvedPurchaseNotification;

    /** @var Delegates\ApprovedPurchaseEmail */
    private $approvedPurchaseEmail;

    public function __construct(
        $repo = null,
        $txManager = null,
        $config = null,
        $issueTokens = null,
        $newPurchaseNotification = null,
        $issuedTokenNotification = null,
        $approvedPurchaseEmail = null
    )
    {
        $this->repo = $repo ?: Di::_()->get('Blockchain\Purchase\Repository');
        $this->txManager = $txManager ?: Di::_()->get('Blockchain\Transactions\Manager');
        $this->config = $config ?: Di::_()->get('Config');
        $this->issueTokens = $issueTokens ?: new Delegates\IssueTokens();
        $this->newPurchaseNotification = $newPurchaseNotification ?: new Delegates\NewPurchaseNotification();
        $this->issuedTokenNotification = $issuedTokenNotification ?: new Delegates\IssuedTokenNotification();
        //$this->approvedPurchaseEmail = $approvedPurchaseEmail ?: new Delegates\ApprovedPurchaseEmail();
    }

    /**
     * Returns the contract address of the token sale event
     * @return 
     */
    private function getContractAddress()
    {
        return $this->config->get('blockchain')['contracts']['token_sale_event']['contract_address'];
    }

    public function getAutoIssueCap()
    {
        return $this->config->get('blockchain')['contracts']['token_sale_event']['auto_issue_cap'];
    }

    public function getEthTokenRate()
    {
        return $this->config->get('blockchain')['contracts']['token_sale_event']['eth_rate'];
    }

    public function getPurchase($phone_number_hash, $tx)
    {
        return $this->repo->get($phone_number_hash, $tx);
    }

    /**
     * Get the purchase amount from a user
     * @param User $user
     * @return string
     */
    public function getPurchasedAmount($user)
    {
        $purchase = $this->getPurchase($user);

        if (!$purchase) {
            return 0;
        }

        return (string) $purchase->getRequestedAmount();
    }

    /**
     * Register a purchase transaction
     * @var Purchase $purchase
     * @return void
     */
    public function purchase($purchase)
    {
        $transaction = new Transaction();
        $transaction
            ->setTx($purchase->getTx())
            ->setContract('purchase')
            ->setAmount($purchase->getRequestedAmount())
            ->setWalletAddress($purchase->getWalletAddress())
            ->setTimestamp($purchase->getTimestamp())
            ->setUserGuid($purchase->getUserGuid())
            ->setData([
                'amount' => $purchase->getRequestedAmount(),
                'phone_number_hash' => $purchase->getPhoneNumberHash(),
                'address' => $purchase->getWalletAddress(),
            ]);

        $this->txManager->add($transaction);
        $this->add($purchase);

        $this->newPurchaseNotification->notify($purchase);
    }

    /**
     * Add a purchase to repository
     */
    public function add($purchase)
    {
        $this->repo->add($purchase);

        //$this->newPurchaseNotification->notify($purchase);
    }

    /**
     * @param Purchase $purchase
     * @return bool
     * @throws \Exception
     */
    public function issue(Purchase $purchase)
    {
        $this->issueTokens->issue($purchase);

        $purchase->setIssuedAmount((string) $purchase->getUnissuedAmount());
        $purchase->setStatus('issued');
        $this->repo->add($purchase);

        $this->issuedTokenNotification->notify($purchase);

        return true;
    }

    /**
     * @param Purchase $purchase
     * @return bool
     */
    public function reject(Purchase $purchase)
    {
        $purchase->setStatus('rejected');
        $this->repo->add($purchase);

        return true;
    }

    public function test()
    {
        return 10;
    }
}
