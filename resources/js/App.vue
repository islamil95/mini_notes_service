<template>
  <div class="min-h-screen bg-slate-50 py-8 px-4">
    <div class="max-w-3xl mx-auto">
      <h1 class="text-2xl font-semibold text-slate-800 mb-6">Заметки</h1>
      <NoteForm
        v-if="editingNote"
        :note="editingNote"
        @saved="onSaved"
        @cancel="editingNote = null"
      />
      <NoteForm
        v-else
        @saved="onSaved"
      />
      <p v-if="loading" class="text-slate-500 py-4">Загрузка...</p>
      <p v-else-if="error" class="text-red-600 py-4">{{ error }}</p>
      <NoteList
        v-else
        :notes="notes"
        @edit="onEdit"
        @delete="onDelete"
      />
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import NoteForm from './components/NoteForm.vue';
import NoteList from './components/NoteList.vue';
import { notesApi } from './api/notes';

const notes = ref([]);
const loading = ref(true);
const error = ref('');
const editingNote = ref(null);

async function fetchNotes() {
  loading.value = true;
  error.value = '';
  try {
    const { data } = await notesApi.list();
    notes.value = Array.isArray(data.data) ? data.data : (data.data ? [data.data] : data);
  } catch (e) {
    error.value = e.response?.data?.message || 'Ошибка загрузки заметок';
    notes.value = [];
  } finally {
    loading.value = false;
  }
}

function onSaved() {
  editingNote.value = null;
  fetchNotes();
}

function onEdit(note) {
  editingNote.value = note;
}

async function onDelete(note) {
  if (!confirm('Удалить заметку?')) return;
  try {
    await notesApi.delete(note.id);
    fetchNotes();
  } catch (e) {
    error.value = e.response?.data?.message || 'Ошибка удаления';
  }
}

onMounted(fetchNotes);
</script>
