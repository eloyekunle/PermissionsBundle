<?php

/*
 * This file is part of the EloyekunlePermissionsBundle package.
 *
 * (c) Elijah Oyekunle <https://elijahoyekunle.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloyekunle\PermissionsBundle\Tests\Util;

use Eloyekunle\PermissionsBundle\Util\PermissionsHandler;
use Eloyekunle\PermissionsBundle\Util\PermissionsHandlerInterface;
use PHPUnit\Framework\TestCase;

class PermissionsHandlerTest extends TestCase
{
    /** @var PermissionsHandlerInterface */
    protected $permissionsHandler;

    public function setUp()
    {
        $this->permissionsHandler = new PermissionsHandler(__DIR__.'/../Fixtures');
    }

    public function testHandlerReadsDefinitions()
    {
        $permissions = $this->permissionsHandler->getPermissions();
        $this->assertArrayHasKey('edit users', $permissions);
        $this->assertSame('full_definition', $permissions['edit users']['provider']);
        $this->assertSame('Edit Users', $permissions['edit users']['title']);

        $this->assertArrayHasKey('delete users', $permissions);
        $this->assertSame('no_name', $permissions['delete users']['provider']);
        $this->assertSame('Delete Users', $permissions['delete users']['title']);
    }
}
