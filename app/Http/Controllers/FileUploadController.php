<?php

namespace App\Http\Controllers;

use App\Models\ArticleImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

use function PHPUnit\Framework\directoryExists;

class FileUploadController extends Controller
{
    public function process(Request $request): string
    {
        $files = $request->allFiles();

        if (empty($files)) {
            abort(422, 'No files were uploaded.');
        }

        if (count($files) > 1) {
            abort(422, 'Only 1 file can be uploaded at a time.');
        }

        $requestKey = array_key_first($files);

        $file = is_array($request->input($requestKey))
            ? $request->file($requestKey)[0]
            : $request->file($requestKey);

        $extension = $file->getClientOriginalExtension();

        if (!in_array($extension, ['jpg', 'jpeg', 'png'])) {
            abort(422, 'Only jpg, jpeg or png images can be uploaded.');
        }

        $id = uniqid('', true);

        Storage::disk('tmp')->put($id, $file);

        return $id;
    }

    public function restore(string $id)
    {
        Log::info('File ' . $id . ' restored.');

        $dir = public_path('images/tmp/' . $id);

        if (directoryExists($dir)) {

            $files = File::allFiles($dir);
            $file = $files[0];
            $filePath = $file->getPathname();
            $name = $file->getFilename();

            return response()->file($filePath, [
                'Content-Type' => 'image/jpg',
                'Content-Disposition' => 'inline; filename="' . $name . '"',
            ]);
        } else {
            return response()->json(['error' => 'File not found'], 404);
        }
    }

    public function load(string $file_name)
    {
        $filePath = public_path('images/articles/' . $file_name);

        if (file_exists($filePath)) {
            return response()->file($filePath, [
                'Content-Type' => 'image/jpg',
                'Content-Disposition' => 'inline; filename="' . $file_name,
            ]);
        } else {
            return response()->json(['error' => 'File not found'], 404);
        }
    }

    public function delete(Request $request)
    {
        $id = $request->getContent();

        $dir = public_path('images/tmp/' . $id);
        $files = File::allFiles($dir);
        $file = $files[0];
        $filePath = $file->getPathname();

        unlink($filePath);
        rmdir(public_path('images/tmp/' . $id));

        return response(
            [
                'message' => 'File ' . $id . ' deleted.',
            ],
            200
        );
    }

    public function remove(Request $request)
    {
        request()->validate([
            'id' => 'required|string',
        ]);

        $id = $request->id;

        $path = public_path('images/articles/' . $id . '.jpg');

        File::delete($path);

        ArticleImage::where('file_name', $id . '.jpg')->delete();

        Log::info('File ' . $id . ' removed.');

        return response(
            [
                'message' => 'File ' . $id . ' removed.',
            ],
            200
        );
    }
}
