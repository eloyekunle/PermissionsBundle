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

- Roles can be stored via Doctrine ORM (Support for MongoDB/CouchDB ORM not available yet).
- Flexible permissions definitions, in YAML files.
- Symfony Voter for Authorization Checking.
- Unit tested.

Installation
---------------
Installation is a quick (I promise!) 5 step process:

1. Download EloyekunlePermissionsBundle using composer
2. Enable the Bundle
3. Create your Role class
4. Configure the EloyekunlePermissionsBundle
5. Update your database schema

#### Step 1: Download the bundle

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```bash
$ composer require eloyekunle/permissions-bundle
```

This command requires you to have Composer installed globally, as explained
in the (installation chapter)[https://getcomposer.org/doc/00-intro.md] of the Composer documentation.

#### Step 2: Enable the bundle

Then, enable the bundle by adding the following line in the ``config/bundles.php``
file of your project, e.g. (Symfony >=4):

```php
    // config/bundles.php
    return [
        // ...
        new FOS\RestBundle\FOSRestBundle(),
    ];
```

#### Step 3: Create Role class

#### Step 4: Configure the EloyekunlePermissionsBundle

#### Step 5: Update your database schema


TODO
----
- [x] Add support for MongoDB/CouchDB ORM
- [x] Performance improvements (some caching??)
- [x] Persist DEFAULT_ROLE on initial migrations.
- [x] Add Events to major actions.
- [x] Explore use of expressions in permissions definitions.
- [x] Console commands to manage Roles and Permissions.