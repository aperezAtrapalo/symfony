<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Serializer\Tests\Fixtures;

use Makhan\Component\Serializer\Annotation\Groups;

/**
 * @author Kévin Dunglas <dunglas@gmail.com>
 */
interface GroupDummyInterface
{
    /**
     * @Groups({"a", "name_converter"})
     */
    public function getMakhan();
}
