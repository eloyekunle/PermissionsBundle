<?php

/*
 * This file is part of the EloyekunlePermissionsBundle package.
 *
 * (c) Elijah Oyekunle <https://elijahoyekunle.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloyekunle\PermissionsBundle\Model;

abstract class RoleManager implements RoleManagerInterface
{
    /**
     * {@inheritdoc}
     */
    public function createRole()
    {
        $class = $this->getClass();
        $role = new $class();

        return $role;
    }
}
