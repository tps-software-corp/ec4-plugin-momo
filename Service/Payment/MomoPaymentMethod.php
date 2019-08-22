<?php
namespace Plugin\EC4MOMO\Service\Payment;

use Eccube\Common\EccubeConfig;
use Eccube\Entity\Order;
use Eccube\Repository\BaseInfoRepository;
use Eccube\Repository\OrderRepository;
use Plugin\EC4MOMO\Entity\Config;
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

class MomoPaymentMethod implements PaymentMethodInterface
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
        ContainerInterface $container
    ) {
        $this->orderStatusRepository = $orderStatusRepository;
        $this->purchaseFlow = $shoppingPurchaseFlow;
        $this->MomoConfig = $configRepository->get();
        $this->MomoTransationRepo = $MomoTransactionRepository;
        $this->eccubeConfig = $eccubeConfig;
        $this->BaseInfo = $BaseInfo->get();
        $this->orderRepository = $orderRepository;
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Eccube\Service\PurchaseFlow\PurchaseException
     */
    public function checkout()
    {

        $this->purchaseFlow->commit($this->Order, new PurchaseContext());

        $transaction = $this->MomoTransationRepo->findOneBy(['Order' => $this->Order]);
        $result = new PaymentResult();
        $result->setSuccess(true);

        return $result;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Eccube\Service\PurchaseFlow\PurchaseException
     */
    public function apply()
    {
        $this->purchaseFlow->prepare($this->Order, new PurchaseContext());

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function setFormType(\Symfony\Component\Form\FormInterface $form)
    {
        $this->form = $form;
    }

    /**
     * {@inheritdoc}
     */
    public function verify()
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function setOrder(Order $Order)
    {
        $this->Order = $Order;
    }
}