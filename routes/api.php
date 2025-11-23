<?php

use App\Http\Controllers\Api\{
    AppoitmentController,
    CountryController,
    DocumentController,
    DocumentationController,
    FaqChabotController,
    MessageController,
    NotificationController,
    ReceiptController,
    UserController,
    VisaController,
    VisaRequestController,
    VisaTypeController,
    PaymentController,
    ProfilController,
};
use Illuminate\Support\Facades\Route;

Route::post('/auth/register', [UserController::class, 'register']);
Route::post('/auth/login', [UserController::class, 'login']);

Route::get('/faqchat', [FaqChabotController::class, 'index']);

// webhook / notification (POST - serveur -> serveur)
Route::get('/payement/callback', [PaymentController::class, 'handleCallback'])->name('paiement.callback');

// user return (GET - navigateur redirect après paiement) -> redirect vers le frontend
Route::get('/payment/return', [PaymentController::class, 'returnFromGateway'])->name('payment.return');

// polling status
Route::get('/payment/status/{reference}', [PaymentController::class, 'checkStatus']);

Route::get('/documentation', [DocumentationController::class, 'index']);

// Groupe API avec Sanctum pour l’authentification
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/auth/logout', [UserController::class, 'logout']);

    Route::get('/document', [DocumentController::class, 'index']);
    Route::get('/document/show/{id}', [DocumentController::class, 'show']);


    Route::get('/user/show/{id}', [UserController::class, 'show']);


    Route::post('/visa/storestore', [VisaController::class, 'storestore']);

    Route::get('/country', [CountryController::class, 'index']);
    Route::get('/country/show/{id}', [CountryController::class, 'show']);

    Route::get('/visatype', [VisaTypeController::class, 'index']);
    Route::get('/visarequest/show/{id}', [VisaRequestController::class, 'show']);

    Route::get('/document/showbyvisarequest/{visaRequestId}', [DocumentController::class, 'getByVisaRequest']);
    Route::put('/document/update/{id}', [DocumentController::class, 'update']);
    // création de paiement (init)


    // Route::get('/profil/showuser/{id}', [ProfilController::class, 'showUser']);
    // Routes pour custom
    Route::middleware(['role:custom'])->group(function () {
        Route::post('/visarequest/store', [VisaRequestController::class, 'store']);
        Route::delete('/visarequest/delete/{id}', [VisaRequestController::class, 'destroy']);

        Route::put('/user/storeprofil', [UserController::class, 'storeProfil']);
        Route::put('/user/updateprofil/{id}', [UserController::class, 'updateProfil']);

        Route::post('/document/store', [DocumentController::class, 'store']);
        Route::delete('/document/delete/{id}', [DocumentController::class, 'destroy']);

        Route::get('/receipt/show', [ReceiptController::class, 'show']);

        // Route::apiResource('/document', DocumentController::class, ['only' => ['store', 'update', 'destroy']]);


        Route::get('/useragent', [UserController::class, 'getAgent']);
    });


    // Routes pour agents
    Route::middleware(['role:agent'])->group(function () {
        Route::get('/visa-requests', [VisaController::class, 'index']);

        Route::post('/appoitment/store', [AppoitmentController::class, 'store']);
        Route::delete('/appoitment/delete/{id}', [AppoitmentController::class, 'destroy']);

        Route::get('/message', [MessageController::class, 'index']);


        Route::prefix('notification')->group(function () {
            Route::get('/', [NotificationController::class, 'index']);
            Route::post('/store', [NotificationController::class, 'store']);
            Route::put('/update/{id}', [NotificationController::class, 'update']);
            Route::delete('/delete/{id}', [NotificationController::class, 'destroy']);
        });

        Route::post('/visa', [VisaController::class, 'index']);
    });

    // Routes seulement pour les admins
    Route::middleware(['role:admin'])->group(function () {

        // Route::post('/document/store', [DocumentController::class,'store']);

        Route::put('/user/storecustombyadmin', [UserController::class, 'storeCustomByAdmin']);

        Route::get('/visarequest', [VisaRequestController::class, 'index']);

        Route::get('/visa', [VisaController::class, 'index']);
        Route::post('/visa/store', [VisaController::class, 'store']);
        Route::put('/visa/update/{id}', [VisaController::class, 'update']);
        Route::delete('/visa/delete/{id}', [VisaController::class, 'destroy']);

        Route::post('/visatype/store', [VisaTypeController::class, 'store']);
        Route::put('/visatype/update/{id}', [VisaTypeController::class, 'update']);
        Route::delete('/visatype/delete/{id}', [VisaTypeController::class, 'destroy']);


        Route::post('/country/store', [CountryController::class, 'store']);
        Route::put('/country/update/{id}', [CountryController::class, 'update']);
        Route::delete('/country/delete/{id}', [CountryController::class, 'destroy']);

        Route::post('/documentation/store', [DocumentationController::class, 'store']);
        Route::put('/documentation/update/{id}', [DocumentationController::class, 'update']);
        Route::delete('/documentation/delete/{id}', [DocumentationController::class, 'destroy']);

        Route::post('/faqchat/store', [FaqChabotController::class, 'store']);
        Route::get('/faqchat/show/{id}', [FaqChabotController::class, 'show']);
        Route::put('/faqchat/update/{id}', [FaqChabotController::class, 'update']);
        Route::delete('/faqchat/delete/{id}', [FaqChabotController::class, 'destroy']);



        Route::get('/user', [UserController::class, 'index']);
    });
    Route::get('/profil/user/{id}', [ProfilController::class, 'showUser']);
    // ProfilController routes accessible to admin
    Route::middleware(['role:admin|custom'])->group(function () {
        Route::post('/payment/store', [PaymentController::class, 'store']);


        Route::put('/payment/update/{id}', [PaymentController::class, 'update']);

        Route::get('/payment', [PaymentController::class, 'index']);
        Route::delete('/payment/delete/{id}', [PaymentController::class, 'destroy']);
        Route::post('/profil/store', [ProfilController::class, 'store']);
        Route::put('/user/update/{id}', [UserController::class, 'update']);
        Route::post('/user/store', [UserController::class, 'store']);

        Route::delete('/user/delete/{id}', [UserController::class, 'destroy']);

        Route::get('/payment/show/{id}', [PaymentController::class, 'show']);
        Route::get('/payment/user/{id}', [PaymentController::class, 'showUser']);

        Route::put('/profil/update/{id}', [ProfilController::class, 'update']);
        Route::put('/user/updatecustombyadmin/{id}', [UserController::class, 'updateCustomByAdmin']);
    });

    Route::get('/notification/user/{id}', [NotificationController::class, 'showUser']);
    Route::get('/appoitment/user/{id}', [AppoitmentController::class, 'showByUser']);

    Route::get('/notification', [NotificationController::class, 'index']);
    Route::middleware(['role:admin|agent'])->group(function () {
        Route::put('/notification/update/{id}', [NotificationController::class, 'update']);
        Route::delete('/notification/delete/{id}', [NotificationController::class, 'delete']);


        Route::put('/document/updatestatus/{id}', [DocumentController::class, 'updateStatus']);

        Route::get('/usercustom', [UserController::class, 'getCustom']);


        Route::get('/document/showbyuser/{userId}', [DocumentController::class, 'getByUser']);
    });


    Route::middleware(['role:agent|custom'])->group(function () {
        Route::get('/appoitment', [AppoitmentController::class, 'index']);
        Route::get('/appoitment/showbyvisarequest/{id}', [AppoitmentController::class, 'showByVisaRequest']);
        Route::get('/appoitment/show/{id}', [AppoitmentController::class, 'show']);
        Route::put('/appoitment/update/{id}', [AppoitmentController::class, 'update']);
        Route::put('/appoitment/updatebyuser/{id}', [AppoitmentController::class, 'updateByUser']);

        Route::post('/message/store', [MessageController::class, 'store']);
        Route::get('/message/show/{id}', [MessageController::class, 'show']);
        Route::get('/message/show/{agentId}/{customId}/{visaRequestId}', [MessageController::class, 'showMessages']);
        Route::get('/message/showbyagent/{id}', [MessageController::class, 'showByUser']);
        Route::get('/message/showbycustom/{id}', [MessageController::class, 'showByCustom']);
        Route::get('/message/showbyvisarequest/{customId}/{visaRequestId}', [MessageController::class, 'showByVisaRequest']);
        Route::put('/message/update/{id}', [MessageController::class, 'update']);
        Route::delete('/message/delete/{id}', [MessageController::class, 'destroy']);

        Route::get('/notification/show/{id}', [NotificationController::class, 'show']);


        Route::put('/visarequest/update/{id}', [VisaRequestController::class, 'update']);

        Route::get('/visarequest/showbyuser/{id}', [VisaRequestController::class, 'showByUser']);
        Route::get('/visarequest/showid/{id}', [VisaRequestController::class, 'showId']);
    });
});
