<?php

declare(strict_types=1);

namespace Rinvex\Addresses\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Jackpopp\GeoDistance\GeoDistanceTrait;
use Rinvex\Support\Traits\ValidatingTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Rinvex\Addresses\Models\Address.
 *
 * @property int                 $id
 * @property int                 $addressable_id
 * @property string              $addressable_type
 * @property string              $address_field
 * @property string              $label
 * @property string              $first_name
 * @property string              $last_name
 * @property string              $organization
 * @property string              $country_code
 * @property string              $street
 * @property string              $full_street
 * @property string              $state
 * @property string              $city
 * @property string              $postal_code
 * @property float               $latitude
 * @property float               $longitude
 * @property bool                $is_primary
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $addressable
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Addresses\Models\Address inCountry($countryCode)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Addresses\Models\Address inLanguage($languageCode)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Addresses\Models\Address isPrimary()
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Addresses\Models\Address outside($distance, $measurement = null, $latitude = null, $longitude = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Addresses\Models\Address whereAddressableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Addresses\Models\Address whereAddressableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Addresses\Models\Address whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Addresses\Models\Address whereCountryCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Addresses\Models\Address whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Addresses\Models\Address whereFamilyName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Addresses\Models\Address whereGivenName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Addresses\Models\Address whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Addresses\Models\Address whereIsBilling($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Addresses\Models\Address whereIsPrimary($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Addresses\Models\Address whereIsShipping($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Addresses\Models\Address whereLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Addresses\Models\Address whereLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Addresses\Models\Address whereLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Addresses\Models\Address whereOrganization($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Addresses\Models\Address wherePostalCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Addresses\Models\Address whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Addresses\Models\Address whereStreet($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Addresses\Models\Address whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Addresses\Models\Address within($distance, $measurement = null, $latitude = null, $longitude = null)
 * @mixin \Eloquent
 */
class Address extends Model
{
    use HasFactory;
    use SoftDeletes;
    use ValidatingTrait;
    use GeoDistanceTrait;

    /**
     * {@inheritdoc}
     */
    protected $fillable = [
        'addressable_id',
        'addressable_type',
        'label',
        'first_name',
        'last_name',
        'organization',
        'country_code',
        'street',
        'address_supplement',
        'state',
        'city',
        'postal_code',
        'latitude',
        'longitude',
        'is_primary',
        'type',
    ];

    /**
     * {@inheritdoc}
     */
    protected $casts = [
        'addressable_id' => 'integer',
        'addressable_type' => 'string',
        'label' => 'string',
        'first_name' => 'string',
        'last_name' => 'string',
        'organization' => 'string',
        'country_code' => 'string',
        'street' => 'string',
        'address_supplement' => 'string',
        'state' => 'string',
        'city' => 'string',
        'postal_code' => 'string',
        'latitude' => 'float',
        'longitude' => 'float',
        'is_primary' => 'boolean',
        'deleted_at' => 'datetime',
    ];

    /**
     * {@inheritdoc}
     */
    protected $observables = [
        'validating',
        'validated',
    ];

    /**
     * The default rules that the model will validate against.
     *
     * @var array
     */
    protected $rules = [];

    /**
     * Whether the model should throw a
     * ValidationException if it fails validation.
     *
     * @var bool
     */
    protected $throwValidationExceptions = true;

    /**
     * Create a new Eloquent model instance.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->setTable(config('rinvex.addresses.tables.addresses'));
        $this->mergeRules([
            'addressable_id' => 'required|integer',
            'addressable_type' => 'required|string|strip_tags|max:150',
            'label' => 'nullable|string|strip_tags|max:150',
            'first_name' => 'required|string|strip_tags|max:150',
            'last_name' => 'nullable|string|strip_tags|max:150',
            'organization' => 'nullable|string|strip_tags|max:150',
            'country_code' => 'nullable|alpha|size:2|country',
            'street' => 'nullable|string|strip_tags|max:150',
            'address_supplement' => 'nullable|string|strip_tags|max:150',
            'state' => 'nullable|string|strip_tags|max:150',
            'city' => 'nullable|string|strip_tags|max:150',
            'postal_code' => 'nullable|string|strip_tags|max:150',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'is_primary' => 'sometimes|boolean',
        ]);
        $this->latColumn = 'latitude';
        $this->lngColumn = 'longitude';

        parent::__construct($attributes);
    }

    /**
     * Get the owner model of the address.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function addressable(): MorphTo
    {
        return $this->morphTo('addressable', 'addressable_type', 'addressable_id', 'id');
    }

    /**
     * Scope primary addresses.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeIsPrimary(Builder $builder): Builder
    {
        return $builder->where('is_primary', true);
    }

    /**
     * Scope addresses by the given country.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param string                                $countryCode
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeInCountry(Builder $builder, string $countryCode): Builder
    {
        return $builder->where('country_code', $countryCode);
    }

    /**
     * Scope addresses by the given language.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param string                                $languageCode
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeInLanguage(Builder $builder, string $languageCode): Builder
    {
        return $builder->where('language_code', $languageCode);
    }

    public function getFullStreetAttribute(): string
    {
        return implode(' ', [$this->street, $this->street_number]);
    }

    public function getAddressFieldAttribute(): string
    {
        return collect([
            $this->full_name,
            $this->department,
            $this->street." ".$this->street_number,
            $this->address_supplement,
            "{$this->postal_code} {$this->city}",
            is_string(country($this->country_code)) ? country($this->country_code) : null,
        ])
            // set all items which consists of spaces only to empty string
            ->map(function(?string $item) {
                if($item === null) {
                    return null;
                }
                return trim($item);
            })
            // remove all empty / null items from collection
            ->reject(function(?string $item) {
                return empty($item);
            })
            ->implode("\n");
    }

    /**
     * {@inheritdoc}
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function (self $address) {
            $geocoding = config('rinvex.addresses.geocoding.enabled');
            $geocoding_api_key = config('rinvex.addresses.geocoding.api_key');
            if ($geocoding && $geocoding_api_key) {
                $segments[] = $address->street;
                $segments[] = sprintf('%s, %s %s', $address->city, $address->state, $address->postal_code);
                $segments[] = country($address->country_code)->getName();

                $query = str_replace(' ', '+', implode(', ', $segments));
                $geocode = json_decode(file_get_contents(
                    "https://maps.google.com/maps/api/geocode/json?address={$query}&sensor=false&key={$geocoding_api_key}"
                ));

                if (count($geocode->results)) {
                    $address->latitude = $geocode->results[0]->geometry->location->lat;
                    $address->longitude = $geocode->results[0]->geometry->location->lng;
                }
            }
        });
    }
}
