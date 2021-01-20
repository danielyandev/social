<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *      @OA\Xml(name="UserResource"),
 *      @OA\Property(
 *          property="data",
 *          ref="#/components/schemas/User"
 *      )
 * )
 *
 * Class UserResource
 */
class UserResource extends JsonResource
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
