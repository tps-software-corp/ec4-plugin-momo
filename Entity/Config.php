<?php

namespace Plugin\EC4MOMO\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Config
 *
 * @ORM\Table(name="plg_ec4_momo_config")
 * @ORM\Entity(repositoryClass="Plugin\EC4MOMO\Repository\ConfigRepository")
 */
class Config
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", options={"unsigned":true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="env", type="string", length=255)
     */
    private $env;


    /**
     * @var string
     *
     * @ORM\Column(name="partner_code", type="string", length=255)
     */
    private $partnerCode;

    /**
     * @var string
     *
     * @ORM\Column(name="store_id", type="string", length=50)
     */
    private $storeId;

    /**
     * @var string
     *
     * @ORM\Column(name="access_key", type="string", length=50)
     */
    private $accessKey;

    /**
     * @var string
     *
     * @ORM\Column(name="secret_key", type="string", length=50)
     */
    private $secretKey;

    /**
     * @var string
     *
     * @ORM\Column(name="api_endpoint", type="string", length=255)
     */
    private $apiEndpoint;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
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
    public function setPartnerCode(string $partnerCode)
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
    public function setStoreId(string $storeId)
    {
        $this->storeId = $storeId;

        return $this;
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
    public function setEnv(string $env)
    {
        $this->env = $env;

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
    public function setAccessKey(string $accessKey)
    {
        $this->accessKey = $accessKey;

        return $this;
    }

    /**
     * Get the value of secretKey
     *
     * @return  string
     */ 
    public function getSecretKey()
    {
        return $this->secretKey;
    }

    /**
     * Set the value of secretKey
     *
     * @param  string  $secretKey
     *
     * @return  self
     */ 
    public function setSecretKey(string $secretKey)
    {
        $this->secretKey = $secretKey;

        return $this;
    }

    /**
     * Get the value of apiEndpoint
     *
     * @return  string
     */ 
    public function getApiEndpoint()
    {
        return $this->apiEndpoint;
    }

    /**
     * Set the value of apiEndpoint
     *
     * @param  string  $apiEndpoint
     *
     * @return  self
     */ 
    public function setApiEndpoint(string $apiEndpoint)
    {
        $this->apiEndpoint = $apiEndpoint;

        return $this;
    }
}
