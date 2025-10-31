<?php

namespace App\Jobs;

use App\Models\File;
use App\Services\UploadService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class UploadFileJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(private $file, private int $userId) {}

    /**
     * Execute the job.
     */
    public function handle()
    {
        $extension = $this->file->getClientOriginalExtension();
        $type = app(UploadService::class)->detectType($extension);

        $fileName = Str::uuid().'.'.$extension;

        $path = $this->file->storeAs($type, $fileName, 's3');

        $fileModel = File::create([
            'user_id' => $this->userId,
            'original_name' => $this->file->getClientOriginalName(),
            'path' => $path,
            'type' => $type,
            'status' => 'pending',
            'metadata' => [],
        ]);

        return $fileModel;
    }
}
