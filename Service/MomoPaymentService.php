<?php
namespace Plugin\EC4MOMO\Service;

use Eccube\Common\EccubeConfig;
use Eccube\Entity\Order;
use Eccube\Repository\BaseInfoRepository;
use Eccube\Repository\OrderRepository;
use Plugin\EC4MOMO\Entity\Config;
use Plugin\EC4MOMO\Entity\MomoTransaction;
use Plugin\EC4MOMO\Repository\ConfigRepository;
use Plugin\EC4MOMO\Repository\MomoTransactionRepository;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Eccube\Service\Payment\PaymentMethodInterface;
use Eccube\Service\Payment\PaymentResult;
use Eccube\Service\Payment\PaymentDispatcher;
use Eccube\Repository\Master\OrderStatusRepository;
use Eccube\Service\PurchaseFlow\PurchaseFlow;
use Eccube\Entity\Master\OrderStatus;
use Eccube\Service\PurchaseFlow\PurchaseContext;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Doctrine\ORM\EntityManager;

class MomoPaymentService
{
    /**
     * @var bool
     */
    protected $isCheck = false;

    /**
     * @var \Eccube\Entity\Order
     */
    protected $Order;

    /**
     * @var \Symfony\Component\Form\FormInterface
     */
    protected $form;

    /**
     * @var OrderStatusRepository
     */
    protected $orderStatusRepository;

    /**
     * @var OrderRepository
     */
    protected $orderRepository;

    /**
     * @var PurchaseFlow
     */
    protected $purchaseFlow;

    /**
     * @var Config
     */
    protected $MomoConfig;

    /**
     * @var MomoTransactionRepository
     */
    protected $MomoTransationRepo;

    /**
     * @var EccubeConfig
     */
    protected $eccubeConfig;

    /**
     * @var BaseInfoRepository
     */
    protected $BaseInfo;

    /**
     * @var ContainerInterface
     */
    protected $container;

    private $EntityManager;

    /**
     * NapasGateway constructor.
     *
     * @param OrderStatusRepository $orderStatusRepository
     * @param PurchaseFlow $shoppingPurchaseFlow
     * @param ConfigRepository $configRepository
     * @param MomoTransactionRepository $MomoTransactionRepository
     * @param BaseInfoRepository $BaseInfo
     * @param EccubeConfig $eccubeConfig
     * @param OrderRepository $orderRepository
     * @param ContainerInterface $container
     * @throws \Exception
     */
    public function __construct(
        OrderStatusRepository $orderStatusRepository,
        PurchaseFlow $shoppingPurchaseFlow,
        ConfigRepository $configRepository,
        MomoTransactionRepository $MomoTransactionRepository,
        BaseInfoRepository $BaseInfo,
        EccubeConfig $eccubeConfig,
        OrderRepository $orderRepository,
        EntityManager $EntityManager,
        ContainerInterface $container
    ) {
        $OrderStatusRepository = $orderStatusRepository;
        $this->purchaseFlow = $shoppingPurchaseFlow;
        $this->MomoConfig = $configRepository->get();
        $this->MomoTransationRepo = $MomoTransactionRepository;
        $this->eccubeConfig = $eccubeConfig;
        $this->BaseInfo = $BaseInfo->get();
        $this->orderRepository = $orderRepository;
        $this->container = $container;
        $this->EntityManager = $EntityManager;
    }
    
    /**
     * Undocumented function
     *
     * @return void
     */
    public function getQRCode($Order)
    {
        $secret = $this->MomoConfig->getSecretKey();
        $domain = $this->MomoConfig->getEnv();
        $storeSlug = $this->MomoConfig->getPartnerCode() . '-' . $this->MomoConfig->getStoreId();
        $amount = $Order->getTotal();
        $billId = $Order->getId();
        $signature = hash_hmac('sha256', "storeSlug=$storeSlug&amount=$amount&billId=$billId", $secret);

        $transaction = $this->MomoTransationRepo->findOneBy(['Order' => $Order]);
        if (!$transaction) {
            $transaction = new MomoTransaction();
            $transaction->setEnv($domain);
            $transaction->setPartnerCode($this->MomoConfig->getPartnerCode());
            $transaction->setStoreId($this->MomoConfig->getStoreId());
            $transaction->setAccessKey($this->MomoConfig->getAccessKey());
            $transaction->setAmount($amount);
            $transaction->setPartnerRefId($billId);
            $transaction->setTransType('momo_wallet');
            $transaction->setSignature($signature);
            $transaction->setOrder($Order);
            $this->MomoTransationRepo->save($transaction);
            $this->EntityManager->flush();
        }
        $url = "$domain/pay/store/$storeSlug?a=$amount&b=$billId&s=$signature";
        return $url;
    }
}