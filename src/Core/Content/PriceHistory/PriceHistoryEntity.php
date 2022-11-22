<?php

namespace PriceHistory\Core\Content\PriceHistory;

use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityIdTrait;
use Shopware\Core\Framework\DataAbstractionLayer\Pricing\PriceCollection;

class PriceHistoryEntity extends Entity
{
    use EntityIdTrait;

    protected ?string $productId;
    protected ?string $changeDate;
    protected ?PriceCollection $oldPrice;
    protected ?PriceCollection $newPrice;

    /**
     * @return string|null
     */
    public function getProductId(): ?string
    {
        return $this->productId;
    }

    /**
     * @param string|null $productId
     */
    public function setProductId(?string $productId): void
    {
        $this->productId = $productId;
    }

    /**
     * @return string|null
     */
    public function getChangeDate(): ?string
    {
        return $this->changeDate;
    }

    /**
     * @param string|null $changeDate
     */
    public function setChangeDate(?string $changeDate): void
    {
        $this->changeDate = $changeDate;
    }

    /**
     * @return PriceCollection|null
     */
    public function getOldPrice(): ?PriceCollection
    {
        return $this->oldPrice;
    }

    /**
     * @param PriceCollection|null $oldPrice
     */
    public function setOldPrice(?PriceCollection $oldPrice): void
    {
        $this->oldPrice = $oldPrice;
    }

    /**
     * @return PriceCollection|null
     */
    public function getNewPrice(): ?PriceCollection
    {
        return $this->newPrice;
    }

    /**
     * @param PriceCollection|null $newPrice
     */
    public function setNewPrice(?PriceCollection $newPrice): void
    {
        $this->newPrice = $newPrice;
    }
}