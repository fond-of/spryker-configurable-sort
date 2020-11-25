<?php

namespace FondOfSpryker\Client\ConfigurableSort\Plugin\SearchExtension;

use Codeception\Test\Unit;
use Elastica\Query;
use FondOfSpryker\Client\ConfigurableSort\ConfigurableSortFactory;
use FondOfSpryker\Client\ConfigurableSort\Model\SortInterface;
use FondOfSpryker\Shared\ConfigurableSort\ConfigurableSortConstants;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface;

class SortedQueryExpanderPluginTest extends Unit
{
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\FondOfSpryker\Client\ConfigurableSort\ConfigurableSortFactory
     */
    protected $configurableSortFactoryMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\FondOfSpryker\Client\ConfigurableSort\Model\SortInterface
     */
    protected $sortMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Elastica\Query
     */
    protected $queryMock;

    /**
     * @var string[]
     */
    protected $possibleSortProperties;

    /**
     * @var array
     */
    protected $requestParameters;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface
     */
    protected $searchQueryMock;

    /**
     * @var \FondOfSpryker\Client\ConfigurableSort\Plugin\SearchExtension\SortedQueryExpanderPlugin
     */
    protected $sortedQueryExpanderPlugin;

    /**
     * @return void
     */
    protected function _before(): void
    {
        $this->configurableSortFactoryMock = $this->getMockBuilder(ConfigurableSortFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->sortMock = $this->getMockBuilder(SortInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->searchQueryMock = $this->getMockBuilder(QueryInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->queryMock = $this->getMockBuilder(Query::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->possibleSortProperties = ['foo'];
        $this->requestParameters = [
            ConfigurableSortConstants::PARAMETER_SORT => 'foo_asc',
        ];

        $this->sortedQueryExpanderPlugin = new SortedQueryExpanderPlugin($this->possibleSortProperties);
        $this->sortedQueryExpanderPlugin->setFactory($this->configurableSortFactoryMock);
    }

    /**
     * @return void
     */
    public function testExpandQueryWithSortDirection(): void
    {
        $this->configurableSortFactoryMock->expects(static::atLeastOnce())
            ->method('createSort')
            ->willReturn($this->sortMock);

        $this->sortMock->expects(static::atLeastOnce())
            ->method('getCurrentParam')
            ->with($this->requestParameters, $this->possibleSortProperties)
            ->willReturn('foo');

        $this->searchQueryMock->expects(static::never())
            ->method('getSearchQuery');

        $this->queryMock->expects(static::never())
            ->method('addSort');

        static::assertEquals(
            $this->searchQueryMock,
            $this->sortedQueryExpanderPlugin->expandQuery($this->searchQueryMock, $this->requestParameters)
        );
    }

    /**
     * @return void
     */
    public function testExpandQueryWithInvalidRequestParameters(): void
    {
        $this->configurableSortFactoryMock->expects(static::atLeastOnce())
            ->method('createSort')
            ->willReturn($this->sortMock);

        $this->sortMock->expects(static::atLeastOnce())
            ->method('getCurrentParam')
            ->with($this->requestParameters, $this->possibleSortProperties)
            ->willReturn(null);

        $this->searchQueryMock->expects(static::never())
            ->method('getSearchQuery');

        $this->queryMock->expects(static::never())
            ->method('addSort');

        static::assertEquals(
            $this->searchQueryMock,
            $this->sortedQueryExpanderPlugin->expandQuery($this->searchQueryMock, $this->requestParameters)
        );
    }

    /**
     * @return void
     */
    public function testExpandQuery(): void
    {
        $this->configurableSortFactoryMock->expects(static::atLeastOnce())
            ->method('createSort')
            ->willReturn($this->sortMock);

        $this->sortMock->expects(static::atLeastOnce())
            ->method('getCurrentParam')
            ->with($this->requestParameters, $this->possibleSortProperties)
            ->willReturn('foo_asc');

        $this->searchQueryMock->expects(static::atLeastOnce())
            ->method('getSearchQuery')
            ->willReturn($this->queryMock);

        $this->queryMock->expects(static::atLeastOnce())
            ->method('addSort')
            ->with(static::callback(static function (array $sort) {
                return isset($sort['foo']['order'])
                    && $sort['foo']['order'] === 'asc';
            }));

        static::assertEquals(
            $this->searchQueryMock,
            $this->sortedQueryExpanderPlugin->expandQuery($this->searchQueryMock, $this->requestParameters)
        );
    }
}
