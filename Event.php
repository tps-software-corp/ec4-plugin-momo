<?php

namespace Plugin\EC4MOMO;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Eccube\Event\TemplateEvent;
use Plugin\EC4MOMO\Service\Payment\MomoPaymentMethod;
use Plugin\EC4MOMO\Service\MomoPaymentService;
use Plugin\EC4MOMO\Repository\MomoTransactionRepository;

class Event implements EventSubscriberInterface
{
    private $momoPaymentService;
    private $MomoTransactionRepository;

    public function __construct(MomoPaymentService $momoPaymentService, MomoTransactionRepository $MomoTransactionRepository)
    {
        $this->momoPaymentService = $momoPaymentService;
        $this->MomoTransactionRepository = $MomoTransactionRepository;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            'Shopping/confirm.twig' => 'showMomoQRCode',
            'Shopping/complete.twig' => 'showMomoTransactionErrorMessage',
        ];
    }

    public function showMomoQRCode(TemplateEvent $event)
    {
        $parameter = $event->getParameters();
        $Order = $parameter['Order'];
        if ($Order->getPayment()->getMethodClass() == MomoPaymentMethod::class) {
            $twig = '@EC4MOMO/momo/qrcode_popup.twig';
            $event->setParameter('QRCode', $this->momoPaymentService->getQRCode($Order));
            $event->addSnippet($twig);
        }
    }

    public function showMomoTransactionErrorMessage(TemplateEvent $event)
    {
        $parameter = $event->getParameters();
        $Order = $parameter['Order'];
        if ($Order->getPayment()->getMethodClass() == MomoPaymentMethod::class) {
            $transaction = $this->MomoTransactionRepository->findOneBy(['Order' => $Order]);
            if (!($transaction && $transaction->getStatus() == 0)) {
                $twig = '@EC4MOMO/momo/transaction_error.twig';
                $event->addSnippet($twig);
            }
        }
    }
}
