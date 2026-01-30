<template>
  <li class="bg-white border border-slate-200 rounded-lg p-4 shadow-sm hover:shadow transition-shadow">
    <div class="flex justify-between items-start gap-2">
      <div class="min-w-0 flex-1">
        <h3 class="font-medium text-slate-800 truncate">{{ note.title }}</h3>
        <p class="text-slate-600 text-sm mt-1 line-clamp-2">{{ note.content }}</p>
      </div>
      <div class="flex gap-2 shrink-0">
        <button
          type="button"
          class="px-3 py-1.5 text-sm text-slate-600 hover:bg-slate-100 rounded"
          @click="$emit('edit', note)"
        >
          Изменить
        </button>
        <button
          type="button"
          class="px-3 py-1.5 text-sm text-red-600 hover:bg-red-50 rounded"
          @click="$emit('delete', note)"
        >
          Удалить
        </button>
      </div>
    </div>
  </li>
</template>

<script setup>
defineProps({
  note: {
    type: Object,
    required: true,
  },
});

defineEmits(['edit', 'delete']);
</script>
