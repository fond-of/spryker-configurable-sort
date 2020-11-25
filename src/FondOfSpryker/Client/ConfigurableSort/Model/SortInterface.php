<?php

namespace FondOfSpryker\Client\ConfigurableSort\Model;

interface SortInterface
{
    /**
     * @param array $requestParameters
     * @param string[] $possibleSortProperties
     *
     * @return string|null
     */
    public function getCurrentParam(array $requestParameters, array $possibleSortProperties): ?string;
}
