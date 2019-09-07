<?php

namespace App\Models;

/**
 * Class ApiResponse
 *
 * @package Models
 *
 * @OA\Schema(
 *     description="ManageApi response",
 *     title="ManageApi response"
 * )
 */
class ApiResponse
{
    /**
     * @OA\Property(
     *     description="Code",
     *     title="Code",
     *     format="int32"
     * )
     *
     * @var int
     */
    private $code;

    /**
     * @OA\Property(
     *     description="Message",
     *     title="Message",
     *     example="Success"
     * )
     *
     * @var string
     */
    private $message;
}