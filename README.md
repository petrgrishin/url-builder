url-builder
===========
[![Travis CI](https://travis-ci.org/petrgrishin/url-builder.png "Travis CI")](https://travis-ci.org/petrgrishin/url-builder)
[![Coverage Status](https://coveralls.io/repos/petrgrishin/url-builder/badge.png?branch=master)](https://coveralls.io/r/petrgrishin/url-builder?branch=master)

Url builder

Установка
------------
Добавите зависимость для вашего проекта в composer.json:
```json
{
    "require": {
        "petrgrishin/url-builder": "~1.0"
    }
}
```

Постановка проблемы
------------
Необходимо определить знание об адресе контроллеров в одном слое сисстемы. Это позволит быстро и безболезненно производить поиск и рефакторинг контроллеров и их адресов.

Реализовать проверку достаточности параметров построения адреса, если такое происходит в другом слое, например в представлении или клиентских скриптах.

Решение
------------
Все адреса контроллеров должны быть определены в самих контроллерах. При необходимости недостающие параметры можно заполнить в слое представления или клиентского скрипта. Для удобной работы необходимо определить помощника - построитель адресов.

Примеры использования
------------
#### Определение знания об адресе в контроллере
Базовый абстрактный контроллер. Реализация метода создания обектов построителя адреса 
```php
class BaseController extends \CController {

    public function createUrlBuilder($route, $params = array()) {
        $urlBuilder = new SimpleUrlBuilder();
        $urlBuilder
            ->setRoute($route)
            ->setParams($params);
        return $urlBuilder;
    }
}
```

Конкретный контроллер. Использование построителя адреса
```php
class SiteController extends BaseController {

    public function actionIndex() {
        return $this->render('index', array(
            'urls' => array(
                'catalog' => $this->createUrlBuilder('site/catalog')
                    ->getUrl(),
                // передана готовая строка адреса /?r=site/catalog
            ),
        ));
    }
    
    public function actionCatalog() {
        return $this->render('about', array(
            'products' => Product::model()->findAll(),
            'urls' => array(
                'product' => $this->createUrlBuilder('site/product')
                    ->setRequired(array('id')),
                // передан объект построителя с необходимыми знаниями,
                // требуемые параметры заполняются в представлении
            ),
        ));
    }
    
    public function actionProduct($id) {
        return $this->render('product');
    }
}
```

Представление вывода каталога товаров
```php
/** @var UrlBuilder $productUrlBuilder */
$productUrlBuilder = $this->getParam('urls.product');

foreach ($this->getParam('products') as $product) {
    $productUrl = $productUrlBuilder
        ->copy()
        ->setParam('id', $product->id)
        ->getUrl();
        
    print($productUrl);
    // строка адреса /?r=site/product&id=1
}

// или передать параметры построителя адреса в клиентский скрипт
$this->setJsParams(array(
    'urls' => array(
        'product' => $productUrlBuilder->toArray(),
    ),
));
```



