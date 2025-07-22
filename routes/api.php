<?php
use App\Http\Controllers\API\V1\AuthController;
use App\Http\Controllers\API\V1\PredictionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

/*
/================================================================
/ API Routes Handling
/================================================================
*/

Route::prefix('v1')->group(function() {
    Route::prefix('auth')->group(function() {
        Route::post("/setup", [AuthController::class, 'setup']);
        Route::post("/login", [AuthController::class, 'login']);
        Route::post("/logout", [AuthController::class, 'logout']);
        Route::post("/status", [AuthController::class, 'checkApiKey']);
        Route::post("/update", [AuthController::class, 'updateData']);
    });

    Route::prefix('ml')->group(function (){
        Route::post('/predict', [PredictionController::class, "setup"]);
        Route::post('/predict/get', [PredictionController::class, "getPrediction"]);
    });

    Route::prefix('survey')->group(function (){
        Route::post('/set', [AuthController::class, 'setSurveyState']);
        Route::post('/get', [AuthController::class, 'getSurveyState']);
    });
});
