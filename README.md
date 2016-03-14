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

### Step 5: Register the entities

```xml
# src/AppBundle/Resources/config/doctrine/Category.orm.xml
<?xml version="1.0" encoding="utf-8"?>
<doctrine-mapping xmlns="http://doctrine-project.org/schemas/orm/doctrine-mapping"
                  xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
                  xsi:schemaLocation="http://doctrine-project.org/schemas/orm/doctrine-mapping http://doctrine-project.org/schemas/orm/doctrine-mapping.xsd">
    <entity name="AppBundle\Entity\Category" repository-class="Vlabs\CmsBundle\Repository\CategoryRepository">
        <one-to-many field="posts" target-entity="Post" mapped-by="category">
            <order-by>
                <order-by-field name="position" direction="ASC"/>
            </order-by>
        </one-to-many>
        <one-to-many field="children" target-entity="Category" mapped-by="parent">
            <order-by>
                <order-by-field name="position" direction="ASC"/>
            </order-by>
        </one-to-many>
        <many-to-one field="parent" target-entity="Category" inversed-by="children"/>
    </entity>
</doctrine-mapping>
```
```php
// src/AppBundle/Entity/Category.php
<?php

namespace AppBundle\Entity;

use Vlabs\CmsBundle\Entity\Category as BaseCategory;
use Vlabs\CmsBundle\Entity\CategoryTrait;

class Category extends BaseCategory
{
    use CategoryTrait;
}
```

```xml
# src/AppBundle/Resources/config/doctrine/Post.orm.xml
<?xml version="1.0" encoding="utf-8"?>
<doctrine-mapping xmlns="http://doctrine-project.org/schemas/orm/doctrine-mapping"
                  xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
                  xsi:schemaLocation="http://doctrine-project.org/schemas/orm/doctrine-mapping http://doctrine-project.org/schemas/orm/doctrine-mapping.xsd">
    <entity name="AppBundle\Entity\Post">
        <many-to-one field="category" target-entity="Category" inversed-by="posts"/>
        <many-to-many field="relatedPosts" target-entity="Post"/>
        <many-to-many field="tags" target-entity="Tag" inversed-by="posts"/>
    </entity>
</doctrine-mapping>
```
```php
// src/AppBundle/Entity/Post.php
<?php

namespace AppBundle\Entity;

use Vlabs\CmsBundle\Entity\Post as BasePost;
use Vlabs\CmsBundle\Entity\PostTrait;

class Post extends BasePost
{
    use PostTrait;
}
```

```xml
# src/AppBundle/Resources/config/doctrine/Tag.orm.xml
<?xml version="1.0" encoding="utf-8"?>
<doctrine-mapping xmlns="http://doctrine-project.org/schemas/orm/doctrine-mapping"
                  xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
                  xsi:schemaLocation="http://doctrine-project.org/schemas/orm/doctrine-mapping http://doctrine-project.org/schemas/orm/doctrine-mapping.xsd">
    <entity name="AppBundle\Entity\Tag">
        <many-to-many field="posts" target-entity="Post" mapped-by="tags"/>
    </entity>
</doctrine-mapping>

```
```php
// src/AppBundle/Entity/Tag.php
<?php

namespace AppBundle\Entity;

use Vlabs\CmsBundle\Entity\Tag as BaseTag;
use Vlabs\CmsBundle\Entity\TagTrait;

class Tag extends BaseTag
{
    use TagTrait;
}
```
