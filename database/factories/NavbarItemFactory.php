<?php

namespace Database\Factories;

use App\Models\NavbarItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\NavbarItem>
 */
class NavbarItemFactory extends Factory
{
    public function definition(): array
    {
        return [
            'key' => fake()->unique()->slug(2),
            'label' => fake()->words(2, true),
            'url' => '/',
            'group' => NavbarItem::GROUP_MAIN,
            'sort_order' => 0,
            'active' => true,
        ];
    }
}
