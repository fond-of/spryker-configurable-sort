<?php

namespace FondOfSpryker\Client\ConfigurableSort;

use Codeception\Test\Unit;
use FondOfSpryker\Client\ConfigurableSort\Model\Sort;

class ConfigurableSortFactoryTest extends Unit
{
    /**
     * @var \FondOfSpryker\Client\ConfigurableSort\ConfigurableSortFactory
     */
    protected $configurableSortFactory;

    /**
     * @return void
     */
    protected function _before(): void
    {
        $this->configurableSortFactory = new ConfigurableSortFactory();
    }

    /**
     * @return void
     */
    public function testCreateSort(): void
    {
        static::assertInstanceOf(
            Sort::class,
            $this->configurableSortFactory
        );
    }
}
