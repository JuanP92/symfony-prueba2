<?php

namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\FormTypeInterface;

/** @ODM\Document */
class Order
{
    /** @ODM\Id */
    private $id;

    /** @ODM\Field(type="int") */
    #[Assert\NotBlank]
    #[Assert\Positive]
    private int $userId;

    /** @ODM\Field(type="int") */
    #[Assert\NotBlank]
    #[Assert\Positive]
    private int $amount;

    /** @ODM\Field(type="int") */
    #[Assert\NotBlank]
    #[Assert\PositiveOrZero]
    private int $unitPrice;

    /** @ODM\Field(type="string") */
    #[Assert\NotBlank]
    private string $productName;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return Order
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @param int $userId
     * @return Order
     */
    public function setUserId(int $userId): Order
    {
        $this->userId = $userId;
        return $this;
    }

    /**
     * @return int
     */
    public function getAmount(): int
    {
        return $this->amount;
    }

    /**
     * @param int $amount
     * @return Order
     */
    public function setAmount(int $amount): Order
    {
        $this->amount = $amount;
        return $this;
    }

    /**
     * @return int
     */
    public function getUnitPrice(): int
    {
        return $this->unitPrice;
    }

    /**
     * @param int $unitPrice
     * @return Order
     */
    public function setUnitPrice(int $unitPrice): Order
    {
        $this->unitPrice = $unitPrice;
        return $this;
    }

    /**
     * @return string
     */
    public function getProductName(): string
    {
        return $this->productName;
    }

    /**
     * @param string $productName
     * @return Order
     */
    public function setProductName(string $productName): Order
    {
        $this->productName = $productName;
        return $this;
    }

    public function getTotal(): int{
        return $this->amount*$this->unitPrice;
    }
}