<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Attachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AttachmentController extends Controller
{
    public function download(Request $request, int $attachmentId)
    {
        // findしてあれば取得、なければabort(404)を返す
        $attachment = Attachment::findOrFail($attachmentId);

        return Storage::download($attachment->path, $attachment->original_filename);
    }
}
