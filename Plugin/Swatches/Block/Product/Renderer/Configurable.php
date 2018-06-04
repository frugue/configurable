<?php
namespace Frugue\Configurable\Plugin\Swatches\Block\Product\Renderer;
use Magento\Catalog\Model\Product as P;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable as T;
use Magento\Swatches\Block\Product\Renderer\Configurable as Sb;
// 2018-06-04
final class Configurable {
	/**
	 * 2018-06-04
	 * «If one or more sizes of particular color are out of stock,
	 * those sizes should be marked in grey color some
	 * that customer could clearly understand that particular size is unavailable.»
	 * «If all sizes of particular color are out of stock, all sizes should be in grey color.
	 * Also, a color box should be marked same or similar to Amazon example
	 * so that customer could clearly understand that a whole color is unavailable.»
	 * https://www.upwork.com/ab/f/contracts/20124469
	 * https://magento.stackexchange.com/a/191435
	 * @see \Magento\ConfigurableProduct\Block\Product\View\Type\Configurable::getAllowProducts()
	 * https://github.com/magento/magento2/blob/2.2.4/app/code/Magento/ConfigurableProduct/Block/Product/View/Type/Configurable.php#L163-L182
	 * @param Sb $sb
	 * @return Sb
	 */
	function aroundGetAllowProducts(Sb $sb) {
		if (!$sb->hasData($k = 'allow_products')) {
			$t = $sb->getProduct()->getTypeInstance(); /** @var T $t */
			$sb[$k] = $t->getUsedProducts($sb->getProduct(), null);
			if (!df_product_h()->getSkipSaleableCheck()) {
				$sb[$k] = array_filter($sb[$k], function(P $p) {return $p->isSaleable();});
			}
		}
		return $sb[$k];
	}
}