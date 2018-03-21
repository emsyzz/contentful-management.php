<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2018 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Management\Resource\ContentType\Field;

/**
 * LocationField class.
 */
class LocationField extends BaseField
{
    /**
     * @return string
     */
    public function getType(): string
    {
        return 'Location';
    }
}
