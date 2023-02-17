<?php

namespace App\Factory;

use App\Entity\Categories;
use App\Repository\CategoriesRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Categories>
 *
 * @method        Categories|Proxy create(array|callable $attributes = [])
 * @method static Categories|Proxy createOne(array $attributes = [])
 * @method static Categories|Proxy find(object|array|mixed $criteria)
 * @method static Categories|Proxy findOrCreate(array $attributes)
 * @method static Categories|Proxy first(string $sortedField = 'id')
 * @method static Categories|Proxy last(string $sortedField = 'id')
 * @method static Categories|Proxy random(array $attributes = [])
 * @method static Categories|Proxy randomOrCreate(array $attributes = [])
 * @method static CategoriesRepository|RepositoryProxy repository()
 * @method static Categories[]|Proxy[] all()
 * @method static Categories[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Categories[]|Proxy[] createSequence(array|callable $sequence)
 * @method static Categories[]|Proxy[] findBy(array $attributes)
 * @method static Categories[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static Categories[]|Proxy[] randomSet(int $number, array $attributes = [])
 */
final class CategoriesFactory extends ModelFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function getDefaults(): array
    {
        return [
            'category_id' => self::faker()->randomNumber(),
            'date_added' => self::faker()->dateTime(),
            'date_modified' => self::faker()->dateTime(),
            'eshop_status' => self::faker()->numberBetween(1, 32767),
            'order_sort' => self::faker()->randomNumber(),
            'parent_id' => self::faker()->randomNumber(),
            'status' => self::faker()->numberBetween(1, 32767),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Categories $categories): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Categories::class;
    }
}
