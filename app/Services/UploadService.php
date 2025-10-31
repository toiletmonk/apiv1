<?php

namespace App\Services;

use App\Models\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class UploadService
{
    public function __construct(private $file) {}

    public function upload(UploadedFile $file, int $userId)
    {
        $extension = strtolower($this->file->getClientOriginalExtension());
        $type = $this->detectType($extension);

        $fileName = Str::uuid().'.'.$extension;

        $path = $this->file->storeAs($type, $fileName, 's3');

        $fileModel = File::create([
            'user_id' => $userId,
            'original_name' => $file->getClientOriginalName(),
            'path' => $path,
            'type' => $type,
            'status' => 'pending',
            'metadata' => [],
        ]);

        return $fileModel;
    }

    public function detectType(string $extension)
    {
        $documentExtensions = ['doc', 'docx', 'pdf', 'xls', 'xlsx', 'ppt', 'pptx'];

        if (in_array($extension, $documentExtensions)) {
            return 'document';
        }

        throw new \Exception('Unsupported type: '.$extension);
    }
}
