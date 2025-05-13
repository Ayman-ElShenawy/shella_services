<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ServiceInformation;
use App\Models\Video;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class VideoController extends Controller
{
    public function store(Request $request, $id)
    {
        try {
            $request->validate([
                "video" => 'required|mimes:mp4,ogg,webm,3gp,mkv|max:100000',
            ]);

            $filePath = '';
            $entity = ServiceInformation::find($id);
            if (!$entity) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Service information not found',
                ], 404);
            }
            $filePath = 'Video/';
            $file = $request->file('video');
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path($filePath), $fileName);

            $videoPath = $filePath . $fileName;
            Video::create([
                'video' => url($videoPath),
                's_information_id' => $entity->id,
                'user_id' => Auth::user()->id
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Video has been uploaded successfully',
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        } catch (ValidationException $validationException) {
            return response()->json([
                'status' => 'error',
                'message' => $validationException->getMessage(),
            ], 422);
        }
    }

    public function destroy($id)
    {
        try {
            $video = Video::find($id);
            if (!$video) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Video not found',
                ], 404);
            }
            $video->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Video has been deleted successfully',
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
