<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Http\Controllers\ThumbnailController;
use Illuminate\Support\Facades\Log;

class ThumbnailJob implements ShouldQueue
{
    use Queueable;
    protected $type;
    protected $id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($type, $id)
    {
        $this->type = $type;
        $this->id = $id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $typeToFunctionMap = [
            "hat" => [ThumbnailController::class, "renderHat"],
            "head" => [ThumbnailController::class, "renderHead"],
            "gear" => [ThumbnailController::class, "renderGear"],
            "model" => [ThumbnailController::class, "renderModel"],
            "shirt" => [ThumbnailController::class, "renderShirt"],
            "pants" => [ThumbnailController::class, "renderPants"],
            "tshirt" => [ThumbnailController::class, "renderTShirt"],
            "place" => [ThumbnailController::class, "renderThumbnail"],
            "user" => [ThumbnailController::class, "renderUser"],
            "mesh" => [ThumbnailController::class, "renderMesh"],
        ];

        if (array_key_exists($this->type, $typeToFunctionMap)) {
            call_user_func($typeToFunctionMap[$this->type], $this->id);
        } else {
            Log::warning("Thumbnail generation skipped: Unsupported type '{$this->type}' for asset ID {$this->id}");
        }
    }
}
