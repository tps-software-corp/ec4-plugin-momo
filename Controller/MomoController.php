<?php

namespace Plugin\EC4MOMO\Controller;

use Eccube\Controller\AbstractController;
use Eccube\Entity\Order;
use Eccube\Entity\Master\OrderStatus;
use Eccube\Repository\OrderRepository;
use Eccube\Repository\Master\OrderStatusRepository;
use Plugin\EC4MOMO\Repository\MomoTransactionRepository;
use Plugin\EC4MOMO\Form\Type\Admin\ConfigType;
use Plugin\EC4MOMO\Service\MomoPaymentService;
use Plugin\EC4MOMO\Repository\ConfigRepository as MomoConfig;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MomoController extends AbstractController
{
    private $OrderRepository;
    private $MomoTransactionRepository;
    private $MomoConfig;
    private $MomoPaymentService;

    public function __construct(OrderRepository $OrderRepository, 
        MomoTransactionRepository $MomoTransactionRepository, 
        MomoConfig $MomoConfig, 
        MomoPaymentService $MomoPaymentService
    )
    {
        $this->OrderRepository = $OrderRepository;
        $this->MomoTransactionRepository = $MomoTransactionRepository;
        $this->MomoConfig = $MomoConfig->get();
        $this->MomoPaymentService = $MomoPaymentService;
    }

    /**
     * @Route("/ec4-momo/verify", name="ec4_momo_verify", )
     * 
     */
    public function verify(Request $request)
    {
        log_info('[MOMO] Called from MOMO at ' . date('Y-m-d H:i:s'));
        if ('POST' === $request->getMethod()) {
            $secret = $this->MomoConfig->getSecretKey();
            $orderId = $request->get("partnerRefId");
            $Order = $this->OrderRepository->find($orderId);
            $transaction = $this->MomoTransactionRepository->findOneBy([
                'Order' => $Order,
                'signature' => $request->get("signature"),
                'partnerCode' => $request->get("partnerCode"),
                'accessKey' => $request->get("accessKey"),
                'amount' => $request->get("amount"),
            ]);
            $status = 0;
            if ($transaction) {
                $transaction->setMomoTransId($request->get("momoTransId"));
                $transaction->setStatus($request->get("status"));
                $transaction->setMessage($request->get("message"));
                $transaction->setResponseTime($request->get("responseTime"));
                $this->MomoTransactionRepository->save($transaction);
                $Status = $entityManager->getRepository('Eccube\Entity\Master\OrderStatus')->find(OrderStatus::PAID);
                $Order->setOrderStatus($Status);
                $this->OrderRepository->save($Order);
                // $this->MomoPaymentService->confirm();
            } else {
                // $this->MomoPaymentService->rollback();
                $status = 1;
            }
            $status = $status;
            $message = $Order->getOrderStatus()->getName();
            $amount = $request->get("amount");
            $partnerRefId = $request->get("partnerRefId");
            $momoTransId = $request->get("momoTransId");
            $reponse = [
                "status" => $status,
                "message" => $message,
                "amount" => $amount,
                "partnerRefId" => $partnerRefId,
                "momoTransId" => $momoTransId,
                "signature" => hash_hmac('sha256', "amount=$amount&message=$message&momoTransId=$momoTransId&partnerRefId=$partnerRefId&status=$status", $secret)
            ];
            $this->json($reponse);
        }
        abort(404);
    }
}
