<?php

namespace App\Http\Controllers\API;


use App\Models\Gallery;
use App\Models\User;
use App\Repositories\GalleryRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Illuminate\Support\Facades\Response;
use Prettus\Repository\Exceptions\RepositoryException;
use Flash;
use Image;

/**
 * Class GalleryController
 * @package App\Http\Controllers\API
 */
class GalleryAPIController extends Controller
{
    /** @var  GalleryRepository */
    private $galleryRepository;

    public function __construct(GalleryRepository $galleryRepo)
    {
        $this->galleryRepository = $galleryRepo;
    }

    /**
     * Display a listing of the Gallery.
     * GET|HEAD /galleries
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        if ($request->header('devicetoken')) {
            $user = User::where('device_token', $request->header('devicetoken'))->first();

            if (empty($user)) {
                return $this->sendError('User not found', 401);
            }

            $images = $user->gallery;

            $response = [];
            foreach ($images as $image) {
                $response[] = [
                    'image' => asset('storage/gallery') . '/' . $image->image,
                    'user_id' => $image->user_id
                ];
            }

            // $new_galleries = $galleries->toArray();

            // $galleries_final = [];
            // foreach($new_galleries as $new_gallery)
            // {
            //     if($new_gallery['approved'] == 1)
            //     {
            //         array_push($galleries_final,$new_gallery);
            //     }
            // }

            return $this->sendResponse($response, 'Galleries retrieved successfully');


        } else
            return $this->sendError('nothing to process', 401);
    }

    /**
     * Display the specified Gallery.
     * GET|HEAD /galleries/{id}
     *
     * @param int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        /** @var Gallery $gallery */
        if (!empty($this->galleryRepository)) {
            $gallery = $this->galleryRepository->findWithoutFail($id);
        }

        if (empty($gallery)) {
            return $this->sendError('Gallery not found');
        }

        return $this->sendResponse($gallery->toArray(), 'Gallery retrieved successfully');
    }

    public function store(Request $request)
    {
        if ($request->header('devicetoken')) {
            $user = User::where('device_token', $request->header('devicetoken'))->first();

            if (empty($user)) {
                return $this->sendError('User not found', 401);
            }
            if (!$request->hasFile('image')) {
                return response()->json(['upload_file_not_found'], 400);
            }
            $allowedfileExtension = ['JPEG', 'jpg', 'png', 'gif', 'svg'];
            $files = $request->file('image');
            $errors = [];

            foreach ($request->file('image') as $file) {

                $extension = $file->getClientOriginalExtension();
                $check = in_array($extension, $allowedfileExtension);

                if ($check) {


                    $imageName = uniqId() . $file->getClientOriginalName();
                    $file->move(public_path('storage/gallery'), $imageName);

                    // $image->save(public_path('storage/gallery/') . $imageName);
//return 55;
//                    $image->save(public_path('images\gallery/'.$imageName));


//                    $path = $file->store('public\images\gallery');
//                    $name = $file->getClientOriginalName();

                    //store image file into directory and db
                    $save = new Gallery();
                    $save->image = $imageName;
                    $save->user_id = $user->id;
                    $save->save();
                } else {
                    return $this->sendError('invalid_file_format', 422);
                }


            }
            return $this->sendResponse([], 'file uploaded successfully');

//        return response()->json(['what'], 200);
        } else
            return $this->sendError('nothing to process', 401);
    }
    public function destroy(Request $request)
    {
        if ($request->header('devicetoken')) {
            $user = User::where('device_token', $request->header('devicetoken'))->first();

            if (empty($user)) {
                return $this->sendError('User not found', 401);
            }

            $gallery = Gallery::find($request->image_id);
            $gallery->delete();

            return $this->sendResponse([], 'file delete successfully');

//        return response()->json(['what'], 200);
        } else
            return $this->sendError('nothing to process', 401);
    }
}
