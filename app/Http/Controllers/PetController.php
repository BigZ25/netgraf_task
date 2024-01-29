<?php

namespace App\Http\Controllers;

use App\Enum\StatusEnum;
use App\Http\Requests\PetRequest;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;

class PetController extends Controller
{
    private $apiBaseURL = 'https://petstore.swagger.io/v2/pet';

    public function index()
    {
        $status = match (request()->get('status')) {
            StatusEnum::SOLD => StatusEnum::SOLD,
            StatusEnum::PENDING => StatusEnum::PENDING,
            default => StatusEnum::AVAILABLE,
        };

        try {
            $client = new Client();
            $response = $client->request('GET', $this->apiBaseURL . '/findByStatus', [
                'query' => [
                    'status' => $status,
                ],
                'headers' => [
                    'Accept' => 'application/json',
                ],
            ]);

            $pets = json_decode($response->getBody(), true);

            return view('pets.index', [
                'pets' => $pets,
                'status' => $status,
                'statuses' => StatusEnum::getList(),
            ]);
        } catch (\Exception $e) {
            return view('pets.index', [
                'pets' => [],
                'statuses' => StatusEnum::getList(),
                'messages' => [
                    $this->message('Wystąpił błąd podczas pobierania listy zwierząt.')
                ]
            ]);
        }
    }

    public function create()
    {
        return view('pets.form', [
            'statuses' => StatusEnum::getList()
        ]);
    }

    public function edit(int $petId)
    {
        try {
            $client = new Client();
            $response = $client->request('GET', $this->apiBaseURL . '/' . $petId, [
                'headers' => [
                    'Accept' => 'application/json',
                ],
            ]);

            $pet = json_decode($response->getBody(), true);
            return view('pets.form', [
                'pet' => $pet,
                'statuses' => StatusEnum::getList(),
            ]);
        } catch (\Exception $e) {
            return view('pets.form', [
                'pet' => [],
                'statuses' => StatusEnum::getList(),
                'messages' => [
                    $this->message('Wystąpił błąd podczas pobierania danych.')
                ]
            ]);
        }
    }

    public function show(int $petId)
    {
        try {
            $client = new Client();
            $response = $client->request('GET', $this->apiBaseURL . '/' . $petId, [
                'headers' => [
                    'Accept' => 'application/json',
                ],
            ]);

            $pet = json_decode($response->getBody(), true);
            return view('pets.show', [
                'pet' => $pet,
            ]);
        } catch (\Exception $e) {
            return view('pets.show', [
                'pet' => [],
                'messages' => [
                    $this->message('Wystąpił błąd podczas pobierania danych.')
                ]
            ]);
        }
    }

    public function store(Request $request)
    {
        $petRequest = new PetRequest();
        $validator = Validator::make($request->all(), $petRequest->rules(), $petRequest->messages());

        if ($validator->fails()) {
            return view('pets.form', [
                'messages' => array_map(function ($error) {
                    return ['message' => $error, 'type' => 'error'];
                }, Arr::flatten($validator->messages()->messages()))
            ]);
        } else {
            try {
                $client = new Client();
                $response = $client->request('POST', $this->apiBaseURL, [
                    'json' => $validator->validated(),
                    'headers' => [
                        'Accept' => 'application/json',
                    ],
                ]);
                return view('pets.show', [
                    'pet' => json_decode($response->getBody(), true),
                    'messages' => [
                        $this->message('Zwierzak został zapisany.', 'success')
                    ]
                ]);
            } catch (\Exception $e) {
                return view('pets.form', [
                    'messages' => [
                        $this->message('Wystąpił błąd podczas zapisu.')
                    ]
                ]);
            }
        }
    }

    public function update(int $petId, Request $request)
    {
        $petRequest = new PetRequest();
        $validator = Validator::make($request->all(), $petRequest->rules(), $petRequest->messages());

        if ($validator->fails()) {
            return view('pets.form', [
                'messages' => array_map(function ($error) {
                    return ['message' => $error, 'type' => 'error'];
                }, Arr::flatten($validator->messages()->messages()))
            ]);
        } else {
            try {
                $json = $validator->validated();
                $json['id'] = $petId;
                $client = new Client();
                $response = $client->request('PUT', $this->apiBaseURL, [
                    'json' => $json,
                    'headers' => [
                        'Accept' => 'application/json',
                    ],
                ]);
                return view('pets.show', [
                    'pet' => json_decode($response->getBody(), true),
                    'messages' => [
                        $this->message('Zmiany zapisane.', 'success')
                    ]
                ]);
            } catch (\Exception $e) {
                return view('pets.form', [
                    'messages' => [
                        $this->message('Wystąpił błąd podczas zapisu.')
                    ]
                ]);
            }
        }
    }

    public function destroy(int $petId)
    {
        try {
            $client = new Client();
            $response = $client->request('DELETE', $this->apiBaseURL . '/' . $petId, [
                'headers' => [
                    'Accept' => 'application/json',
                ],
            ]);;
            return view('pets.delete', [
                'messages' => [
                    $this->message('Zwierzak został usunięty.', 'success')
                ]
            ]);
        } catch (\Exception $e) {
            return view('pets.delete', [
                'messages' => [
                    $this->message('Wystąpił błąd podczas usuwania.')
                ]
            ]);
        }
    }

    private function message($message, $type = 'error')
    {
        return [
            'message' => $message,
            'type' => $type
        ];
    }
}

