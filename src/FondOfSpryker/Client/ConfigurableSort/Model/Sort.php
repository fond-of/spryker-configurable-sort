<?php

namespace FondOfSpryker\Client\ConfigurableSort\Model;

use FondOfSpryker\Shared\ConfigurableSort\ConfigurableSortConstants;

class Sort implements SortInterface
{
    /**
     * @param array $requestParameters
     * @param string[] $possibleSortProperties
     *
     * @return string|null
     */
    public function getCurrentParam(array $requestParameters, array $possibleSortProperties): ?string
    {
        if (!isset($requestParameters[ConfigurableSortConstants::PARAMETER_SORT])) {
            return null;
        }

        $currentSortParam = $requestParameters[ConfigurableSortConstants::PARAMETER_SORT];
        $pattern = sprintf('/(%s)(_(asc|desc))?/', implode('|', $possibleSortProperties));

        if (!preg_match($pattern, $currentSortParam)) {
            return null;
        }

        return $currentSortParam;
    }
}
