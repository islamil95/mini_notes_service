<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreNoteRequest;
use App\Http\Requests\UpdateNoteRequest;
use App\Http\Resources\NoteResource;
use App\Services\NoteService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class NoteController extends Controller
{
    public function __construct(
        private NoteService $noteService
    ) {}

    /** GET /api/notes */
    public function index(): AnonymousResourceCollection
    {
        $notes = $this->noteService->getAll();
        return NoteResource::collection($notes);
    }

    /** GET /api/notes/{id} */
    public function show(int $id): NoteResource|JsonResponse
    {
        $note = $this->noteService->getById($id);
        if (!$note) {
            return response()->json(['message' => 'Note not found'], 404);
        }
        return new NoteResource($note);
    }

    /** POST /api/notes */
    public function store(StoreNoteRequest $request): NoteResource
    {
        $note = $this->noteService->create($request->validated());
        return new NoteResource($note);
    }

    /** PUT/PATCH /api/notes/{id} */
    public function update(UpdateNoteRequest $request, int $id): NoteResource|JsonResponse
    {
        $note = $this->noteService->getById($id);
        if (!$note) {
            return response()->json(['message' => 'Note not found'], 404);
        }
        $note = $this->noteService->update($note, $request->validated());
        return new NoteResource($note);
    }

    /** DELETE /api/notes/{id} */
    public function destroy(int $id): JsonResponse
    {
        $note = $this->noteService->getById($id);
        if (!$note) {
            return response()->json(['message' => 'Note not found'], 404);
        }
        $this->noteService->delete($note);
        return response()->json(null, 204);
    }
}
