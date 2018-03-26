<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2018 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Management\Proxy;

use Contentful\Core\Api\Link;
use Contentful\Management\Client;
use Contentful\Management\Resource\Behavior\CreatableInterface;
use Contentful\Management\Resource\ResourceInterface;

/**
 * EnvironmentProxy class.
 *
 * This class works as a lazy reference to an environment resource.
 * You can use it for most environment-related needs, such as fetching entries, assets and content types,
 * or creating them (attaching them to this space).
 *
 * To access this class, you can use the convenience method found in a client object.
 *
 * ``` php
 * $environment = $client->getEnvironmentProxy($spaceId, $environmentId);
 * ```
 *
 * The methods provided are very similar to the getX() methods you will find in an actual environment resource object.
 * The main difference is that when fetching a environment resource, you will actually call the API,
 * whereas with this proxy, you're just holding a reference to a certain environment.
 * This is the reason why in most situations you should prefer using
 * an EnvironmentProxy rather than an environment resource.
 *
 * ``` php
 * // Only the entries query will be made
 * $environment = $client->getEnvironmentProxy($spaceId, $environmentId);
 * $entries = $environment->getEntries();
 *
 * // Two queries will be made
 * $environment = $client->getEnvironment($spaceId, $environmentId);
 * $entries = $environment->getEntries();
 * ```
 */
class EnvironmentProxy
{
    use Extension\EnvironmentProxyExtension;

    /**
     * @var Client
     */
    private $client;

    /**
     * @var string
     */
    private $spaceId;

    /**
     * @var string
     */
    private $environmentId;

    /**
     * @param Client $client
     * @param string $spaceId
     * @param string $environmentId
     */
    public function __construct(Client $client, string $spaceId, string $environmentId)
    {
        $this->client = $client;
        $this->spaceId = $spaceId;
        $this->environmentId = $environmentId;
    }

    /**
     * {@inheritdoc}
     */
    protected function getSpaceId()
    {
        return $this->spaceId;
    }

    /**
     * {@inheritdoc}
     */
    protected function getEnvironmentId()
    {
        return $this->environmentId;
    }

    /**
     * Persist a new resource in Contentful.
     * This is a convenience method which just forwards to Client::create(),
     * but setting the `space` and `environment` keys to the current space and environment IDs in the parameters array.
     *
     * @param CreatableInterface         $resource
     * @param string                     $resourceId
     * @param ResourceInterface|string[] $parameters
     *
     * @see \Contentful\Management\Client::create()
     */
    public function create(CreatableInterface $resource, string $resourceId = '', $parameters = [])
    {
        if (\is_array($parameters)) {
            $parameters['space'] = $this->spaceId;
            $parameters['environment'] = $this->environmentId;
        }

        $this->client->create($resource, $resourceId, $parameters);
    }

    /**
     * Resolves a Contentful link scoped to the current space and environment.
     *
     * @param Link     $link
     * @param string[] $parameters
     *
     * @return ResourceInterface
     */
    public function resolveLink(Link $link, array $parameters = []): ResourceInterface
    {
        $parameters['space'] = $this->spaceId;
        $parameters['environment'] = $this->environmentId;

        return $this->client->resolveLink($link, $parameters);
    }
}