<?php

use App\Events\ChatPusherEvent;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CalendarController;
use App\Http\Controllers\API\CartController;
use App\Http\Controllers\API\CategoryNewsController;
use App\Http\Controllers\API\CategoryProductsController;
use App\Http\Controllers\API\NewsController;
use App\Http\Controllers\API\NotificationController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\SliderController;
use App\Http\Controllers\API\SystemBranchController;
use App\Http\Controllers\API\VoucherController;
use App\Http\Requests\Chat\ParticipantAddRequest;
use App\Http\Requests\PusherChatRequest;
use App\Models\Chat;
use App\Models\ChatGroup;
use App\Models\ChatImage;
use App\Models\Notification;
use App\Models\ParticipantChat;
use App\Models\RestfulAPI;
use App\Traits\StorageImageTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('cron')->group(function () {
    Route::get('/', [
        'uses'=>'App\Http\Controllers\Cronner\CronnerController@callback',
    ]);
});

Route::prefix('public')->group(function () {

    Route::prefix('products')->group(function () {
        Route::get('/', [ProductController::class, 'list']);
        Route::get('/{id}', [ProductController::class, 'get']);
    });

    Route::prefix('cart')->group(function () {
        Route::post('/', [CartController::class, 'listNotAuth']);
    });

    Route::prefix('order')->group(function () {
        Route::post('/', [OrderController::class, 'storeNotAuth']);
    });

    Route::prefix('news')->group(function () {
        Route::get('/', [NewsController::class, 'list']);
        Route::get('/{id}', [NewsController::class, 'get']);
    });

    Route::prefix('categories-news')->group(function () {
        Route::get('/', [CategoryNewsController::class, 'list']);
    });

    Route::prefix('categories-products')->group(function () {
        Route::get('/', [CategoryProductsController::class, 'list']);
    });

    Route::prefix('system-branches')->group(function () {
        Route::get('/', [SystemBranchController::class, 'list']);
    });

    Route::prefix('slider')->group(function () {
        Route::get('/', [SliderController::class, 'list']);
    });

    Route::prefix('calendars')->group(function () {
        Route::get('/{id}', [CalendarController::class, 'get']);
    });

    Route::prefix('auth')->group(function () {
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/sign-in', [AuthController::class, 'signIn']);
        Route::post('/check-exist', [AuthController::class, 'checkExist']);
        Route::post('/reset-password', [AuthController::class, 'resetPassword']);
    });
});

