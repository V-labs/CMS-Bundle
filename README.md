VlabsCmsBundle
================

Installation
------------

### Step 1: Download the bundle

Open your command console, browse to your project and execute the following:

```sh
$ composer require vlabs/cms-bundle
```

### Step 2: Enable the bundle

``` php
// app/AppKernel.php
public function registerBundles()
{
    return array(
        // ...
        new FOS\JsRoutingBundle\FOSJsRoutingBundle(),
        new Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),
        new Vlabs\CmsBundle\VlabsCmsBundle(),
    );
}
```


### Step 3: Register the configuration

```yaml
# app/config/config.yml
// ...
framework:
    translator: { fallbacks: ["%locale%"] }
    
stof_doctrine_extensions:
    orm:
        default:
            softdeleteable: true
            timestampable: true

services:
    twig.extension.intl:
        class: Twig_Extensions_Extension_Intl
        tags:
            - { name: twig.extension }

vlabs_cms:
    category_class: AppBundle\Entity\Category
    post_class: AppBundle\Entity\Post
    tag_class: AppBundle\Entity\Tag
```



### Step 4: Register the routing definitions

```yaml
# app/config/routing.yml
// ...

app_cms_front:
    resource: "@VlabsCmsBundle/Resources/config/routing/front.yml"
    prefix: /

app_cms_admin:
    resource: "@VlabsCmsBundle/Resources/config/routing/admin.yml"
    prefix: /admin

fos_js_routing:
    resource: "@FOSJsRoutingBundle/Resources/config/routing/routing.xml"

```
