A custom configurable products module for [frugue.com](https://frugue.com).

## How to install
```        
bin/magento maintenance:enable
rm -rf composer.lock
composer clear-cache
composer require frugue/configurable:*
bin/magento setup:upgrade
bin/magento cache:enable
rm -rf var/di var/generation generated/code
bin/magento setup:di:compile
rm -rf pub/static/*
bin/magento setup:static-content:deploy \
	--area adminhtml \
	--theme Magento/backend \
	-f en_US
bin/magento setup:static-content:deploy \
	--area frontend \
	--theme TemplateMonster/theme007 \
	-f en_US de_DE fr_FR ru_RU
bin/magento maintenance:disable
```

## How to upgrade
```
bin/magento maintenance:enable
composer remove frugue/configurable
rm -rf composer.lock
composer clear-cache
composer require frugue/configurable:*
bin/magento setup:upgrade
bin/magento cache:enable
rm -rf var/di var/generation generated/code
bin/magento setup:di:compile
rm -rf pub/static/*
bin/magento setup:static-content:deploy \
	--area adminhtml \
	--theme Magento/backend \
	-f en_US
bin/magento setup:static-content:deploy \
	--area frontend \
	--theme TemplateMonster/theme007 \
	-f en_US de_DE fr_FR ru_RU
bin/magento maintenance:disable
```

## Features
- If one or more sizes of particular color are out of stock, those sizes should be marked in grey color some that customer could clearly understand that particular size is unavailable.
- If all sizes of particular color are out of stock, all sizes should be in grey color. Also, a color box should be marked same or similar to Amazon example so that customer could clearly understand that a whole color is unavailable. Color box could be additionally crossed out
- If a product doesn’t have any active size/color variations, then it should not be visible to a customer at all.