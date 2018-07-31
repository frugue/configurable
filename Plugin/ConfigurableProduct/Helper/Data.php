<?php
namespace Frugue\Configurable\Plugin\ConfigurableProduct\Helper;
use Magento\Catalog\Model\Product as P;
use Magento\Catalog\Model\ResourceModel\Eav\Attribute as A;
use Magento\CatalogInventory\Api\StockRegistryInterface as IStockRegistry;
use Magento\CatalogInventory\Model\StockRegistry;
use Magento\ConfigurableProduct\Helper\Data as Sb;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable\Attribute as CA;
use Magento\Framework\Data\Collection;
use Magento\Framework\DataObject as _DO;
// 2018-06-04
final class Data {
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
	 * @see \Magento\ConfigurableProduct\Helper\Data::getOptions()
	 * @see \MagicToolbox\MagicZoomPlus\Helper\ConfigurableData::getOptions()
	 * @param Sb $sb
	 * @param \Closure $f
	 * @param P $current
	 * @param P[] $allowed
	 * @return array(string => mixed)
	 */
	function aroundGetOptions(Sb $sb, \Closure $f, P $current, array $allowed) {
		/**
		 * 2018-07-31
		 * It fixes the issue:
		 * «если я захожу в какой-то конкретный продукт,
		 * к примеру: https://frugue.com/uk/sl-553-mix.html
		 * и пытаюсь поменять цвет нажав на цветной квадратик ничего не происходит.»
		 * https://www.upwork.com/messages/rooms/room_51783fb0c631d9f71602d574a9205654/story_c85b4eb6899005bec27523aa32ff4089
		 */
		$f($current, $allowed);
		$stockR = df_stock_r(); /** @var IStockRegistry|StockRegistry $stockR */
		$r = []; /** @var array(string => mixed) $r */
		foreach ($allowed as $p) {  /** @var P $p */
			$p = df_product_load($id = $p->getId()); /** @var int $id */
			if ($stockR->getStockItem($p->getId(), $p->getStore()->getWebsiteId())->getQty()) {
				if ($images = $sb->getGalleryImages($p)) { /** @var Collection $images */
					foreach ($images as $i) { /** @var _DO $i */
						$r['images'][$id][] = [
							'caption' => $i['label']
							,'full' => $i['large_image_url']
							,'img' => $i['medium_image_url']
							,'isMain' => $i['file'] === $p->getImage()
							,'position' => $i['position']
							,'thumb' => $i['small_image_url']
						];
					}
				}
				foreach ($sb->getAllowAttributes($current) as $ca) { /** @var CA $ca */
					$a = $ca->getProductAttribute(); /** @var A $a */
					/** @var int $aid */ /** @var string $v */
					$r[$aid = $a->getId()][$v = $p->getData($a->getAttributeCode())][] = $id;
					$r['index'][$id][$aid] = $v;
				}
			}
		}
		return $r;
	}
}