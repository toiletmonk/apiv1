<?php

namespace App\Http\Controllers;

use App\Http\Requests\UploadRequest;
use App\Models\File;
use App\Models\User;
use App\Services\UploadService;

class UploadController extends Controller
{
    protected UploadService $service;

    public function __construct(UploadService $service)
    {
        $this->service = $service;
    }

    public function upload(UploadRequest $request, User $user)
    {
        $file = $request->file('file');
        $this->service->upload($file, $user->id);

        return response()->json(['message' => 'File uploaded successfully.']);
    }

    public function delete(File $file)
    {
        $user = auth()->user();

        if ($file->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        $file->delete();

        return response()->json(['message' => 'File deleted successfully.']);
    }
}
