<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\ApiKey;
use MailerLite\Exceptions\MailerLiteHttpException;
use MailerLite\MailerLite;

class ApiKeyController extends Controller
{
    /**
     * Validate and save an API key.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function validateAndSave(Request $request) : JsonResponse
    {

        // Validate the request data
        $validator = Validator::make($request->all(), [
            'api_key' => 'required|string',
        ]);

        // If validation fails, return a response with error messages
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        // Validate the API key against the MailerLite API
        $mailerLite = new MailerLite(['api_key' => $request->input('api_key')]);
        $response = [];
        try {
            $response = $mailerLite->subscribers->get();
        } catch (MailerLiteHttpException $e){
            $response['status_code'] = $e->getCode();
            $response['message'] = $e->getMessage();
        }


        // If the API key is not valid, return an error response
        if ($response['status_code'] !== 200) {
            return response()->json([
                'success' => false,
                'errors' => [
                    'api_key' => [$response['message']],
                ],
            ], 422);
        }
        $apiKey = ApiKey::first();
        // Save the valid API key in the database
        if ($apiKey){
            $apiKey->api_key = $request->input('api_key');
        } else {
            $apiKey = ApiKey::create(['api_key' => $request->input('api_key')]);
        }
        $apiKey->save();

        return response()->json([
            'success' => true,
            'message' => 'API key saved successfully.',
        ]);
    }

    /**
     * Load the API key from the database.
     *
     * @return JsonResponse
     */
    public function load(): JsonResponse
    {
        return response()->json([
            'api_key' => (bool)ApiKey::first(),
        ]);
    }
}

