<?php

namespace PriceHistory\Storefront\Controller;

use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
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
            return new JsonResponse("Price history was disabled");
        }
        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('productId', $productId));
        $criteria->setLimit(5);
        $history = $this->priceHistoryRepository->search($criteria, $context);
//        if (empty($history)) {
//            $history = ['This price never changed'];
//        }
        return new JsonResponse($history);
    }
}