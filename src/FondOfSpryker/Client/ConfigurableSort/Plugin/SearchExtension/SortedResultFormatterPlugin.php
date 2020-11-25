<?php

namespace FondOfSpryker\Client\ConfigurableSort\Plugin\SearchExtension;

use Elastica\ResultSet;
use Generated\Shared\Transfer\SortSearchResultTransfer;
use Spryker\Client\SearchElasticsearch\Plugin\ResultFormatter\AbstractElasticsearchResultFormatterPlugin;

/**
 * @method \FondOfSpryker\Client\ConfigurableSort\ConfigurableSortFactory getFactory()
 */
class SortedResultFormatterPlugin extends AbstractElasticsearchResultFormatterPlugin
{
    public const NAME = 'sort';

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
     * @return string
     */
    public function getName(): string
    {
        return static::NAME;
    }

    /**
     * @param \Elastica\ResultSet $searchResult
     * @param array $requestParameters
     *
     * @return mixed
     */
    protected function formatSearchResult(ResultSet $searchResult, array $requestParameters)
    {
        $currentSortOrder = null;
        $currentSortParam = $this->getFactory()
            ->createSort()
            ->getCurrentParam($requestParameters, $this->possibleSortProperties);

        if ($currentSortParam !== null) {
            $currentSortParamParts = explode('_', $currentSortParam);
            $currentSortOrder = count($currentSortParamParts) === 2 ? $currentSortParamParts[1] : null;
        }

        return (new SortSearchResultTransfer())
            ->setSortParamNames($this->getSortParamNames())
            ->setCurrentSortParam($currentSortParam)
            ->setCurrentSortOrder($currentSortOrder);
    }

    /**
     * @return string[]
     */
    protected function getSortParamNames(): array
    {
        $sortParamNames = [];

        foreach ($this->possibleSortProperties as $possibleSortProperty) {
            $sortParamNames[] = sprintf('%s_asc', $possibleSortProperty);
            $sortParamNames[] = sprintf('%s_desc', $possibleSortProperty);
        }

        return $sortParamNames;
    }
}
