<?php

namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\FormTypeInterface;

/** @ODM\Document */
class Order
{
    /** @ODM\Id */
    private $id;

    /** @ODM\Field(type="string") */
    #[Assert\NotBlank]
    private string $userEmail;

    /** @ODM\Field(type="int") */
    #[Assert\NotBlank]
    #[Assert\Positive]
    private int $amount;

    /** @ODM\Field(type="int") */
    #[Assert\NotBlank]
    #[Assert\PositiveOrZero]
    private int $unitPrice;

    /** @ODM\Field(type="float") */
    #[Assert\NotBlank]
    #[Assert\PositiveOrZero]
    #[Assert\Range(min: 0, max: 1)]
    private float $discount;

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
     * @return string
     */
    public function getUserEmail(): string
    {
        return $this->userEmail;
    }

    /**
     * @param string $userEmail
     * @return Order
     */
    public function setUserEmail(string $userEmail): Order
    {
        $this->userEmail = $userEmail;
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
     * @return float
     */
    public function getDiscount(): float
    {
        return $this->discount;
    }

    /**
     * @param float $discount
     * @return Order
     */
    public function setDiscount(float $discount): Order
    {
        $this->discount = $discount;
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

    /**
     * @param string $productName
     * @return Order
     */
    #[Groups(['details'])]
    public function getTotal(): int
    {
        return ($this->amount * $this->unitPrice)
            - ($this->unitPrice * $this->discount);
    }
}