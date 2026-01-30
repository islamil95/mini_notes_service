<template>
  <form
    class="bg-white border border-slate-200 rounded-lg p-4 shadow-sm"
    @submit.prevent="submit"
  >
    <h2 class="text-lg font-medium text-slate-800 mb-3">
      {{ note ? 'Редактировать заметку' : 'Новая заметка' }}
    </h2>
    <div class="space-y-3">
      <div>
        <label for="title" class="block text-sm font-medium text-slate-700">Заголовок</label>
        <input
          id="title"
          v-model="form.title"
          type="text"
          maxlength="255"
          class="mt-1 block w-full rounded border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
          placeholder="Заголовок"
        />
        <p v-if="errors.title" class="mt-1 text-sm text-red-600">{{ errors.title }}</p>
      </div>
      <div>
        <label for="content" class="block text-sm font-medium text-slate-700">Текст</label>
        <textarea
          id="content"
          v-model="form.content"
          rows="4"
          class="mt-1 block w-full rounded border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
          placeholder="Содержимое"
        />
        <p v-if="errors.content" class="mt-1 text-sm text-red-600">{{ errors.content }}</p>
      </div>
    </div>
    <div class="mt-4 flex gap-2">
      <button
        type="submit"
        class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded hover:bg-indigo-700"
      >
        {{ note ? 'Сохранить' : 'Создать' }}
      </button>
      <button
        v-if="note"
        type="button"
        class="px-4 py-2 bg-slate-100 text-slate-700 text-sm font-medium rounded hover:bg-slate-200"
        @click="$emit('cancel')"
      >
        Отмена
      </button>
    </div>
  </form>
</template>

<script setup>
import { ref, watch } from 'vue';
import { notesApi } from '../api/notes';

const props = defineProps({
  note: {
    type: Object,
    default: null,
  },
});

const emit = defineEmits(['saved', 'cancel']);

const form = ref({
  title: '',
  content: '',
});
const errors = ref({});
const submitting = ref(false);

watch(
  () => props.note,
  (n) => {
    form.value = {
      title: n?.title ?? '',
      content: n?.content ?? '',
    };
    errors.value = {};
  },
  { immediate: true }
);

async function submit() {
  errors.value = {};
  if (!form.value.title?.trim()) errors.value.title = 'Заголовок обязателен';
  if (!form.value.content?.trim()) errors.value.content = 'Текст обязателен';
  if (Object.keys(errors.value).length) return;

  submitting.value = true;
  try {
    if (props.note) {
      await notesApi.update(props.note.id, form.value);
    } else {
      await notesApi.create(form.value);
    }
    form.value = { title: '', content: '' };
    emit('saved');
  } catch (e) {
    const data = e.response?.data?.errors ?? {};
    errors.value = Object.fromEntries(
      Object.entries(data).map(([k, v]) => [k, Array.isArray(v) ? v[0] : v])
    );
  } finally {
    submitting.value = false;
  }
}
</script>
