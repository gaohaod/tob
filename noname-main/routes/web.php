<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\GamesController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ForumController;
use App\Http\Controllers\AvatarController;
use App\Http\Controllers\RenderController;
use App\Http\Controllers\GameserverController;
use App\Http\Controllers\ThumbnailController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\GameTicketController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\PersistenceController;
use App\Http\Controllers\DiscordController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\MessagingController;
use App\Http\Controllers\FriendsController;

use App\Http\Middleware\CheckAdmin;
use App\Http\Middleware\BanMiddleware;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Jobs\ThumbnailJob;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/GETPEEPS', function () {
    return view('jokes.buypeeps');
})->name('FREEPEEPS');

Route::get('/app/rules', function () {
    return view('legal.rules');
})->name('legal.rules');
Route::get('/app/policy', function () {
    return view('legal.policy');
})->name('legal.policy');

        Route::get('/Status', function () {
            return view('status.main');
        })->name('status');


    Route::get('/app/videos', [VideoController::class, 'index'])->name('videos');
    Route::get('/app/videos/{id}', [VideoController::class, 'view'])->name('videos.view');

Route::get('/app/users', [UserController::class, 'index'])->name('app.users');
Route::get('/app/users/search', [UserController::class, 'search'])->name('app.users.search');
Route::middleware(['auth'])->group( function() {
    Route::get('/app/moderation', function () {
        return view('moderation');
    })->name('app.moderation');
});

