<?php

namespace FondOfSpryker\Client\ConfigurableSort;

use FondOfSpryker\Client\ConfigurableSort\Model\Sort;
use FondOfSpryker\Client\ConfigurableSort\Model\SortInterface;
use Spryker\Client\Kernel\AbstractFactory;

class ConfigurableSortFactory extends AbstractFactory
{
    /**
     * @return \FondOfSpryker\Client\ConfigurableSort\Model\SortInterface
     */
    public function createSort(): SortInterface
    {
        return new Sort();
    }
}
