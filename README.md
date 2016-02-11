Alias module for Yii 2
========================


Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require infoweb-internet-solutions/yii2-cms-alias "*"
```

or add

```
"infoweb-internet-solutions/yii2-cms-alias": "*"
```

to the require section of your `composer.json` file.


Usage
-----

Once the extension is installed, simply modify your application configuration as follows:

Your backend configuration as follows:

```php
'modules' => [
    ...
    'alias' => [
        'class' => 'infoweb\alias\Module',
        'reservedUrls' => ['page'] // Url's that are reserved by the application
    ],
],
```

Import the translations and use category 'infoweb/alias':
```
yii i18n/import @infoweb/alias/messages
```

To use the module, execute yii migration
```
yii migrate/up --migrationPath=@vendor/infoweb-internet-solutions/yii2-cms-alias/migrations
```

Behavior
--------

To use AliasBehavior, insert the following code to your ActiveRecord class:

```
use infoweb\seo\behaviors\SeoBehavior;

public function behaviors()
{
    return [
        'alias' => [
            'class' => AliasBehavior::className(),
        ],
    ];
}
```

Add this code to your view

```
 // Initialize the tabs
$tabs = [
    ...
    [
        'label' => 'SEO',
        'content' => $this->render('@infoweb/seo/views/behaviors/seo/_seo_tab', ['model' => $model, 'form' => $form]),
    ],
];
```