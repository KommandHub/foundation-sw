<?php

namespace Kommandhub\Foundation\Fixture;

use Shopware\FixtureBundle\FixtureInterface;
use Shopware\FixtureBundle\Attribute\Fixture;
use Shopware\FixtureBundle\Helper\Customer\CustomerFixtureDefinition;
use Shopware\FixtureBundle\Helper\Customer\CustomerFixtureLoader;

#[Fixture(groups: ['foundation'])]
class CustomerFixture implements FixtureInterface
{
    public function __construct(
        private readonly CustomerFixtureLoader $customerFixtureLoader
    ) {
    }

    public function load(): void
    {
        $this->customerFixtureLoader->apply(
            (new CustomerFixtureDefinition('customer@example.com'))
                ->firstName('Max')
                ->lastName('Mustermann')
                ->salutation('mr')
                ->password('password')
                ->defaultBillingAddress([
                    'firstName' => 'Max',
                    'lastName' => 'Mustermann',
                    'street' => 'Musterstraße 123',
                    'zipcode' => '12345',
                    'city' => 'Musterstadt',
                    'country' => 'DEU',
                    'company' => 'Musterfirma GmbH',
                    'phoneNumber' => '+49 123 456789',
                    'salutation' => 'mr',
                ])
                ->defaultShippingAddress([
                    'firstName' => 'Max',
                    'lastName' => 'Mustermann',
                    'street' => 'Lieferadresse 456',
                    'zipcode' => '67890',
                    'city' => 'Lieferstadt',
                    'country' => 'DEU',
                    'additionalAddressLine1' => 'Building B',
                    'additionalAddressLine2' => '3rd Floor',
                ])
                ->addAddress('work', [
                    'firstName' => 'Max',
                    'lastName' => 'Mustermann',
                    'street' => 'Office Street 789',
                    'zipcode' => '11111',
                    'city' => 'Business City',
                    'country' => 'DEU',
                    'company' => 'Work Corporation',
                ])
        );
    }
}
