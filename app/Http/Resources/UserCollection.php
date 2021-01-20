<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

/**
 * @OA\Schema(
 *      @OA\Xml(name="UserCollection"),
 *      @OA\Property(
 *          property="data",
 *          type="array",
 *          @OA\Items(ref="#/components/schemas/User")
 *      ),
 *      @OA\Property(
 *          property="links",
 *          type="object",
 *          example={
 *              "first": "first page link",
 *              "last": "last page link",
 *              "next": "next page link",
 *              "prev": "prev page link"
 *          }),
 *      @OA\Property(
 *          property="meta",
 *          type="object",
 *          example={
 *              "current_page":1,
 *              "from":1,
 *              "last_page":1,
 *              "path":"full url path",
 *              "per_page":10,
 *              "to":2,
 *              "total":2
 *          })
 * )
 *
 * Class UserCollection
 */
class UserCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
