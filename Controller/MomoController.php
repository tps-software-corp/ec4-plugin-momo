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
use Symfony\Component\HttpFoundation\Response;

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
     * @Route("/ec4-momo/check-status/{orderId}", name="ec4_momo_check_order_status", requirements={"orderId" = "\d+"})
     */
    public function checkOrderStatus(Request $request, $orderId)
    {
        $Order = $this->OrderRepository->find($orderId);
        if (!$Order) {
            return $this->json(['result' => 0]);
        }
        $transaction = $this->MomoTransactionRepository->findOneBy(['Order' => $Order]);
        if (!$transaction) {
            return $this->json(['result' => 0]);
        }
        return $this->json(['result' => $transaction->getStatus() === '0' ? 1 : 0]);
    }

    /**
     * @Route("/ec4-momo/verify", name="ec4_momo_verify", )
     */
    public function verify(Request $request)
    {
        $params = json_decode($request->getContent());
        if ('POST' === $request->getMethod()) {
            $secret = $this->MomoConfig->getSecretKey();
            $orderId = $params->partnerRefId;
            $Order = $this->OrderRepository->find($orderId);
            $transaction = $this->MomoTransactionRepository->findOneBy([
                'Order' => $Order,
                'signature' => $params->signature,
                'partnerCode' => $params->partnerCode,
                'accessKey' => $params->accessKey,
                'amount' => $params->amount,
            ]);
            $status = 0;
            if ($transaction) {
                $transaction->setMomoTransId($params->momoTransId);
                $transaction->setStatus($params->status === 0 ? \Plugin\EC4MOMO\Entity\MomoTransaction::STATUS_SUCCESS : $params->status);
                $transaction->setMessage($params->message);
                $transaction->setResponseTime($params->responseTime);
                $this->MomoTransactionRepository->save($transaction);
                $Status = $this->entityManager->getRepository('Eccube\Entity\Master\OrderStatus')->find(OrderStatus::PAID);
                $Order->setOrderStatus($Status);
                $this->OrderRepository->save($Order);
                $this->entityManager->flush();
                // $this->MomoPaymentService->confirm();
            } else {
                // $this->MomoPaymentService->rollback();
                $status = 1;
            }
            $status = $status;
            $message = $Order->getOrderStatus()->getName();
            $amount = $params->amount;
            $partnerRefId = $params->partnerRefId;
            $momoTransId = $params->momoTransId;
            $reponse = [
                "status" => $status,
                "message" => $message,
                "amount" => $amount,
                "partnerRefId" => $partnerRefId,
                "momoTransId" => $momoTransId,
                "signature" => hash_hmac('sha256', "amount=$amount&message=$message&momoTransId=$momoTransId&partnerRefId=$partnerRefId&status=$status", $secret)
            ];
            return $this->json($reponse);
        }
        return new Response("Page not found", 404);
        
    }
}
