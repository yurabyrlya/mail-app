<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use App\Models\ApiKey;
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

        $response = $mailerLite->subscribers->get();

        // If the API key is not valid, return an error response
        if ($response['status_code'] !== 200) {
            return response()->json([
                'success' => false,
                'errors' => [
                    'api_key' => ['The provided API key is not valid.'],
                ],
            ], 422);
        }

        // Save the valid API key in the database
        $apiKey = ApiKey::firstOrNew(['api_key' => $request->input('api_key')]);
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
            'api_key' => (bool)ApiKey::latest()->first(),
        ]);
    }
}

