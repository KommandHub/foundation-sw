<?php

declare(strict_types=1);

namespace Kommandhub\Foundation\Tests\Fixture\Helper\Country;

class CountryFixtureDefinition
{
    private ?string $id = null;

    private string $name;

    private string $iso;

    private ?string $iso3 = null;

    private bool $active = true;

    private bool $shippingAvailable = true;

    private ?int $position = null;

    private bool $taxFree = false;

    private bool $checkVatIdPattern = false;

    private ?string $vatIdPattern = null;

    private bool $vatIdRequired = false;

    private bool $forceStateInRegistration = false;

    private bool $postalCodeRequired = false;

    private bool $checkPostalCodePattern = false;

    private ?string $checkAdvancedPostalCodePattern = null;

    private ?string $defaultPostalCodePattern = null;

    private bool $displayStateInRegistration = false;

    public function __construct(string $name, string $iso)
    {
        $this->name = $name;
        $this->iso = $iso;
    }

    public function id(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function iso3(string $iso3): self
    {
        $this->iso3 = $iso3;

        return $this;
    }

    public function active(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function shippingAvailable(bool $shippingAvailable): self
    {
        $this->shippingAvailable = $shippingAvailable;

        return $this;
    }

    public function position(int $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function vatIdPattern(string $vatIdPattern): self
    {
        $this->vatIdPattern = $vatIdPattern;

        return $this;
    }

    public function checkAdvancedPostalCodePattern(string $checkAdvancedPostalCodePattern): self
    {
        $this->checkAdvancedPostalCodePattern = $checkAdvancedPostalCodePattern;

        return $this;
    }

    public function defaultPostalCodePattern(string $defaultPostalCodePattern): self
    {
        $this->defaultPostalCodePattern = $defaultPostalCodePattern;

        return $this;
    }

    public function taxFree(bool $taxFree): self
    {
        $this->taxFree = $taxFree;

        return $this;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $data = [
            'name' => $this->name,
            'iso' => $this->iso,
            'active' => $this->active,
            'shippingAvailable' => $this->shippingAvailable,
            'taxFree' => $this->taxFree,
            'checkVatIdPattern' => $this->checkVatIdPattern,
            'vatIdRequired' => $this->vatIdRequired,
            'forceStateInRegistration' => $this->forceStateInRegistration,
            'postalCodeRequired' => $this->postalCodeRequired,
            'checkPostalCodePattern' => $this->checkPostalCodePattern,
            'displayStateInRegistration' => $this->displayStateInRegistration,
        ];

        if ($this->id !== null) {
            $data['id'] = $this->id;
        }

        if ($this->iso3 !== null) {
            $data['iso3'] = $this->iso3;
        }

        if ($this->position !== null) {
            $data['position'] = $this->position;
        }

        if ($this->vatIdPattern !== null) {
            $data['vatIdPattern'] = $this->vatIdPattern;
        }

        if ($this->checkAdvancedPostalCodePattern !== null) {
            $data['checkAdvancedPostalCodePattern'] = $this->checkAdvancedPostalCodePattern;
        }

        if ($this->defaultPostalCodePattern !== null) {
            $data['defaultPostalCodePattern'] = $this->defaultPostalCodePattern;
        }

        return $data;
    }
}
