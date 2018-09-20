<?php

namespace MageSuite\CmsProductBacklink\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    public function removeSpecificPageIdFromIds($cmsPageIds, $pageId)
    {
        return array_values(array_diff($cmsPageIds, [$pageId]));
    }

    public function mapPagesToProducts($productsIdsAssociatedWithPages)
    {
        $products = [];

        foreach($productsIdsAssociatedWithPages as $pageId => $productIds){
            foreach($productIds as $productId){
                $products[$productId][] = $pageId;
            }
        }

        return $products;
    }

    public function getProductIdsFromIdentities($identities)
    {
        $ids = array_map(
            function($identity) {
                if(substr($identity, 0, 6) !== 'cat_p_'){
                    return null;
                }
                return substr($identity, 6);
            },
            $identities
        );

        return $this->removeEmptyValuesFromArray($ids);
    }

    private function removeEmptyValuesFromArray($ids)
    {
        return array_filter($ids);
    }
}
