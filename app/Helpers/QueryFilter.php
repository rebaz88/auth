<?php

namespace App\Helpers;

class QueryFilter {

    /**
     * Filter easui datagrid filter
     * @method filter
     * @param  [QueryBuilder] $query
     * @param  [Request] $request the request that contains filter
     * @param  array  $params  additional parameter to be passed for between operatoer in sql
     * @return [QueryBuilder]
     */
    public static function filter($query, $request, $betweenFields = [])
    {
      $filterRules = collect(($request->has('filterRules')) ? json_decode($request->filterRules) : []);

      list($filterRules, $query) = self::removeBetweenParams($betweenFields, $filterRules, $query);

      $filterRules->map(function($filterRule, $key) use ($query){
        $query->where($filterRule->field, 'like', '%'. $filterRule->value .'%');
      });

      return $query;

    }

    public static function removeBetweenParams($betweenFields, $filterRules, $query)
    {
      $betweenFields = collect($betweenFields);

      $remainingRules = $filterRules->filter(function($filterRule, $key) use ($betweenFields, $query){

        if($betweenFields->contains($filterRule->field)) {
          $from = toMysqlDate($filterRule->filter_from);
          $to = toMysqlDate($filterRule->filter_to);
          $query->whereBetween($filterRule->field, [$from, $to]);
        }

        return (!$betweenFields->contains($filterRule->field));

      });

      return [$remainingRules, $query];

    }

}
