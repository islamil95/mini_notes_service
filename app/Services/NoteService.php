<?php

namespace App\Services;

use App\Models\Note;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class NoteService
{
    public function getAll(): Collection
    {
        return Note::orderByDesc('updated_at')->get();
    }

    public function getById(int $id): ?Note
    {
        return Note::find($id);
    }

    public function create(array $data): Note
    {
        return Note::create($data);
    }

    public function update(Note $note, array $data): Note
    {
        $note->update($data);
        return $note->fresh();
    }

    public function delete(Note $note): bool
    {
        return $note->delete();
    }
}
