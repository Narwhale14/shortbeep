<script setup lang="ts">
import { ref } from 'vue'

const link = ref('')
const shortlink = ref('')

async function shorten() {
  const result = await fetch('/shorten', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ url: link.value })
  })
  const data = await result.json()
  shortlink.value = `http://localhost:8000/${data.code}`
}
</script>

<template>
  <input type="text" v-model="link" placeholder="Enter your link"/>
  <button @click="shorten">Shorten</button>

  <p v-if="shortlink">
    Short Link: <a :href="shortlink">{{ shortlink }}</a>
  </p>
</template>

<style scoped></style>
