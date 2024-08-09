<?php

namespace App\Http\Controllers;

use App\Http\Requests\DocumentStoreRequest;
use App\Http\Requests\DocumentUpdateRequest;
use App\Http\Resources\DocumentCollection;
use App\Http\Resources\DocumentResource;
use App\Models\Document;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class DocumentController extends BaseController
{
    /**
     * Display a listing of the documents.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $documents = Document::all();
        return $this->jsonResponse(new DocumentCollection($documents));
    }

    /**
     * Store a newly created document in storage.
     *
     * @param DocumentStoreRequest $request
     * @return JsonResponse
     */
    public function store(DocumentStoreRequest $request): JsonResponse
    {
        $userId = $request->input('user_id');
        $user = User::find($userId);

        if (!$user) {
            return $this->notFoundResponse('User');
        }

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filePath = $file->store('documents');

            // Create the document associated with the user
            $document = $user->documents()->create([
                'file_name' => $file->getClientOriginalName(),
                'file_type' => $file->getClientMimeType(),
                'size' => $file->getSize(),
                'file_path' => $filePath,
            ]);

            return $this->jsonResponse(['message' => 'Document uploaded successfully', 'document' => $document], 201);
        }

        return $this->jsonResponse(['message' => 'No file uploaded'], 400);
    }

    /**
     * Display the specified document.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $document = Document::find($id);
        if (!$document) {
            return $this->notFoundResponse('Document');
        }

        return $this->jsonResponse(new DocumentResource($document));
    }

    /**
     * Update the specified document in storage.
     *
     * @param DocumentUpdateRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(DocumentUpdateRequest $request, int $id): JsonResponse
    {
        $document = Document::find($id);
        if (!$document) {
            return $this->notFoundResponse('Document');
        }

        if ($request->hasFile('file')) {
            Storage::delete($document->file_path);

            $file = $request->file('file');
            $filePath = $file->store('documents');

            $document->file_name = $file->getClientOriginalName();
            $document->file_type = $file->getClientMimeType();
            $document->size = $file->getSize();
            $document->file_path = $filePath;
        }

        $document->save();

        return $this->jsonResponse([
            'message' => 'Document updated successfully',
            'document' => new DocumentResource($document)
        ]);
    }

    /**
     * Remove the specified document from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $document = Document::find($id);
        if (!$document) {
            return $this->notFoundResponse('Document');
        }

        Storage::delete($document->file_path);

        $document->delete();

        return $this->jsonResponse(['message' => 'Document deleted successfully']);
    }
}
