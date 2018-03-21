<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2018 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Management\Mapper;

use Contentful\Core\Api\DateTimeImmutable;
use Contentful\Management\Resource\PersonalAccessToken as ResourceClass;
use Contentful\Management\SystemProperties;

/**
 * PersonalAccessToken class.
 *
 * This class is responsible for converting raw API data into a PHP object
 * of class Contentful\Management\Resource\PersonalAccessToken.
 */
class PersonalAccessToken extends BaseMapper
{
    /**
     * {@inheritdoc}
     */
    public function map($resource, array $data): ResourceClass
    {
        return $this->hydrate($resource ?: ResourceClass::class, [
            'sys' => new SystemProperties($data['sys']),
            'name' => $data['name'],
            'isReadOnly' => !\in_array('content_management_manage', $data['scopes'], true),
            'revokedAt' => isset($data['revokedAt']) ? new DateTimeImmutable($data['revokedAt']) : null,
            'token' => $data['token'] ?? null,
        ]);
    }
}
