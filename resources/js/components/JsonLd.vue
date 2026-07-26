<script setup lang="ts">
import { onMounted, onUnmounted, watch, computed } from 'vue';

const props = defineProps<{
  schema: Record<string, unknown> | Record<string, unknown>[];
}>();

let scriptEl: HTMLScriptElement | null = null;

const jsonString = computed(() => JSON.stringify(props.schema));

const inject = () => {
  remove();
  scriptEl = document.createElement('script');
  scriptEl.type = 'application/ld+json';
  scriptEl.textContent = jsonString.value;
  document.head.appendChild(scriptEl);
};

const remove = () => {
  if (scriptEl?.parentNode) {
    scriptEl.parentNode.removeChild(scriptEl);
  }
  scriptEl = null;
};

onMounted(inject);
onUnmounted(remove);
watch(jsonString, inject);
</script>

<template>
  <slot />
</template>
