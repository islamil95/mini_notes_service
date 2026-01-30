<template>
  <ul class="space-y-3 mt-6">
    <NoteItem
      v-for="note in notes"
      :key="note.id"
      :note="note"
      @edit="$emit('edit', note)"
      @delete="$emit('delete', note)"
    />
  </ul>
</template>

<script setup>
import NoteItem from './NoteItem.vue';

defineProps({
  notes: {
    type: Array,
    default: () => [],
  },
});

defineEmits(['edit', 'delete']);
</script>
