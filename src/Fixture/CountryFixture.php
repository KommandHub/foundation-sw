<?php

declare(strict_types=1);

namespace Kommandhub\Foundation\Fixture;

use Kommandhub\Foundation\Fixture\Helper\Country\CountryFixtureDefinition;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\FixtureBundle\FixtureInterface;
use Shopware\FixtureBundle\Attribute\Fixture;

#[Fixture(groups: ['foundation'])]
class CountryFixture implements FixtureInterface
{
    public function __construct(
        private readonly EntityRepository $countryRepository
    ) {
    }

    public function load(): void
    {
        $countries = [
            (new CountryFixtureDefinition('Germany', 'DE'))->id('5c8ee7838501460f85c1630c904df62e')->iso3('DEU')->toArray(),
            (new CountryFixtureDefinition('United Kingdom', 'GB'))->id('9f96a326260241e78051756d108d4b2d')->iso3('GBR')->toArray(),
            (new CountryFixtureDefinition('USA', 'US'))->id('3488f553641b4e0ca238a8e32d56ed75')->iso3('USA')->toArray(),
        ];

        $this->countryRepository->upsert(
            $countries,
            Context::createDefaultContext()
        );
    }
}