Route::middleware(['auth', BanMiddleware::class])->group(function () {

    /*Route::get('/app/home', function () {
        return view('dashboard');
    })->middleware(['auth', 'verified'])->name('app.home');*/

    Route::get('/app/presence', [UserController::class, 'registerPresence'])->name('app.presence');

    Route::get('/app/home', [UserController::class, 'home'])->name('app.home');

    Route::get('/app/settings', [ProfileController::class, 'edit'])->name('app.profile.edit');

    Route::get('/app/user/{id}', [UserController::class, 'view'])->name('app.profile.view');

    Route::post('/app/settings/change-bio', [ProfileController::class, 'updateBio'])->name('app.profile.change-bio')->middleware('throttle:15,1');
    Route::post('/app/change-peeps', [UserController::class, 'changePeepType'])->name('app.change-peeps');
    
    Route::get('/app/places', [GamesController::class, 'index'])->name('app.places');
    Route::get('/app/places/search', [GamesController::class, 'search'])->name('app.place.search');
    Route::get('/app/place/{id}', [GamesController::class, 'show'])->name('app.place.view');

    Route::get('/app/download', function () {
        return view('download');
    })->name('app.download');

    Route::get('/app/catalog', [CatalogController::class, 'index'])->name('app.catalog');
    Route::get('/app/catalog/search', [CatalogController::class, 'search'])->name('app.catalog.search');
    
    Route::get('/app/transactions', [CatalogController::class, 'getTransactions'])->name('app.transactions');
    
    Route::get('/app/model/{id}', [CatalogController::class, 'model'])->name('app.item.view-model');
    Route::get('/app/catalog/{by}', [CatalogController::class, 'sort'])->name('app.catalog.sort');
    Route::get('/app/item/{id}', [CatalogController::class, 'show'])->name('app.item.view');

    Route::get('/app/create', function () {
        return view('create');
    })->name('app.create');

    // create routes for the options
    Route::get('/app/create/asset', function () {
        return view('create.asset');
    })->name('app.create.asset');
    
    Route::get('/app/create/place', function () {
        return view('create.place');
    })->name('app.create.place');

    Route::get('/app/3d-thumbnail-testing/', function () {
        return view('3d-thumbnail-test.main');
    })->name('app.3d-thumbnail-test');

    /*Route::get('/app/user/1', function () {
        return view('view.user');
    })->name('app.user.');*/

    Route::get('/app/forum/vote', [ForumController::class, 'vote'])->name('app.forum.vote');

    Route::get('/app/forum', [ForumController::class, 'index'])->name('app.forum');

    Route::get('/app/forum/{category_id}', [ForumController::class, 'viewCategory'])->name('app.forum.viewcat');

    Route::get('/app/forum/view/{post_id}', [ForumController::class, 'viewPost'])->name('app.forum.view');
    Route::get('/app/forum/reply/{post_id}', [ForumController::class, 'replyToPost'])->name('app.forum.reply');
    Route::post('/app/forum/reply/{post_id}', [ForumController::class, 'createReply'])->name('app.forum.reply.create')->middleware('throttle:45,1');

    Route::get('/app/forum/new/{category_id}', [ForumController::class, 'newPost'])->name('app.forum.new-post');
    Route::post('/app/forum/new/{category_id}', [ForumController::class, 'createPost'])->name('app.forum.new-post.create')->middleware('throttle:10,1');

    Route::get('/app/forum/lock/{postId}', [ForumController::class, 'toggleLock'])->name('app.forum.lock')->middleware('throttle:50,1');
    Route::get('/app/forum/pin/{postId}', [ForumController::class, 'togglePin'])->name('app.forum.pin')->middleware('throttle:50,1');
    Route::get('/app/forum/delete/{postId}', [ForumController::class, 'deletePost'])->name('app.forum.delete')->middleware('throttle:50,1');
    Route::get('/app/reply/delete/{replyId}', [ForumController::class, 'moderateReply'])->name('app.reply.delete')->middleware('throttle:50,1');
    Route::get('/app/generate-token', [DiscordController::class, 'generateToken'])->name('app.generate-token')->middleware('throttle:4,1');
    Route::get('/app/clear-all-notifications', [NotificationController::class, 'clearAll'])->name('app.clear-all-notifications')->middleware('throttle:4,1');
    Route::get('/app/get-items/{type}', [AvatarController::class, 'getItems'])->name('app.get-items');

    Route::get('/app/get-notifications', [NotificationController::class, 'get'])->name('app.notifications')->middleware('throttle:50,1');

    Route::get('/app/friends/pending', [FriendsController::class, 'getPending'])->name('app.friends.pending');
    Route::get('/app/user/{userId}/friends', [FriendsController::class, 'getFriends'])->name('app.user.friends');
    Route::get('/app/friend/accept/{id}', [FriendsController::class, 'accept'])->name('app.friend.accept');
    Route::get('/app/friend/decline/{id}', [FriendsController::class, 'removeOrReject'])->name('app.friend.decline');
    Route::get('/app/user/add/{id}', [FriendsController::class, 'add'])->name('app.friend.add');

    Route::get('/app/avatar', [AvatarController::class, 'show'])->name('app.avatar');

    Route::get('/app/messages', [MessagingController::class, 'index'])->name('app.messages');
    Route::get('/app/message/{message}', [MessagingController::class, 'view'])->name('app.message.view');

    Route::post('/app/user/change-gender', [UserController::class, 'changeGender'])->name('app.user.change-gender');
    Route::get('/app/messages/new', function () {
        return view('messages.new');
    })->name('app.messages.new');
    Route::post('/app/messages/new', [MessagingController::class, 'sendMessage'])->name('app.messages.new.post');
    Route::get('/app/messages/archive/{message}', [MessagingController::class, 'archiveMessage'])->name('app.messages.archive');
    Route::get('/app/messages/delete/{message}', [MessagingController::class, 'deleteMessage'])->name('app.messages.delete');

    // APIs

    Route::get('/app/user/change-membership', [UserController::class, 'changeMship'])->name('app.user.change-membership');
    Route::get('/app/get-places', [GamesController::class, 'getGames'])->name('app.get-places')->middleware('throttle:45,1');
    Route::get('/app/buy-slot', [CatalogController::class, 'buyPlaceSlot'])->name('app.buy.slot')->middleware('throttle:15,1');
    Route::get('/app/buy-item/{id}', [CatalogController::class, 'buy'])->name('app.buy.item')->middleware('throttle:15,1');
    Route::get('/app/change-body-color/{color}/{part}', [AvatarController::class, 'changeBodyColor'])->name('app.change-body-color')->middleware('throttle:50,1');
    Route::get('/app/wear-item/{id}', [AvatarController::class, 'wearItem'])->name('app.wear-item')->middleware('throttle:50,1');

    Route::get('/app/tickets/generate-game-ticket/{placeId}', [GameTicketController::class, 'requestTicket'])->name('app.tickets.generate-game-ticket')->middleware('throttle:20,1');
    Route::get('/app/tickets/remove-game-tickets', [GameTicketController::class, 'DeleteAllTickets'])->name('app.tickets.remove-game-tickets'); // This is less destructive

    Route::post('/app/upload-place', [UploadController::class, 'uploadPlace'])->name('app.upload-place')->middleware('throttle:10,1');
    Route::post('/app/upload-asset', [UploadController::class, 'upload'])->name('app.upload-asset')->middleware('throttle:10,1');
});

    // Client APIs
    Route::get('/asset', [ClientController::class, 'asset'])->name('client.asset');
    Route::get('/Asset', [ClientController::class, 'asset'])->name('client.asset.cap');
    Route::get('/get-place', [ClientController::class, 'getPlace'])->name('client.get-place');

    Route::get('/GetAllowedMD5Hashes', function () {
        return response('{"data":["908191885a6a196ae2b7968c0e1deaf1"]}')->header('Content-Type', 'text/plain');
    });

    Route::get('/GetAllowedSecurityVersions', function () {
        return response('{"data":["0.270.0pcplayer"]}')->header('Content-Type', 'text/plain');
    });

    Route::get('/game/visit.ashx', [ClientController::class, 'visit_2016'])->name('studio.visit');
    Route::get('/game/join.ashx', [ClientController::class, 'join_16'])->name('client.join');
    Route::get('/Game/PlaceLauncher.ashx', [ClientController::class, 'placelauncher'])->name('client.place.launcher');

    Route::any('/marketplace/productinfo', [ClientController::class, 'placeinfo'])->name('client.productinfo');
    
    Route::get('/char/{id}', [AvatarController::class, 'charapp'])->name('client.character');

    Route::get('/Asset/BodyColors.ashx', [AvatarController::class, 'bodycolors'])->name('body.colors');

    Route::get('/game/GetCurrentUser.ashx', function () {
        if (Auth::check()) {
            return Auth::user()->id;
        } else {
            return '-1';
        }
    })->name('gcu');

    Route::get('/Game/LuaWebService/HandleSocialRequest.ashx', [ClientController::class, 'LuaWebServiceHandleSocial'])->name('lua.web.service');

    Route::get('/clienttest/currentuser', [ClientController::class, 'getCurrentUser'])->name('test.test');
    
    // Studio pages
    Route::get('/app/studio/landing', function () {
        return view('studio.landing');
    })->name('app.studio.landing');

    Route::get('/ide/welcome', function () {
        return view('studio.landing');
    })->name('app.studio.landing.ide');


    Route::get('/test/{id}', [RenderController::class, 'full'])->name('test')->middleware('throttle:15,1');

    Route::get('/thumbnail/test/{id}', [ThumbnailController::class, 'placeThumbnail'])->name('thumbnailtest')->middleware('throttle:15,1');

    Route::get('/thumbnail/gear/{id}', [ThumbnailController::class, 'renderGear'])->name('thumbnail.gear')->middleware('throttle:15,1');
    Route::get('/thumbnail/shirt/{id}', [ThumbnailController::class, 'renderShirt'])->name('thumbnail.shirt')->middleware('throttle:15,1');
    Route::get('/thumbnail/pants/{id}', [ThumbnailController::class, 'renderPants'])->name('thumbnail.pants')->middleware('throttle:15,1');
    Route::get('/thumbnail/hat/{id}', [ThumbnailController::class, 'renderHat'])->name('thumbnail.hat')->middleware('throttle:15,1');

    Route::get('/universes/validate-place-join', function () {
            return "true";
    })->name('validate.place.join');

    Route::get('/GameServer/{jobId}/renew', [GameserverController::class, 'renewGameserver'])->name('gameserver.renew');
    Route::get('/GameServer/{jobId}/complete', [GameserverController::class, 'completeGameserver'])->name('gameserver.complete');
    Route::get('/GameServer/{jobId}/delete', [GameserverController::class, 'deleteJobGameserver'])->name('gameserver.delete');

    Route::middleware(['auth', CheckAdmin::class])->group(function () {

        Route::get('/app/admin/ban-user/{username}', [AdminController::class, 'banUser'])->name('admin.ban-user');
        Route::get('/app/admin/unban-user/{username}', [AdminController::class, 'unbanUser'])->name('admin.unban-user');

        Route::get('/app/admin/main', function () {
            return view('admin.main');
        })->name('app.admin.main');

        Route::get('/app/admin/ban/user', function () {
            return view('admin.ban.user');
        })->name('app.admin.ban-user-p');

        Route::get('/app/admin/ban/asset', function () {
            return view('admin.ban.place');
        })->name('app.admin.ban-asset-p');

        Route::get('/app/admin/render', function () {
            return view('admin.render.main');
        })->name('app.admin.render-asset');

        Route::get('/app/admin/instances', function () {
            return view('admin.instances.main');
        })->name('app.admin.instances.main');

        Route::get('/app/admin/create/hat', function () {
            return view('admin.create.hat');
        })->name('admin.create.hat.view');

        Route::get('/app/admin/create/face', function () {
            return view('admin.create.face');
        })->name('admin.create.face.view');

        Route::get('/app/admin/create/gear', function () {
            return view('admin.create.gear');
        })->name('admin.create.gear.view');

        Route::get('/app/admin/create/head', function () {
            return view('admin.create.head');
        })->name('admin.create.head.view');

        Route::get('/app/admin/create-invite', function () {
            return view('admin.create-invite.main');
        })->name('admin.create-invite');

        Route::get('/app/admin/verify-tickets', function () {
            return view('admin.ticket');
        })->name('admin.tickets');
        
        Route::get('/app/admin/create-video', function () {
            return view('admin.video.upload');
        })->name('admin.create-video');

        Route::get('/app/admin/alert', function () {
            return view('admin.alert');
        })->name('admin.alert');

        Route::post('/app/admin/alert', [AdminController::class, 'createAlert'])->name('admin.alert-post');
        Route::get('/app/admin/alerts', [AdminController::class, 'alerts'])->name('admin.alerts');
        Route::get('/app/admin/remove-alert/{id}', [AdminController::class, 'deleteAlert'])->name('admin.alert-delete');

        Route::post('/app/admin/create-video', [UploadController::class, 'uploadVideo'])->name('admin.create-video-post');

        Route::get('/app/admin/invites', [AdminController::class, 'viewInvites'])->name('admin.invites');

        Route::get('/app/admin/ban-asset/{id}', [AdminController::class, 'banAsset'])->name('admin.ban-asset');
        Route::get('/app/admin/verify-token', [DiscordController::class, 'verifyToken'])->name('admin.verify-token');

        Route::get('/app/admin/renew-invite/{inv}', [AdminController::class, 'renewInvite'])->name('admin.invites.renew');
        Route::get('/app/admin/revoke-invite/{inv}', [AdminController::class, 'revokeInvite'])->name('admin.invites.revoke');

        Route::get('/app/admin/thumbnail/gear/{id}', function ($id) {
            ThumbnailJob::dispatch("gear", $id);
        })->name('admin.thumbnail.gear');

        Route::get('/app/admin/thumbnail/head/{id}', function ($id) {
            ThumbnailJob::dispatch("head", $id);
        })->name('admin.thumbnail.head');

        Route::get('/app/admin/thumbnail/model/{id}', function ($id) {
            ThumbnailJob::dispatch("model", $id);
        })->name('admin.thumbnail.model');

        Route::get('/app/admin/thumbnail/user/{id}', function ($id) {
            ThumbnailJob::dispatch("user", $id);
        })->name('admin.thumbnail.user');
        
        Route::get('/app/admin/thumbnail/gear/{id}', function ($id) {
            ThumbnailJob::dispatch("gear", $id);
        })->name('admin.thumbnail.gear');

        Route::get('/app/admin/thumbnail/shirt/{id}', function ($id) {
            ThumbnailJob::dispatch("shirt", $id);
        })->name('admin.thumbnail.shirt');

        Route::get('/app/admin/thumbnail/pants/{id}', function ($id) {
            ThumbnailJob::dispatch("pants", $id);
        })->name('admin.thumbnail.pants');

        Route::get('/app/admin/thumbnail/hat/{id}', function ($id) {
            ThumbnailJob::dispatch("hat", $id);
        })->name('admin.thumbnail.hat');

        Route::get('/app/admin/thumbnail/mesh/{id}', function ($id) {
            ThumbnailJob::dispatch("mesh", $id);
        })->name('admin.thumbnail.mesh');

        Route::get('/app/admin/thumbnail/place/{id}', function ($id) {
            ThumbnailJob::dispatch("place", $id);
        })->name('admin.thumbnail.place');

        Route::get('/app/admin/generateKey', [AdminController::class, 'createKey'])->name('admin.create-key');
        
        Route::get('/app/admin/jobs', [AdminController::class, 'getJobs'])->name('admin.jobs');

        Route::get('/app/admin/moderation', [AdminController::class, 'getPendingAssets'])->name('admin.moderation.assets');

        Route::post('/app/admin/create/hat', [AdminController::class, 'UploadHat'])->name('admin.create.hat');

        Route::post('/app/admin/create/face', [UploadController::class, 'uploadFace'])->name('admin.create.face');

        Route::post('/app/admin/create/head', [AdminController::class, 'uploadHead'])->name('admin.create.head');

        Route::post('/app/admin/create/gear', [AdminController::class, 'uploadGear'])->name('admin.create.gear');

        Route::get('/app/admin/approve-asset/{id}', [AdminController::class, 'approveAsset'])->name('admin.approve-asset');

        Route::get('/app/admin/decline-asset/{id}', [AdminController::class, 'declineAsset'])->name('admin.decline-asset');

        Route::get('/app/admin/decline-asset-banuser/{id}', [AdminController::class, 'declineAssetAndBanCreator'])->name('admin.decline-asset-banuser');
        
    });

    Route::get('/rcc/register', [GameserverController::class, 'registerRcc'])->name('rcc.register');
    Route::get('/rcc/remove/{uuid}', [GameserverController::class, 'removeRcc'])->name('rcc.remove');

    Route::get('/game/wipeouts/{userId}/{accessKey}', [GameserverController::class, 'addWipeout'])->name('game.wipeouts');
    Route::get('/game/knockouts/{userId}/{accessKey}', [GameserverController::class, 'addKnockout'])->name('game.knockouts');

    Route::get('/2012/game-start/{placeId}/{accessKey}', [GameserverController::class, 'startServer'])->name('2012.game-start');
    Route::get('/2012/game-stop/{jobId}/{accessKey}', [GameserverController::class, 'stopServer'])->name('2012.game-stop');
    Route::get('/2012/game-remove/{jobId}/{accessKey}', [GameserverController::class, 'removeServer'])->name('2012.game-remove');
    Route::get('/gameserver', [GameserverController::class, 'gameserver2012'])->name('2012.gameserver');

    Route::get('/Game/LoadPlaceInfo.ashx', [ClientController::class, 'loadPlaceInfo'])->name('game.load-place-info');

    Route::get('/game/gears-enabled/{id}', [GamesController::class, 'getGearEnabled'])->name('game.gears-enabled');

    Route::get('/IDE/ClientToolbox.aspx', [ClientController::class, 'Toolbox'])->name('game.toolbox');

    Route::get('/setup/launcherVersion', function () {
            return '0.1';
    })->name('setup.launcher.version');

    Route::get('/game/studio.ashx', [ClientController::class, 'studioAshx'])->name('game.studio');

    Route::get('/setup/latest/2017', function () {
        return '1';
    })->name('setup.launcher.2017');

    Route::get('/setup/latest/2012', function () {
        return '1.1';
    })->name('setup.launcher.2012');

    Route::get('/asset/GetScriptState.ashx', function () {
        return 'true';
    })->name('asset.getscriptstate');

    Route::get('/2016/sysstats', function () {
        return response('true')->header('Content-Type', 'text/plain');
    })->name('2016.sysstats');

    Route::get('/Game/KeepAlivePinger.ashx', function () {
        return '69';
    })->name('game.KeepAlivePinger');

    Route::get('/ping', function () {
        return '';
    })->name('ping');

    Route::get('/server/add', [GameserverController::class, 'AddToServer'])->name('game.add-to-server')->middleware('throttle:15,1');
    Route::get('/server/remove', [GameserverController::class, 'RemoveFromServer'])->name('game.remove-from-server')->middleware('throttle:15,1');

    Route::get('/Login/Negotiate.ashx', [ClientController::class, 'getAuth'])->name('login.negotiate');

    Route::get('/Setting/QuietGet/Client12LSettings', [ClientController::class, 'fflags2012'])->name('2012.fflags');

    Route::get('/Setting/QuietGet/ClientAppSettings/', [ClientController::class, 'flags2015'])->name('2015.fflags');

    Route::get('/Setting/QuietGet/RCCService/', [ClientController::class, 'rcc2015'])->name('2015.fflags.rcc');

    Route::get('/2012/join/{token}', [ClientController::class, 'join12'])->name('2012.join');

    Route::any('/Asset/GetScriptState.ashx', function () { return response('true')->header('Content-Type', 'text/plain'); });


    Route::get('/app/blog/home', function () {
        return view('blog.main');
    })->name('app.blog.home');

    Route::get('/universes/validate-place-join', function () {
        return 'true';
    })->name('vpj');

    Route::get('/game/players/{id}', function () {
        return 'true';
    })->name('game.players');

    Route::any('/persistence/getSortedValues', [PersistenceController::class, 'getSortedValues']);
    Route::any('/persistence/getV2', [PersistenceController::class, 'getV2']);
    Route::any('/persistence/set', [PersistenceController::class, 'set']);

    
    Route::get('/Game/GamePass/GamePassHandler.ashx', function () {
        return '<Value type="boolean">true</Value>';
    })->name('game.gamepass.handler');

require __DIR__.'/auth.php';
