<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *      @OA\Xml(name="RelationshipResource"),
 *      @OA\Property(
 *          property="data",
 *          ref="#/components/schemas/Relationship"
 *      )
 * )
 *
 * Class RelationshipResource
 */
class RelationshipResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
