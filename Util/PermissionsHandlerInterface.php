<?php

/*
 * This file is part of the EloyekunlePermissionsBundle package.
 *
 * (c) Elijah Oyekunle <https://elijahoyekunle.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloyekunle\PermissionsBundle\Util;

interface PermissionsHandlerInterface
{
    /**
     * Gets all available permissions.
     *
     * @return array
     *               A multi-dimensional array with the permission key as the keys, and other fields in an array as the value
     */
    public function getPermissions();
}
