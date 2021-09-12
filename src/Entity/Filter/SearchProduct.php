<?php

namespace App\Entity\Filter;

use App\Entity\Category;
use phpDocumentor\Reflection\Types\Float_;
use phpDocumentor\Reflection\Types\Mixed_;

class SearchProduct
{

    /**
     * @var Category
     */
    private $categories;

    /**
     * @var null|integer
     */
    private $maxPrice;

    /**
     * @var null|integer
     */
    private $minPrice;

    /**
     * @var null|string
     */
    private $name;

    /**
     * @var mixed
     */
    private $sortProduct;
    /**
     * @var boolean
     */
    private $isBest;


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
    public function getMaxPrice(): ?int
    {
        return $this->maxPrice;
    }

    /**
     * @param int|null $maxPrice
     */
    public function setMaxPrice(?int $maxPrice): void
    {
        $this->maxPrice = $maxPrice;
    }

    /**
     * @return int|null
     */
    public function getMinPrice(): ?int
    {
        return $this->minPrice;
    }

    /**
     * @param int|null $minPrice
     */
    public function setMinPrice(?int $minPrice): void
    {
        $this->minPrice = $minPrice;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getSortProduct()
    {
        return $this->sortProduct;
    }

    /**
     * @param mixed $sortProduct
     */
    public function setSortProduct($sortProduct): void
    {
        $this->sortProduct = $sortProduct;
    }

    /**
     * @return bool
     */
    public function getIsBest(): bool
    {
        return $this->isBest;
    }


    /**
     * @param bool $isBest
     */
    public function setIsBest(bool $isBest): void
    {
        $this->isBest = $isBest;
    }

}