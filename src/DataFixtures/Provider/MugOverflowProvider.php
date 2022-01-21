<?php

namespace App\DataFixtures\Provider;

class MugOverflowProvider
{
    // Taleau de titre de mug
    private $products = [
        'Keep calm & var dump',
        'Titanic',
        'Classique O\'Clock',
        'Mika MVC',
        'Xandar',
        'Mario',
        'Deploy Now',
        'Je suis content, je commit',
        'Debugging',
        'Designer+Developer, You are a unicorn'
    ];

    // Tableau de category
    private $categories = [
        'Blague de dev',
        'Promo',
        'Troll',
        'PHP',
        'JavaScript',
        'WordPress'
    ];

    // Tableau de promos
    private $promos = [
        'Wonderland',
        'Xandar',
        'Valhalla',
        'Uther'
    ];

    /**
     * Retourne une promo au hasard
     */
    public function promoTitle()
    {
        return $this->promos[array_rand($this->promos)];
    }

    /**
     * Retourne une catégorie au hasard
     */
    public function productCategory()
    {
        return $this->categories[array_rand($this->categories)];
    }

    /**
     * Retourne un titre de mug au hasard
     */
    public function mugTitle()
    {
        return $this->products[array_rand($this->products)];
    }
}
