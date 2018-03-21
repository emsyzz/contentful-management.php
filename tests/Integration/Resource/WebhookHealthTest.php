<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2018 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Tests\Management\Integration\Resource;

use Contentful\Management\Resource\WebhookHealth;
use Contentful\Management\ResourceBuilder;
use Contentful\Tests\Management\BaseTestCase;

class WebhookHealthTest extends BaseTestCase
{
    /**
     * @expectedException \Error
     */
    public function testInvalidCreation()
    {
        new WebhookHealth();
    }

    /**
     * This test is meaningless.
     * It's only here because private, empty contructors are not correctly picked by code coverage.
     */
    public function testConstructor()
    {
        $class = new \ReflectionClass(WebhookHealth::class);
        $object = $class->newInstanceWithoutConstructor();
        $constructor = $class->getConstructor();
        $constructor->setAccessible(true);
        $constructor->invoke($object);

        $this->markTestAsPassed();
    }

    /**
     * @return WebhookHealth
     */
    public function testJsonSerialize(): WebhookHealth
    {
        $webhookHealth = (new ResourceBuilder())->build([
            'sys' => [
                'type' => 'Webhook',
            ],
            'calls' => [
                'total' => 233,
                'healthy' => 102,
                ],
            ]
        );

        $this->assertJsonFixtureEqualsJsonObject('Integration/Resource/webhook_health.json', $webhookHealth);

        return $webhookHealth;
    }

    /**
     * @param WebhookHealth $webhookHealth
     *
     * @depends testJsonSerialize
     * @expectedException \LogicException
     * @expectedExceptionMessage Trying to update resource object in mapper of type "Contentful\Management\Mapper\WebhookHealth", but only creation from scratch is supported.
     */
    public function testInvalidUpdate(WebhookHealth $webhookHealth)
    {
        (new ResourceBuilder())
            ->build(['sys' => [
                'type' => 'Webhook',
            ]], $webhookHealth);
    }

    /**
     * @param WebhookHealth $webhookHealth
     *
     * @depends testJsonSerialize
     * @expectedException \LogicException
     * @expectedExceptionMessage Trying to convert object of class "Contentful\Management\Resource\WebhookHealth" to a request body format, but operation is not supported on this class.
     */
    public function testInvalidConversionToRequestBody(WebhookHealth $webhookHealth)
    {
        $webhookHealth->asRequestBody();
    }
}
