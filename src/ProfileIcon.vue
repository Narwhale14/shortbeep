<script setup lang="ts">
const emit = defineEmits<{
  (e: 'open'): void
}>()
</script>

<template>
  <button class="icon" @click="emit('open')">
    A
  </button>
</template>

<style scoped>
.icon {
  position: absolute;
  top: 1rem;
  right: 1rem;
}
</style>