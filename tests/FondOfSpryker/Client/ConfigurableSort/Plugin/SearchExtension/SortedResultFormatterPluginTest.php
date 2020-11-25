<?php

namespace FondOfSpryker\Client\ConfigurableSort\Plugin\SearchExtension;

use Codeception\Test\Unit;
use Elastica\ResultSet;
use FondOfSpryker\Client\ConfigurableSort\ConfigurableSortFactory;
use FondOfSpryker\Client\ConfigurableSort\Model\SortInterface;
use FondOfSpryker\Shared\ConfigurableSort\ConfigurableSortConstants;

class SortedResultFormatterPluginTest extends Unit
{
    /**
     * @var string[]
     */
    protected $possibleSortProperties;

    /**
     * @var string[]
     */
    protected $requestParameters;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\FondOfSpryker\Client\ConfigurableSort\ConfigurableSortFactory
     */
    protected $configurableSortFactoryMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\FondOfSpryker\Client\ConfigurableSort\Model\SortInterface
     */
    protected $sortMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Elastica\ResultSet
     */
    protected $resultSetMock;

    /**
     * @var \FondOfSpryker\Client\ConfigurableSort\Plugin\SearchExtension\SortedResultFormatterPlugin
     */
    protected $sortedResultFormatterPlugin;

    /**
     * @return void
     */
    protected function _before(): void
    {
        $this->possibleSortProperties = ['foo'];
        $this->requestParameters = [
            ConfigurableSortConstants::PARAMETER_SORT => 'foo_asc',
        ];

        $this->configurableSortFactoryMock = $this->getMockBuilder(ConfigurableSortFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->sortMock = $this->getMockBuilder(SortInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->resultSetMock = $this->getMockBuilder(ResultSet::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->sortedResultFormatterPlugin = new SortedResultFormatterPlugin(
            $this->possibleSortProperties
        );
        $this->sortedResultFormatterPlugin->setFactory($this->configurableSortFactoryMock);
    }

    /**
     * @return void
     */
    public function testGetName(): void
    {
        static::assertEquals(
            SortedResultFormatterPlugin::NAME,
            $this->sortedResultFormatterPlugin->getName()
        );
    }

    /**
     * @return void
     */
    public function testFormatSearchResult(): void
    {
        $this->configurableSortFactoryMock->expects(static::atLeastOnce())
            ->method('createSort')
            ->willReturn($this->sortMock);

        $this->sortMock->expects(static::atLeastOnce())
            ->method('getCurrentParam')
            ->with($this->requestParameters, $this->possibleSortProperties)
            ->willReturn('foo_asc');

        $sortSearchResultTransfer = $this->sortedResultFormatterPlugin
            ->formatResult($this->resultSetMock, $this->requestParameters);

        static::assertEquals(
            'foo_asc',
            $sortSearchResultTransfer->getCurrentSortParam()
        );

        static::assertEquals(
            'asc',
            $sortSearchResultTransfer->getCurrentSortOrder()
        );

        static::assertEquals(
            ['foo_asc', 'foo_desc'],
            $sortSearchResultTransfer->getSortParamNames()
        );
    }
}
