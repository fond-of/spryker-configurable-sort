<?php

namespace FondOfSpryker\Client\ConfigurableSort\Model;

use Codeception\Test\Unit;
use FondOfSpryker\Shared\ConfigurableSort\ConfigurableSortConstants;

class SortTest extends Unit
{
    /**
     * @var \FondOfSpryker\Client\ConfigurableSort\Model\SortInterface
     */
    protected $sort;

    /**
     * @return void
     */
    protected function _before(): void
    {
        $this->sort = new Sort();
    }

    /**
     * @return void
     */
    public function testGetCurrentParam(): void
    {
        $requestParameters = [
            ConfigurableSortConstants::PARAMETER_SORT => 'bar_asc',
        ];

        $possibleSortProperties = ['bar'];

        static::assertEquals(
            'bar_asc',
            $this->sort->getCurrentParam($requestParameters, $possibleSortProperties)
        );
    }

    /**
     * @return void
     */
    public function testGetCurrentParamWithEmptyRequestParameters(): void
    {
        $requestParameters = [];

        $possibleSortProperties = ['bar'];

        static::assertEquals(
            null,
            $this->sort->getCurrentParam($requestParameters, $possibleSortProperties)
        );
    }

    /**
     * @return void
     */
    public function testGetCurrentParamWithInvalidRequestParameters(): void
    {
        $requestParameters = [
            ConfigurableSortConstants::PARAMETER_SORT => 'foo_asc',
        ];

        $possibleSortProperties = ['bar'];

        static::assertEquals(
            null,
            $this->sort->getCurrentParam($requestParameters, $possibleSortProperties)
        );
    }
}
