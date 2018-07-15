EloyekunlePermissionsBundle
===========================


[![Build Status](https://travis-ci.org/eloyekunle/PermissionsBundle.svg?branch=master)](https://travis-ci.org/eloyekunle/PermissionsBundle)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/eloyekunle/PermissionsBundle/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/eloyekunle/PermissionsBundle/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/eloyekunle/PermissionsBundle/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/eloyekunle/PermissionsBundle/?branch=master)
[![Total Downloads](https://poser.pugx.org/eloyekunle/permissions-bundle/downloads.svg)](https://packagist.org/packages/eloyekunle/permissions-bundle)
[![Latest Stable Version](https://poser.pugx.org/eloyekunle/permissions-bundle/v/stable.svg)](https://packagist.org/packages/eloyekunle/permissions-bundle)
[![Latest Unstable Version](https://poser.pugx.org/eloyekunle/permissions-bundle/v/unstable.png)](https://packagist.org/packages/eloyekunle/permissions-bundle)

The EloyekunlePermissionsBundle provides support for a database-backed permissions system in Symfony2.
It provides a flexible framework for permissions management that aims to handle common tasks such as flexible
Permissions Definitions, Roles Creation and Authorization Checking (using Symfony Voters).

Features include:

- Roles can be stored via Doctrine ORM.
- Flexible & Modular permissions definitions in YAML files. From few permissions to hundreds, this bundle has your back.
- Symfony Voter for Authorization Checking.
- Unit tested.

## INSTALLATION
Installation is a quick (I promise!) 5 step process:

1. Download EloyekunlePermissionsBundle using composer
2. Enable the Bundle
3. Create your Role class
4. Configure the EloyekunlePermissionsBundle
5. Update your database schema

### Step 1: Download the bundle

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```bash
$ composer require "eloyekunle/permissions-bundle"
```

This command requires you to have Composer installed globally, as explained
in the [installation chapter](https://getcomposer.org/doc/00-intro.md) of the Composer documentation.

### Step 2: Enable the bundle

Then, enable the bundle by adding the following line in the ``config/bundles.php``
file of your project, e.g. (Symfony >=4):

```php
    // config/bundles.php
    return [
        // ...
        Eloyekunle\PermissionsBundle\EloyekunlePermissionsBundle::class => ['all' => true],
    ];
```

### Step 3: Create Role class

The goal of this bundle is to persist some ``Role`` class to a database (MySql,
MongoDB, CouchDB, etc). Your first job, then, is to create the ``Role`` class
for your application. This class can look and act however you want: add any
properties or methods you find useful. This is *your* ``Role`` class.

The bundle provides base classes which are already mapped for most fields
to make it easier to create your entity. Here is how you use it:

1. Extend the base ``Role`` class (from the ``Model`` folder if you are using
   any of the doctrine variants)
2. Map the ``id`` field. It must be protected as it is inherited from the parent class.

__Caution__
    When you extend from the mapped superclass provided by the bundle, don't
    redefine the mapping for the other fields as it is provided by the bundle.

In the following sections, you'll see examples of how your ``Role`` class should
look, depending on how you're storing your roles (Doctrine ORM, MongoDB ODM,
or CouchDB ODM).

__Note__
    The doc uses a bundle named ``AppBundle`` according to the Symfony best
    practices. However, you can of course place your role class in the bundle
    you want.

__Caution__
    If you override the __construct() method in your Role class, be sure
    to call parent::__construct(), as the base Role class depends on
    this to initialize some fields.

#### Doctrine ORM Role class

If you're persisting your roles via the Doctrine ORM, then your ``Role`` class
should live in the ``Entity`` namespace of your bundle and look like this to
start:

##### PHP

```php
<?php
// src/AppBundle/Entity/Role.php

namespace AppBundle\Entity;

use Eloyekunle\PermissionsBundle\Model\Role as BaseRole;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="role")
 */
class Role extends BaseRole
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    public function __construct()
    {
        parent::__construct();
        // your own logic
    }
}
```

##### YAML

```yaml
# src/AppBundle/Resources/config/doctrine/Role.orm.yml
AppBundle\Entity\User:
    type:  entity
    table: role
    id:
        id:
            type: integer
            generator:
                strategy: AUTO
```

##### XML

```xml
<?xml version="1.0" encoding="utf-8"?>
<!-- src/AppBundle/Resources/config/doctrine/Role.orm.xml -->
<doctrine-mapping xmlns="http://doctrine-project.org/schemas/orm/doctrine-mapping"
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:schemaLocation="http://doctrine-project.org/schemas/orm/doctrine-mapping http://doctrine-project.org/schemas/orm/doctrine-mapping.xsd">

    <entity name="AppBundle\Entity\User" table="role">
        <id name="id" type="integer" column="id">
            <generator strategy="AUTO"/>
        </id>
    </entity>
</doctrine-mapping>
```

### Step 4: Configure the EloyekunlePermissionsBundle

### Step 5: Update your database schema


## USAGE


TODO
----
- [ ] Add support for MongoDB/CouchDB ORM
- [ ] Performance improvements (some caching??)
- [ ] Persist DEFAULT_ROLE on initial migrations.
- [ ] Add Events to major actions.
- [ ] Explore use of expressions in permissions definitions.
- [ ] Console commands to manage Roles and Permissions.