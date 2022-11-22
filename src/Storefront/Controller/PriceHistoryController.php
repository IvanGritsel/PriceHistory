<?php

namespace PriceHistory\Storefront\Controller;

use Shopware\Core\Defaults;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Pricing\PriceCollection;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Sorting\FieldSorting;
use Shopware\Core\System\SystemConfig\SystemConfigService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(defaults={"_routeScope"={"storefront"}})
 */
class PriceHistoryController extends AbstractController
{
    private EntityRepository $priceHistoryRepository;
    private SystemConfigService $systemConfigService;

    public function __construct(EntityRepository $priceHistoryRepository, SystemConfigService $systemConfigService)
    {
        $this->priceHistoryRepository = $priceHistoryRepository;
        $this->systemConfigService = $systemConfigService;
    }

    /**
     * @param string $productId
     * @param Context $context
     * @return JsonResponse
     *
     * @Route("/price_history/price_change/{productId}", name="frontend.price_history.price_change", defaults={"XmlHttpRequest"=true}, methods={"GET"})
     */
    public function priceChange(string $productId, Context $context): JsonResponse
    {
        if (!$this->systemConfigService->get('PriceHistory.config.showInStorefront')) {
            return new JsonResponse(["Price history was disabled"]);
        }
        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('productId', $productId));
        $criteria->addSorting(new FieldSorting('changeDate', 'DESC'));
        $criteria->setLimit(5);
        $history = $this->priceHistoryRepository->search($criteria, $context)->getEntities();

        $items = [];

        foreach ($history as $item) {
            $date = $item->getCreatedAt();
            $items[date('Y-m-d H:i:s', $date->getTimestamp())] = [
                'old' => $this->getGrossPrice($item->getOldPrice()),
                'new' => $this->getGrossPrice($item->getNewPrice()),
            ];
        }
        return new JsonResponse($items);
    }

    private function getGrossPrice(PriceCollection $collection): float
    {
        $price = $collection->get(Defaults::CURRENCY);
        return $price->getGross();
    }
}