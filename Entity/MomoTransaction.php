<?php

namespace Plugin\EC4MOMO\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Config
 *
 * @ORM\Table(name="plg_ec4_momo_transactions")
 * @ORM\Entity(repositoryClass="Plugin\EC4MOMO\Repository\MomoTransactionRepository")
 */
class MomoTransaction
{
    const STATUS_SUCCESS = 'success';

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", options={"unsigned":true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \Eccube\Entity\Order
     *
     * @ORM\OneToOne(targetEntity="Eccube\Entity\Order")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="order_id", referencedColumnName="id")
     * })
     */
    private $Order;

    /**
     * @var integer
     *
     * @ORM\Column(name="env", type="string", length=255, nullable=true)
     */
    private $env;

    /**
     * @var string
     *
     * @ORM\Column(name="partnerCode", type="string", length=255, nullable=true)
     */
    private $partnerCode;

    /**
     * @var string
     *
     * @ORM\Column(name="storeId", type="string", length=50, nullable=true)
     */
    private $storeId;
    
    /**
     * @var string
     *
     * @ORM\Column(name="accessKey", type="string", length=255, nullable=true)
     */
    private $accessKey;
    
    /**
     * @var bigint
     *
     * @ORM\Column(name="amount", type="bigint", nullable=true)
     */
    private $amount;
    
    /**
     * @var string
     *
     * @ORM\Column(name="partnerRefId", type="string", length=50, nullable=true)
     */
    private $partnerRefId;
    
    /**
     * @var string
     *
     * @ORM\Column(name="partnerTransId", type="string", length=50, nullable=true)
     */
    private $partnerTransId;
    
    /**
     * @var string
     *
     * @ORM\Column(name="transType", type="string", length=20, nullable=true)
     */
    private $transType;
    
    /**
     * @var string
     *
     * @ORM\Column(name="momoTransId", type="string", length=50, nullable=true)
     */
    private $momoTransId;
    
    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=50, nullable=true)
     */
    private $status;
    
    /**
     * @var string
     *
     * @ORM\Column(name="message", type="string", length=255, nullable=true)
     */
    private $message;
    
    /**
     * @var string
     *
     * @ORM\Column(name="responseTime", type="bigint", nullable=true)
     */
    private $responseTime;
    
    /**
     * @var string
     *
     * @ORM\Column(name="signature", type="string", length=255, nullable=true)
     */
    private $signature;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the value of env
     *
     * @return  string
     */ 
    public function getEnv()
    {
        return $this->env;
    }

    /**
     * Set the value of env
     *
     * @param  string  $env
     *
     * @return  self
     */ 
    public function setEnv($env)
    {
        $this->env = $env;

        return $this;
    }

    /**
     * Get the value of partnerCode
     *
     * @return  string
     */ 
    public function getPartnerCode()
    {
        return $this->partnerCode;
    }

    /**
     * Set the value of partnerCode
     *
     * @param  string  $partnerCode
     *
     * @return  self
     */ 
    public function setPartnerCode($partnerCode)
    {
        $this->partnerCode = $partnerCode;

        return $this;
    }

    /**
     * Get the value of storeId
     *
     * @return  string
     */ 
    public function getStoreId()
    {
        return $this->storeId;
    }

    /**
     * Set the value of storeId
     *
     * @param  string  $storeId
     *
     * @return  self
     */ 
    public function setStoreId($storeId)
    {
        $this->storeId = $storeId;

        return $this;
    }

    /**
     * Get the value of accessKey
     *
     * @return  string
     */ 
    public function getAccessKey()
    {
        return $this->accessKey;
    }

    /**
     * Set the value of accessKey
     *
     * @param  string  $accessKey
     *
     * @return  self
     */ 
    public function setAccessKey($accessKey)
    {
        $this->accessKey = $accessKey;

        return $this;
    }

    /**
     * Get the value of amount
     *
     * @return  string
     */ 
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set the value of amount
     *
     * @param  string  $amount
     *
     * @return  self
     */ 
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get the value of partnerRefId
     *
     * @return  string
     */ 
    public function getPartnerRefId()
    {
        return $this->partnerRefId;
    }

    /**
     * Set the value of partnerRefId
     *
     * @param  string  $partnerRefId
     *
     * @return  self
     */ 
    public function setPartnerRefId($partnerRefId)
    {
        $this->partnerRefId = $partnerRefId;

        return $this;
    }

    /**
     * Get the value of partnerTransId
     *
     * @return  string
     */ 
    public function getPartnerTransId()
    {
        return $this->partnerTransId;
    }

    /**
     * Set the value of partnerTransId
     *
     * @param  string  $partnerTransId
     *
     * @return  self
     */ 
    public function setPartnerTransId($partnerTransId)
    {
        $this->partnerTransId = $partnerTransId;

        return $this;
    }

    /**
     * Get the value of transType
     *
     * @return  string
     */ 
    public function getTransType()
    {
        return $this->transType;
    }

    /**
     * Set the value of transType
     *
     * @param  string  $transType
     *
     * @return  self
     */ 
    public function setTransType($transType)
    {
        $this->transType = $transType;

        return $this;
    }

    /**
     * Get the value of momoTransId
     *
     * @return  string
     */ 
    public function getMomoTransId()
    {
        return $this->momoTransId;
    }

    /**
     * Set the value of momoTransId
     *
     * @param  string  $momoTransId
     *
     * @return  self
     */ 
    public function setMomoTransId($momoTransId)
    {
        $this->momoTransId = $momoTransId;

        return $this;
    }

    /**
     * Get the value of status
     *
     * @return  string
     */ 
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set the value of status
     *
     * @param  string  $status
     *
     * @return  self
     */ 
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get the value of message
     *
     * @return  string
     */ 
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set the value of message
     *
     * @param  string  $message
     *
     * @return  self
     */ 
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get the value of responseTime
     *
     * @return  string
     */ 
    public function getResponseTime()
    {
        return $this->responseTime;
    }

    /**
     * Set the value of responseTime
     *
     * @param  string  $responseTime
     *
     * @return  self
     */ 
    public function setResponseTime($responseTime)
    {
        $this->responseTime = $responseTime;

        return $this;
    }

    /**
     * Get the value of signature
     *
     * @return  string
     */ 
    public function getSignature()
    {
        return $this->signature;
    }

    /**
     * Set the value of signature
     *
     * @param  string  $signature
     *
     * @return  self
     */ 
    public function setSignature($signature)
    {
        $this->signature = $signature;

        return $this;
    }

    /**
     * Get the value of Order
     *
     * @return  \Eccube\Entity\Order
     */ 
    public function getOrder()
    {
        return $this->Order;
    }

    /**
     * Set the value of Order
     *
     * @param  \Eccube\Entity\Order  $Order
     *
     * @return  self
     */ 
    public function setOrder(\Eccube\Entity\Order $Order)
    {
        $this->Order = $Order;

        return $this;
    }
}
