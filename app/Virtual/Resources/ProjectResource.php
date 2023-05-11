<?php

namespace App\Virtual\Resources;

use App\Virtual\Models\Project;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     title="ProjectResource",
 *     description="Project resource",
 *     @OA\Xml(
 *         name="ProjectResource"
 *     )
 * )
 */
class ProjectResource
{
    /**
     * @OA\Property(
     *     title="Data",
     *     description="Data wrapper"
     * )
     *
     * @var Project[]
     */
    private $data;
}
