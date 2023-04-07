<?php

namespace App\Http\Controllers;

use App\Models\ApiKey;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use MailerLite\Exceptions\MailerLiteHttpException;
use MailerLite\MailerLite;
use Yajra\DataTables\DataTables;

class SubscriberController extends Controller
{
    private MailerLite $api;

    public function __construct()
    {
        $apiKey = ApiKey::latest()->firstOrFail();
        $this->api = new MailerLite([
                'api_key' => $apiKey->api_key,
            ]
        );
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(Request $request) : JsonResponse
    {
        try {
            $query = [
                'limit' => 5,
            ];
            if ($request->cursor){
                $query['cursor'] = $request->cursor;
            }
            $response = $this->api->subscribers->get($query);
            $newArrayData = [];

            foreach ($response['body']['data'] as $data){
                $newArray = [];
                $newArray['id'] = $data['id'];
                $newArray['email'] = $data['email'];

                $dateTime = Carbon::parse($data['subscribed_at']);
                $newArray['subscribe_date'] = $dateTime->format('d/m/y');
                $newArray['subscribe_time'] = $dateTime->format('H:i:s');

                $newArray['name'] = $data['fields']['name'];
                $newArray['country'] = $data['fields']['country'];
                $newArrayData[] = $newArray;

            }
            $response['body']['data'] = $newArrayData;

        } catch (MailerLiteHttpException $e) {
            return new JsonResponse(['error' => $e->getMessage()], $e->getCode());
        }

        return new JsonResponse(DataTables::of($response['body'])->make(true), $response['status_code']);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'name' => 'required|string|max:255',
            'country' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => implode("\n", $validator->errors()->all()),
            ], 422);
        }
        $data = [
            'email' => $request->input('email'),
            'fields' => [
                'name' => $request->input('name'),
                'country' => $request->input('country')
            ]
        ];
        try {
            $response = $this->api->subscribers->create($data);
        } catch (\Exception  $e) {
            return new JsonResponse(['error' => $e->getMessage()], 422);
        }
        return new JsonResponse($response, $response['status_code']);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return JsonResponse
     */
    public function update(Request $request, $id)
    {
        // Validate the id parameter
        if (!ctype_digit($id)) {
            return new JsonResponse(['error' => 'Invalid id parameter.'], 400);
        }
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'country' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => implode("\n", $validator->errors()->all()),
            ], 422);
        }
        $data = [
            'fields' => [
                'name' => $request->input('name'),
                'country' => $request->input('country')
           ]
       ];
        try {
            $response = $this->api->subscribers->update($id, $data);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 422);
        }
        return new JsonResponse($response, $response['status_code']);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function destroy(Request $request, $id)
    {
        // Validate the id parameter
        if (!ctype_digit($id)) {
            return new JsonResponse(['error' => 'Invalid id parameter.'], 400);
        }
        try {
            $response = $this->api->subscribers->delete($id);
        } catch (MailerLiteHttpException $e) {
            return new JsonResponse(['error' => $e->getMessage()], $e->getCode());
        }
        return new JsonResponse($response, $response['status_code']);
    }
}
