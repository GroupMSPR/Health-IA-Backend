<?php

namespace App\Rest\Resources;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Lomkit\Rest\Http\Requests\RestRequest;
use Lomkit\Rest\Http\Resource as RestResource;

abstract class Resource extends RestResource
{
    /**
     * Build a "search" query for fetching resource.
     *
     * @param RestRequest $request
     * @param Builder $query
     * @return Builder
     */
    public function searchQuery(RestRequest $request, Builder $query): Builder
    {
        return parent::searchQuery($request, $query)->controlled();
    }

    /**
     * Build a query for mutating resource.
     *
     * @param RestRequest $request
     * @param Builder $query
     * @return Builder
     */
    public function mutateQuery(RestRequest $request, Builder $query): Builder
    {
        return parent::mutateQuery($request, $query)->controlled();
    }

    /**
     * Build a "destroy" query for the given resource.
     *
     * @param RestRequest $request
     * @param Builder $query
     * @return Builder
     */
    public function destroyQuery(RestRequest $request, Builder $query): Builder
    {
        return parent::destroyQuery($request, $query)->controlled();
    }

    /**
     * Build a "restore" query for the given resource.
     *
     * @param RestRequest $request
     * @param Builder $query
     * @return Builder
     */
    public function restoreQuery(RestRequest $request, Builder $query): Builder
    {
        return parent::restoreQuery($request, $query)->controlled();
    }

    /**
     * Build a "forceDelete" query for the given resource.
     *
     * @param RestRequest $request
     * @param Builder $query
     * @return Builder
     */
    public function forceDeleteQuery(RestRequest $request, Builder $query): Builder
    {
        return parent::forceDeleteQuery($request, $query)->controlled();
    }
}
