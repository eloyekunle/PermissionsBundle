EloyekunlePermissionsBundle
===========================

[![Build Status](https://travis-ci.org/eloyekunle/PermissionsBundle.svg?branch=master)](https://travis-ci.org/eloyekunle/PermissionsBundle)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/eloyekunle/PermissionsBundle/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/eloyekunle/PermissionsBundle/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/eloyekunle/PermissionsBundle/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/eloyekunle/PermissionsBundle/?branch=master)

The EloyekunlePermissionsBundle provides support for a database-backed permissions system in Symfony2.
It provides a flexible framework for permissions management that aims to handle common tasks such as flexible
Permissions Definitions, Roles Creation and Authorization Checking (using Symfony Voters).

Features include:

- Roles can be stored via Doctrine ORM.
- Flexible & Modular permissions definitions in YAML files. From few permissions to hundreds, this bundle has your back.
- Symfony Voter for Authorization Checking.
- Unit tested.

CONTENTS
--------

* [Installation](#installation)
* [Usage](#usage)
* [Contributions](#contributions)
* [Support](#support)
* [Credits](#credits)

## INSTALLATION
Installation is a quick (I promise!) 5 step process:

1. [Download EloyekunlePermissionsBundle using composer](#step-1-download-the-bundle)
2. [Enable the Bundle](#step-2-enable-the-bundle)
3. [Create your Role class](#step-3-create-role-class)
4. [Configure your User class](#step-4-configure-your-user-class)
5. [Configure the bundle](#step-5-configure-the-bundle)
6. [Update your database schema](#step-5-update-your-database-schema)

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

The goal of this bundle is to persist some ``Role`` class to a database. 
Your first job, then, is to create the ``Role`` class
for your application. This class can look and act however you want: add any
properties or methods you find useful. This is *your* ``Role`` class.

The bundle provides base classes which are already mapped for most fields
to make it easier to create your entity. Here is how you use it:

1. Extend the base ``Role`` class (from the ``Model`` folder if you are using
   any of the doctrine variants)
2. Map the ``id`` field. It must be protected as it is inherited from the parent class.
3. When you extend from the mapped superclass provided by the bundle, don't 
   redefine the mapping for the other fields as it is provided by the bundle.
4. If you override the __construct() method in your Role class, be sure 
   to call parent::__construct(), as the base Role class depends on this to initialize some fields.

#### Doctrine ORM Role class

If you're persisting your roles via the Doctrine ORM, then your ``Role`` class
should live in the ``Entity`` namespace of your bundle and look like this to
start:

##### PHP

```php
<?php
// src/App/Entity/Role.php

namespace App\Entity;

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

### Step 4: Configure your User class

The bundle currently ships with a [`User`](https://github.com/eloyekunle/PermissionsBundle/blob/master/Model/User.php)
class which you can extend from your own `User` entity.

##### PHP

```php
<?php
// src/App/Entity/User.php

namespace App\Entity;

use Eloyekunle\PermissionsBundle\Model\User as BaseUser;

class User extends BaseUser
{
    
    public function __construct()
    {
        parent::__construct();
        // your own logic
    }
}
```

If you already extend another base user class in your `User` entity (e.g. from the excellent [FOSUserBundle](https://github.com/FriendsOfSymfony/FOSUserBundle)),
you will need to implement [`Eloyekunle\PermissionsBundle\Model\UserInterface`](https://github.com/eloyekunle/PermissionsBundle/blob/master/Model/UserInterface.php)
in your entity and implement the methods (especially `UserInterface::hasPermission`). You can view the [`User`](https://github.com/eloyekunle/PermissionsBundle/blob/master/Model/User.php)
Entity for sample implementation details.

### Step 5: Configure the Bundle

To configure the bundle, add your custom `Role` class that was created above, and also a path where your permissions
will be defined. E.g.

```yaml
# config/packages/eloyekunle_permissions.yaml
eloyekunle_permissions:
  role_class: App\Entity\Role
  module:
    definitions_path: '%kernel.project_dir%/config/modules'
```

### Step 6: Update your database schema

Now that the bundle is configured, the last thing you need to do is update your
database schema because you have added a new entity, the ``Role`` class which you
created in Step 3.

Run the following command.

```bash
$ php bin/console doctrine:schema:update --force
```

**Or** to create and execute a migration:

```bash
$ php bin/console doctrine:migrations:diff
$ php bin/console doctrine:migrations:migrate
```

## USAGE

To start using the bundle:
1. [Set up a few permissions](#2-set-up-a-few-permissions)
2. [Set up a Role](#1-set-up-a-role)
3. [Check Permissions in your Controllers](#4-check-permissions-in-your-controllers)

### 1. Set up a few permissions

This bundle currently supports a modular form of defining permissions, since a lot of projects usually have various
components e.g. Content Management, User Management, Comments, Files etc. You can easily separate your permissions
into various YAML files in your `config/modules` directory (or any directory as long as you specify it in the
config as [described above](#step-5-configure-the-bundle)).
For example:
```yaml
# config/modules/content.yaml
# This shows the basic expected structure of the permissions definitions.

name: 'Content'

permissions:
    # The key is the important stuff here. You can add your own fields too...
    edit content:
      # E.g. Add a human-readable name for the permission.
      title: 'Edit Content'
      description: 'Grants permission to edit content.'
      dependencies:
        - view content
    view content:
      title: 'View Content'
      description: 'Grants permission to view content.'
```

The bundle ships with a [`PermissionsHandler`](https://github.com/eloyekunle/PermissionsBundle/blob/master/Util/PermissionsHandlerInterface.php)
that is available as a service and can be injected into your Controllers/Services. You can use it to get all available
permissions.

```php
<?php
// src/Controller/PermissionsController.php
namespace App\Controller;

use Eloyekunle\PermissionsBundle\Util\PermissionsHandler;

class PermissionsController {
    public function getPermissions(PermissionsHandler $permissionsHandler)
    {
        /*
         * @var array
         * 
         * array (
         *    'edit content' => 
         *        array (
         *          'title' => 'Edit Content',
         *          'description' => 'Grants permission to edit content.',
         *          'dependencies' => 
         *              array (
         *                0 => 'view content',
         *              ),
         *          'provider' => 'content',
         *        ),
         *    'view content' => 
         *        array (
         *          'title' => 'View Content',
         *          'description' => 'Grants permission to view content.',
         *          'provider' => 'content',
         *        ),
         *  )
         */
        $permissions = $permissionsHandler->getPermissions();
        // ........................
    }
}
```

### 2. Set up a Role

The bundle ships with a [`RoleManager`](https://github.com/eloyekunle/PermissionsBundle/blob/master/Model/RoleManagerInterface.php)
which is available as a service and can be injected into your Controllers/Services. It contains useful utility methods
to manager roles. You can also `get` it from the container as `eloyekunle_permissions.role_manager`.

```php
<?php
// src/App/Controller/RoleController.php
namespace App\Controller;

use Eloyekunle\PermissionsBundle\Doctrine\RoleManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class RoleController extends Controller 
{
    public function create(RoleManager $roleManager) {
        $role = $roleManager->createRole();
        $role->setRole('Content Admin');
        
        $roleManager->updateRole($role);
        // ......
    }
}
```

This creates and persists a `Role` entity to your database.

### 3. Check Permissions in your Controllers

The bundle ships with a [voter](https://symfony.com/doc/current/security/voters.html), [`PermissionsVoter`](https://github.com/eloyekunle/PermissionsBundle/blob/master/Security/PermissionsVoter.php).
You can check for user permissions by using the `isGranted()` method on Symfony's authorization checker or call 
`denyAccessUnlessGranted()` in a controller.

```php
<?php
// src/App/Controller/ContentController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class ContentController extends Controller 
{
    
    /**
     * @Route("/content/{id}/edit", name="content_edit")
     * 
     * 
     */
    public function edit($id)
    {
        $this->denyAccessUnlessGranted('edit content');
        // Get a Content object - e.g. query for it.
        // $content = ......;
    }
    
    /**
     * @Route("/content/{id}", name="content_show")
     */
    public function show($id)
    {
        $this->denyAccessUnlessGranted('view content');
        // Get a Content object - e.g. query for it.
        // $content = ......;
    }
}
```

## CONTRIBUTIONS

Contributions of any kind are welcome: code, documentation, ideas etc.
Issues and feature requests are tracked in the [Github issue tracker](https://github.com/eloyekunle/PermissionsBundle/issues).

## SUPPORT
If you need any support related to this bundle, you can contact me on the [Symfony Slack group](http://symfony-devs.slack.com) 
(eloyekunle), or send me an email (eloyekunle@gmail.com).

## CREDITS
- Bundle inspired by the [Drupal Permissions System](https://api.drupal.org/api/drupal/core!core.api.php/group/user_api/8.5.x)!
- Implementation inspired by some excellent Symfony bundles, especially [FOSUserBundle](https://github.com/FriendsOfSymfony/FOSUserBundle).
- [Elijah Oyekunle](https://elijahoyekunle.com) - [LinkedIn](https://www.linkedin.com/in/elijahoyekunle) - [Twitter](https://twitter.com/elijahoyekunle) - [Github](https://github.com/eloyekunle)