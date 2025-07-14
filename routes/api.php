<?php
use App\Http\Controllers\API\V1\AuthController;
use Illuminate\Http\Request;
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
        Route::post("/login", [AuthController::class, 'login']);
        Route::post("/logout", [AuthController::class, 'logout']);
        Route::post("/status", [AuthController::class, 'checkApiKey']);
        Route::post("/update", [AuthController::class, 'updateData']);
    });

    Route::prefix('survey')->group(function (){
        Route::post('/set', [AuthController::class, 'setSurveyState']);
        Route::post('/get', [AuthController::class, 'getSurveyState']);
    });
});
