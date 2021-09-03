<?php

namespace App\Entity\Filter;

use App\Entity\Category;

class SearchProduct
{

    /**
     * @var Category
     */
    private $categories;

    /**
     * @var null|integer
     */
    private $price;

    /**
     * @return Category
     */
    public function getCategories(): ?Category
    {
        return $this->categories;
    }

    /**
     * @param Category $categories
     */
    public function setCategories(?Category $categories): void
    {
        $this->categories = $categories;
    }

    /**
     * @return int|null
     */
    public function getPrice(): ?int
    {
        return $this->price;
    }

    /**
     * @param int|null $price
     */
    public function setPrice(?int $price): void
    {
        $this->price = $price;
    }

}