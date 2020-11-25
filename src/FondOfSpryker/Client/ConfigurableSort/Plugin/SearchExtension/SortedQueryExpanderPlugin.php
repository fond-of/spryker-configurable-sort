<?php

namespace FondOfSpryker\Client\ConfigurableSort\Plugin\SearchExtension;

use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryExpanderPluginInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface;

/**
 * @method \FondOfSpryker\Client\ConfigurableSort\ConfigurableSortFactory getFactory()
 */
class SortedQueryExpanderPlugin extends AbstractPlugin implements QueryExpanderPluginInterface
{
    /**
     * @var string[]
     */
    protected $possibleSortProperties;

    /**
     * @param string[] $possibleSortProperties
     */
    public function __construct(array $possibleSortProperties = [])
    {
        $this->possibleSortProperties = $possibleSortProperties;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface $searchQuery
     * @param array $requestParameters
     *
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface
     */
    public function expandQuery(QueryInterface $searchQuery, array $requestParameters = []): QueryInterface
    {
        $currentSortParam = $this->getFactory()
            ->createSort()
            ->getCurrentParam($requestParameters, $this->possibleSortProperties);

        if ($currentSortParam === null) {
            return $searchQuery;
        }

        $currentSortParamParts = explode('_', $currentSortParam);

        if (count($currentSortParamParts) !== 2) {
            return $searchQuery;
        }

        $searchQuery->getSearchQuery()->addSort(
            [
                $currentSortParamParts[0] => [
                    'order' => $currentSortParamParts[1],
                ],
            ]
        );

        return $searchQuery;
    }
}
