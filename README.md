AssetLoaderBundle
=================

Introduction
------------

With the Symfony 2 (or 3) framework, we found it troublesome to load assets for custom form type widgets.
A recurrent issue which comes up is the loading of front dependencies embedded to the widgets.

### Issue 1 - dependencies order

Let's say you want to create a form type, child of the text form type, 
whose widget will color the background of the input type text; as soon as the input is empty.
Your widget will look like this:

Form/fields.html.twig
```twig
{%- block colored_text_widget -%}
    <div class="colored">
      {{- form_widget(form) -}}
    </div>
    <style>
        .colored input[type="text"] {
            border-color: #c9302c,
            background-color: #f3d9d9
        }
    </style>
    <script type="text/javascript">
        $(document).on('change', '.colored input[type="text"]', function () {
          if ($input.val()) {
            $input.css({
              'border-color': '#cccccc',
              'background-color': '#ffffff'
            });
          } else {
            $input.css({
              'border-color': '#c9302c',
              'background-color': '#f3d9d9'
            });
          }
        });
    </script>
{%- endblock -%}
```

As you can see, this javascript code requires jQuery.
JQuery will not be available when this script will be executed
(unless you place the Jquery script in the head of the html document, but we don't want that)

A possible solution would be to wrap this code with the following vanilla function 

```javascript
window.addEventListener('load', function () {
    // code goes here ...
});
```

This way, when the code will be executed, Jquery should be ready to be used.
But what if your widget is based on an entire library?

### Issue 2 - dependencies duplication

Given the same form type as in the example above, if you have a page in your web application that render this form 3 times,
you will end up with your scripts and stylesheets duplicated 3 times in the DOM. You only need it once.

This bundle attempts to solve those issues.

Installation
------------

Add the dependency in your `composer.json` file:
```json
"require": {
    ...
    "idci/asset-loader-bundle": "dev-master"
},
```

Install these new dependencies in your application using composer:
```sh
$ php composer.phar update
```

Or with docker and docker-compose:

```sh
$ make composer-update
```

Register needed bundles in your application kernel:
```php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new IDCI\Bundle\AssetLoaderBundle\IDCIAssetLoaderBundle(),
    );
}
```

If you want to activate the subscriber to load your assets automatically ([more on that later](#loading-your-assets-automatically), add the following in your config.yml file.

```yml
# app/config/config.yml

idci_asset_loader:
    auto_load: true
```

Usage
-----

### Asset declaration

Adding assets to your form type is pretty simple:

 * Your **AbstractType** must implements the method **getAssetCollection()** from the **AssetProviderInterface** interface. 
 The **getAssetCollection** contains an array of Asset Objects.
 * You must define your type as a service and add the tag with name **idci_asset_loader.asset_provider**

In the example below, we load assets from a form type. An AssetProvider does not necessarily have to be a form type, any service will do the job.

AbstractType

```php
namespace MyBundle/Form/Type;

use IDCI\Bundle\AssetLoaderBundle\AssetProvider\AssetProviderInterface;
use IDCI\Bundle\AssetLoaderBundle\Model\AssetCollection;

class MyType extends AbstractType implements AssetProviderInterface
{
    /**
     * @var AssetCollection
     */
    private $assetCollection;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->assetCollection = new AssetCollection();
    }

    /**
     * {@inheritDoc}
     */
    public function getAssetCollection()
    {
        return $this->assetCollection;
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $this->assetCollection->add(new Asset('MyBundle:Form:form_type_asset.html.twig', $options));
        ...

        return $view->vars;
    }

    ....
}
```

Services.yml

```yml
services:
    my_type:
        class: MyBundle\Form\Type\MyType
        tags:
            - { name: form.type, alias: my_type }
            - { name: idci_asset_loader.asset_provider, alias: my_type }
```

### Loading your assets manually

You can use the **idci_asset_loader.asset_dom_loader** service to load the asset for one or all the providers.
This will register a subscriber which will append the assets to the dom (at the end of the body) on kernel response.

```php
<?php

// Load assets from all providers
$this->get('idci_asset_loader.asset_dom_loader')->loadAll();

// Load assets from one provider
$this->get('idci_asset_loader.asset_dom_loader')->load('my_type');
```

### Loading your assets automatically

In most case, you will just want to let the subscriber load all the assets for you. Simply set **auto_load** to true in the configuration.

Tests
-----

Run the tests:

```bash
$ php ./vendor/bin/phpunit --coverage-text
```

Or with docker and docker-compose:

```bash
$ make phpunit
```
