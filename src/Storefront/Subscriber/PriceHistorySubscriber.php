<?php

namespace PriceHistory\Storefront\Subscriber;

use Shopware\Core\Content\Product\ProductEvents;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Event\EntityWrittenEvent;
use Shopware\Core\Framework\DataAbstractionLayer\Pricing\Price;
use Shopware\Core\Framework\DataAbstractionLayer\Pricing\PriceCollection;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Sorting\FieldSorting;
use Shopware\Core\Framework\Uuid\Uuid;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class PriceHistorySubscriber implements EventSubscriberInterface
{
    private EntityRepository $priceHistoryRepository;
    private Entityrepository $productRepository;

    public function __construct(EntityRepository $priceHistoryRepository, EntityRepository $productRepository)
    {
        $this->priceHistoryRepository = $priceHistoryRepository;
        $this->productRepository = $productRepository;
    }

    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents(): array
    {
        return [
            ProductEvents::PRODUCT_WRITTEN_EVENT => 'written',
        ];
    }

    public function written(EntityWrittenEvent $event): void
    {
        $ids = $event->getIds();
        $context = $event->getContext();
        $this->writeHistory($context, $ids);
    }

    private function writeHistory(Context $context, array $ids): void
    {
        foreach ($ids as $id) {
            $productCriteria = new Criteria();
            $productCriteria->addFilter(new EqualsFilter('id', $id));
            $product = $this->productRepository->search($productCriteria, $context)->first();
            $currentId = $product->getId();
            $currentPrice = $this->collectionToArray($product->getPrice());
            $historyCriteria = new Criteria();
            $historyCriteria->addFilter(new EqualsFilter('productId', $currentId));
            $historyCriteria->addSorting(new FieldSorting('changeDate', 'DESC'));
            $historyCriteria->setLimit(1);
            $lastHistoryItem = $this->priceHistoryRepository->search($historyCriteria, $context)->first();
            $oldPrice = $lastHistoryItem ? $this->collectionToArray($lastHistoryItem->getNewPrice()) : [];
            if ($currentPrice !== $oldPrice) {
                $this->priceHistoryRepository->create([
                    [
                        'id' => Uuid::randomHex(),
                        'productId' => $currentId,
//                        'changeDate' => date('Y-m-d'),
                        'oldPrice' => $lastHistoryItem ? $oldPrice : $currentPrice,
                        'newPrice' => $currentPrice,
                    ],
                ], $context);
            }
        }
    }

    private function collectionToArray(PriceCollection $collection): array
    {
        $result = [];
        foreach ($collection as $element) {
            $result[] = [
                'currencyId' => $element->getCurrencyId(),
                'gross' => $element->getGross(),
                'net' => $element->getNet(),
                'linked' => $element->getLinked(),
            ];
        }
        return $result;
    }
}