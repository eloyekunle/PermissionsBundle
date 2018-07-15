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

use Symfony\Component\Form\Extension\Validator\Type\FormTypeValidatorExtension;
use Symfony\Component\Validator\ConstraintViolationList;

/**
 * Class ValidatorExtensionTypeTestCase
 * FormTypeValidatorExtension added as default. Useful for form types with `constraints` option.
 *
 * @author Sullivan Senechal <soullivaneuh@gmail.com>
 */
class ValidatorExtensionTypeTestCase extends TypeTestCase
{
    /**
     * @return array
     */
    protected function getTypeExtensions()
    {
        $validator = $this->getMockBuilder('Symfony\Component\Validator\Validator\ValidatorInterface')->getMock();
        $validator->method('validate')->will($this->returnValue(new ConstraintViolationList()));

        return array(
            new FormTypeValidatorExtension($validator),
        );
    }
}
