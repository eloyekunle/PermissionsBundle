<?php

/*
 * This file is part of the EloyekunlePermissionsBundle package.
 *
 * (c) Elijah Oyekunle <https://elijahoyekunle.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloyekunle\PermissionsBundle\Tests\Form\Type;

use Eloyekunle\PermissionsBundle\Form\Type\RoleFormType;
use Eloyekunle\PermissionsBundle\Tests\TestRole;

class RoleFormTypeTest extends ValidatorExtensionTypeTestCase
{
    public function testSubmit()
    {
        $role = new TestRole();

        $form = $this->factory->create(RoleFormType::class, $role);
        $formData = [
            'role' => 'Content Admin',
            'permissions' => ['view content', 'delete content'],
        ];
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertSame($role, $form->getData());
        $this->assertSame('Content Admin', $role->getRole());
        $this->assertSame($formData['permissions'], $role->getPermissions());
    }

    /**
     * @return array
     */
    protected function getTypes()
    {
        return array_merge(parent::getTypes(), array(
            new RoleFormType('Eloyekunle\PermissionsBundle\Tests\TestRole'),
        ));
    }
}
