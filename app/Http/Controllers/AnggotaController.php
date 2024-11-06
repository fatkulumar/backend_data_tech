<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Traits\EntityValidator;
use App\Traits\ResultService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AnggotaController extends Controller
{
    use ResultService;
    use EntityValidator;
    /**
     * Get data.
     */
    public function index(Request $request, $searchQuery = null)
    {
        try {
            // get data anggota di urutkan berdasarkan nama, umur, status
            $data = Anggota::when($searchQuery, function ($query) use ($searchQuery) {
                $query->where('status', 'like', '%' . $searchQuery . '%');
            })
            ->orderBy('nama')
            ->orderBy('umur')
            ->orderBy('status')
            ->get();

            $this->setResult($data)
                ->setStatus(true)
                ->setMessage('Is Data')
                ->setCode(JsonResponse::HTTP_OK);

        } catch (Exception $exception) {
            $this->exceptionResponse($exception);
            $errors['message'] = $exception->getMessage();
            $errors['file'] = $exception->getFile();
            $errors['line'] = $exception->getLine();
            Log::channel('daily')->info('function all in AnggotaController', $errors);
        }

        return $this->toJson();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Tambah atau edit data.
     */
    public function store(Request $request)
    {
        try {

            $validasiData = $this->validator($request);
            // apabila form yang di kirim tidak sesuai makan akan mengemblikan nilai error yang tidak sesuai
            if ($this->code != 200) {
                $this->setResult($validasiData)
                    ->setStatus(true)
                    ->setMessage('Failed')
                    ->setCode(JsonResponse::HTTP_CREATED);
                return $this->toJson();
            }

            $result = Anggota::updateOrCreate(
                [
                    'id' => $request->post('id'),
                ],
                [
                    'nama' => $request->post('nama'),
                    'umur' => $request->post('umur'),
                    'email' => $request->post('email'),
                    'status' => $request->post('status'),
                ]
            );

            $this->setResult($result)
                ->setStatus(true)
                ->setMessage('Saved')
                ->setCode(JsonResponse::HTTP_OK);

        } catch (Exception $exception) {
            $this->exceptionResponse($exception);
            $errors['message'] = $exception->getMessage();
            $errors['file'] = $exception->getFile();
            $errors['line'] = $exception->getLine();
            Log::channel('daily')->info('function all in AnggotaController', $errors);
        }

        return $this->toJson();
    }

     /**
     * Validate tambah/edit data
     */

    private function validator(Request $request)
    {
        try {
            $rules = [
                'nama' => 'required|max:4',
                'email' => 'required|email|max:100',
                'umur' => 'required|integer',
                'status' => 'required|string|max:12',
            ];
            $Validatedata = [
                'nama' => $request->post('nama'),
                'email' => $request->post('email'),
                'umur' => $request->post('umur'),
                'status' => $request->post('status'),
            ];
            $messages = [
                'nama.required' => 'Nama wajib diisi.',
                'nama.max' => 'Nama tidak boleh lebih dari 4 karakter.',
                'email.required' => 'Email wajib diisi.',
                'email.email' => 'Format email tidak valid.',
                'email.max' => 'Email tidak boleh lebih dari 100 karakter.',
                'umur.required' => 'Umur wajib diisi.',
                'umur.integer' => 'Umur harus berupa angka.',
                'status.required' => 'Status wajib diisi.',
                'status.string' => 'Status harus berupa teks.',
                'status.max' => 'Status tidak boleh lebih dari 12 karakter.',
            ];

            $validator = Validator::make($Validatedata, $rules, $messages);
            // kembalikan error form yang tidak sesuai
            if ($validator->fails()) {
                $error = $validator->errors();

                $this->setResult($error)
                ->setStatus(true)
                ->setMessage('Gagal melakukan validasi input data')
                ->setCode(JsonResponse::HTTP_BAD_REQUEST);

                return $this->toJson();
            }
            // apanbila isi form sesuai
            $this->setResult(null)
                ->setStatus(true)
                ->setMessage('Proses Validasi input data berhasil')
                ->setCode(JsonResponse::HTTP_OK);
        } catch (\Exception $exception) {
            $errors['message'] = $exception->getMessage();
            $errors['file'] = $exception->getFile();
            $errors['line'] = $exception->getLine();
            $errors['trace'] = $exception->getTrace();
            Log::channel('daily')->info('function validator in AnggotaController', $errors);
        }
        return $this->toJson();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Hapus data
     */
    public function destroy(string $id)
    {
        try {
            $data = Anggota::findOrFail($id);
            if ($data) {
                $data->delete();
                $this->setResult($data)
                    ->setStatus(true)
                    ->setMessage('Deleted')
                    ->setCode(JsonResponse::HTTP_OK);
            }else{
                $this->setResult(null)
                    ->setStatus(false)
                    ->setMessage('Deleted Failed')
                    ->setCode(JsonResponse::HTTP_OK);
            }
        } catch (Exception $exception) {
            $this->exceptionResponse($exception);
            $errors['message'] = $exception->getMessage();
            $errors['file'] = $exception->getFile();
            $errors['line'] = $exception->getLine();
            Log::channel('daily')->info('function all in AnggotaController', $errors);
        }

        return $this->toJson();
    }
}
