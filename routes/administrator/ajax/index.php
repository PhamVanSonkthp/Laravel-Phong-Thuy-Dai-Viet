<?php

use App\Events\ChatPusherEvent;
use App\Http\Requests\PusherChatRequest;
use App\Models\Chat;
use App\Models\ChatImage;
use App\Models\Image;
use App\Models\Notification;
use App\Models\ParticipantChat;
use App\Models\RestfulAPI;
use App\Models\SingleImage;
use App\Models\User;
use App\Traits\StorageImageTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// ajax
Route::prefix('ajax/administrator')->group(function () {
    Route::group(['middleware' => ['auth']], function () {

        Route::prefix('upload-image')->group(function () {
            Route::post('/store', function (Request $request) {

                $item = SingleImage::firstOrCreate([
                    'relate_id' => $request->id,
                    'table' => $request->table,
                ],[
                    'relate_id' => $request->id,
                    'table' => $request->table,
                    'image_path' => 'waiting_update',
                    'image_name' => 'waiting_update',
                ]);

                $dataUploadFeatureImage = StorageImageTrait::storageTraitUpload($request, 'image', 'single', $item->id);

                $item->update([
                    'image_path' => $dataUploadFeatureImage['file_path'],
                    'image_name' => $dataUploadFeatureImage['file_name'],
                ]);
                $item->refresh();

                return response()->json($item);

            })->name('ajax,administrator.upload_image.store');
        });

        Route::prefix('upload-multiple-images')->group(function () {

            Route::post('/store', function (Request $request) {

                $item = Image::create([
                    'uuid' => $request->id,
                    'table' => $request->table,
                    'image_path' => "waiting",
                    'image_name' => "waiting",
                    'relate_id' => $request->relate_id ?? 0,
                ]);

                $dataUploadFeatureImage = StorageImageTrait::storageTraitUpload($request, 'image','multiple', $item->id);

                $dataUpdate = [
                    'image_path' => $dataUploadFeatureImage['file_path'],
                    'image_name' => $dataUploadFeatureImage['file_name'],
                ];

                $item->update($dataUpdate);
                $item->refresh();

                return response()->json($item);

            })->name('ajax,administrator.upload_multiple_images.store');

            Route::delete('/delete', function (Request $request) {
                $image = Image::find($request->id);
                if (empty($image)){
                    $image = Image::where('uuid', $request->id)->first();
                }
                if (!empty($image)){
                    $image->delete();
                }
                return response()->json($image);
            })->name('ajax,administrator.upload_multiple_images.delete');

            Route::put('/sort', function (Request $request) {

                foreach ($request->ids as $index => $id){
                    $image = Image::find($id);
                    if (empty($image)){
                        $image = Image::where('uuid', $id)->first();
                    }

                    if (!empty($image)){
                        $image->update([
                            'index' => $index
                        ]);
                    }
                }

                return response()->json($request->ids);
            })->name('ajax,administrator.upload_multiple_images.sort');

        });
    });

    Route::prefix('/orders')->group(function () {
        Route::group(['middleware' => ['auth']], function () {
            Route::put('/update-to-shipping/{id}', [
                'as' => 'ajax.administrator.orders.update_to_shipping',
                'uses' => 'App\Http\Controllers\Admin\OrderController@updateToShipping',
                'middleware' => 'can:orders-edit',
            ]);
        });
    });


    Route::prefix('/')->group(function () {
        Route::group(['middleware' => ['auth']], function () {
            Route::prefix('chat')->group(function () {
                Route::prefix('participant')->group(function () {

                    Route::get('/{id}', function (Request $request, $chatGroupId) {
                        if (empty(ParticipantChat::where('user_id', auth()->id())->where('chat_group_id', $chatGroupId)->first())) {
                            return response()->json([
                                "code" => 404,
                                "message" => "Không tìm thấy nhóm chat"
                            ], 404);
                        }

                        $queries = ["chat_group_id" => $chatGroupId];
                        $results = RestfulAPI::response(Chat::class, $request, $queries);

                        foreach ($results as $item) {
                            $item->user;
                            $item->images;
                        }
                        return $results;
                    })->name('administrator.chat.participant');

                });

                Route::post('/create', function (PusherChatRequest $request) {

                    $chat = Chat::create([
                        'content' => $request->contents,
                        'user_id' => auth()->id(),
                        'chat_group_id' => $request->chat_group_id,
                    ]);

                    for ($x = 0; $x < $request->total_files; $x++) {
                        if ($request->hasFile('feature_image' . $x)) {
                            $dataChatImageDetail = StorageImageTrait::storageTraitUploadMultiple( $request->file('feature_image'.$x),  'chat');

                            ChatImage::create([
                                'image_name' => $dataChatImageDetail['file_name'],
                                'image_path' => $dataChatImageDetail['file_path'],
                                'chat_id' => $chat->id,
                            ]);
                        }
                    }

                    foreach (ParticipantChat::where('chat_group_id', $request->chat_group_id)->get() as $item) {
                        if (auth()->id() != $item->user_id) {
                            $image_link = User::find($item->user_id)->feature_image_path;
                            event(new ChatPusherEvent($request, $item, auth()->id(), $image_link,$chat->images));
                        }

                        Notification::sendNotificationFirebase($item->user_id, $request->contents,null,'Chat',auth()->id(), $request->chat_group_id);

                        if ($item->user_id == auth()->id()){
                            $item->update([
                                'is_read' => 1
                            ]);
                        }else{
                            $item->update([
                                'is_read' => 0
                            ]);
                        }

                    }

//                    return view('administrator.chat.components')->with(['itemChat' => $chat])->render();

                    return response()->json($chat);
                })->name('administrator.chat.create');

            });
        });
    });

});