Route::prefix('user')->group(function () {

    Route::group(['middleware' => ['auth:sanctum']], function () {

        Route::prefix('auth')->group(function () {
            Route::post('/logout', [AuthController::class, 'logout']);
        });

        Route::prefix('profile')->group(function () {
            Route::put('/', [AuthController::class, 'update']);
            Route::post('/avatar', [AuthController::class, 'updateAvatar']);
            Route::delete('/', [AuthController::class, 'delete']);
        });

        Route::prefix('notification')->group(function () {
            Route::get('/', [NotificationController::class, 'list']);
            Route::get('/count-not-read', [NotificationController::class, 'countNotRead']);
            Route::post('/read/{id}', [NotificationController::class, 'read']);
        });

        Route::prefix('product-seen-recent')->group(function () {
            Route::get('/', [ProductController::class, 'productSeenRecent']);
        });

        Route::prefix('cart')->group(function () {
            Route::get('/', [CartController::class, 'list']);
            Route::post('/', [CartController::class, 'store']);
            Route::put('/{id}', [CartController::class, 'update']);
            Route::delete('/{id}', [CartController::class, 'delete']);
        });

        Route::prefix('order')->group(function () {
            Route::get('/', [OrderController::class, 'list']);
            Route::post('/', [OrderController::class, 'store']);
        });

        Route::prefix('voucher')->group(function () {
            Route::get('/', [VoucherController::class, 'list']);
            Route::post('/', [VoucherController::class, 'store']);
            Route::post('/check-with-carts', [VoucherController::class, 'checkWithCarts']);
        });
    });


    Route::prefix('chat')->group(function () {
        Route::group(['middleware' => ['auth:sanctum', 'banned']], function () {
            Route::post('/', function (PusherChatRequest $request) {
                $chat = Chat::create([
                    'content' => $request->contents,
                    'user_id' => auth()->id(),
                    'chat_group_id' => (int)$request->chat_group_id,
                ]);

                foreach (ParticipantChat::where('chat_group_id', $request->chat_group_id)->get() as $item) {
                    $item->touch();
                    if (auth()->id() != $item->user_id) {
                        event(new ChatPusherEvent($request, $item, auth()->id(), auth()->user()->feature_image_path, $chat->images));
                    }
                    Notification::sendNotificationFirebase($item->user_id, $request->contents, null, 'Chat', auth()->id(), $request->chat_group_id);

                    if ($item->user_id == auth()->id()) {
                        $item->update([
                            'is_read' => 1
                        ]);
                    } else {
                        $item->update([
                            'is_read' => 0
                        ]);
                    }
                }

                return response()->json($chat);
            });

            Route::post('/image', function (PusherChatRequest $request) {

                $chat = Chat::create([
                    'user_id' => auth()->id(),
                    'content' => $request->contents,
                    'chat_group_id' => (int)$request->chat_group_id,
                ]);

                if ($request->hasFile('feature_images')) {
                    foreach ($request->file('feature_images') as $fileItem) {
                        $dataChatImageDetail = StorageImageTrait::storageTraitUploadMultiple($fileItem, 'chat');
                        ChatImage::create([
                            'image_name' => $dataChatImageDetail['file_name'],
                            'image_path' => $dataChatImageDetail['file_path'],
                            'chat_id' => $chat->id,
                        ]);
                    }
                }

                foreach (ParticipantChat::where('chat_group_id', $request->chat_group_id)->get() as $item) {
                    $item->touch();
                    if (auth()->id() != $item->user_id) {
                        event(new ChatPusherEvent($request, $item, auth()->id(), auth()->user()->feature_image_path, $chat->images));
                    }
                    Notification::sendNotificationFirebase($item->user_id, $request->contents, null, 'Chat', auth()->id(), $request->chat_group_id);
                }

                return response()->json($chat);
            });

            Route::prefix('participant')->group(function () {

                Route::get('/', function (Request $request) {
                    $participantChat = ParticipantChat::where('user_id', auth()->id())->first();

                    if (empty($participantChat)) {
                        return response()->json([
                            "code" => 404,
                            "message" => "Không tìm thấy nhóm chat"
                        ], 404);
                    }

                    $participantChat->update([
                        'is_read' => 1
                    ]);
                    $limit = $request['limit'] ?? 10;
                    $page = $request['page'] ?? 1;
                    $results = Chat::where('chat_group_id', $participantChat->id)->latest('updated_at')->limit($limit)->offset($limit * ($page - 1))->get();

                    //$results = $results->latest('updated_at')->paginate((int)filter_var($request->limit ?? '10', FILTER_SANITIZE_NUMBER_INT))->appends(request()->query());

                    foreach ($results as $item) {
                        $item->images;
                    }
                    return response([
                            'data' => $results
                        ]
                    );
                });

                Route::get('/refresh/{id}', function (Request $request, $chatGroupId) {
                    if (empty(ParticipantChat::where('user_id', auth()->id())->where('chat_group_id', $chatGroupId)->first())) {
                        return response()->json([
                            "code" => 404,
                            "message" => "Không tìm thấy nhóm chat"
                        ], 404);
                    }

                    $item = ParticipantChat::where('chat_group_id', $chatGroupId)->where("user_id", auth()->id())->first();

                    $item->chatGroup;

                    $item->users = $item->users();

                    foreach ($item->users as $user) {
                        $user['profile'] = $user->profilePairing($request, $user->id);
                    }

                    $queries = ["chat_group_id" => $item->chatGroup->id];
                    $requestMessage = $request;
                    $requestMessage->limit = 2;
                    $resultsMessage = RestfulAPI::response(Chat::class, $requestMessage, $queries);

                    foreach ($resultsMessage as $message) {
                        $message->images;
                    }
                    $item->messages = $resultsMessage;

                    return $item;
                });

                Route::post('/create', function (ParticipantAddRequest $request) {

                    $chatGoupsOfGetter = ParticipantChat::where('user_id', $request->getter_id)->get();
                    $chatGoupsOfSender = ParticipantChat::where('user_id', auth()->id())->get();

                    foreach ($chatGoupsOfGetter as $itemGetter) {
                        foreach ($chatGoupsOfSender as $itemSender) {
                            if ($itemSender->chat_group_id == $itemGetter->chat_group_id) {
                                $chatGoup = ChatGroup::find($itemSender->chat_group_id);
                                ParticipantChat::firstOrCreate(
                                    [
                                        'user_id' => auth()->id(),
                                        'chat_group_id' => $chatGoup->id,
                                    ]
                                );

                                ParticipantChat::firstOrCreate(
                                    [
                                        'user_id' => $request->getter_id,
                                        'chat_group_id' => $chatGoup->id,
                                    ]
                                );

                                return response()->json($chatGoup);
                            }
                        }
                    }

                    $chatGoup = ChatGroup::create([
                        'title' => $request->title
                    ]);

                    ParticipantChat::create(
                        [
                            'user_id' => auth()->id(),
                            'chat_group_id' => $chatGoup->id,
                        ]
                    );

                    ParticipantChat::create(
                        [
                            'user_id' => $request->getter_id,
                            'chat_group_id' => $chatGoup->id,
                        ]
                    );

                    return response()->json($chatGoup);
                });

                Route::delete('/{id}', function (Request $request, $chatGroupId) {
                    $participantChat = ParticipantChat::where('user_id', auth()->id())->where('chat_group_id', $chatGroupId)->first();
                    if (!empty($participantChat)) {
                        $participantChat->delete();
                    }

                    return response()->json([
                        'message' => 'deleted!',
                        'code' => 200
                    ]);
                });
            });
        });
    });

});
