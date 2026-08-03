<script setup lang="ts">
import type { HTMLAttributes } from 'vue';

defineOptions({
    // Ini memastikan atribut tambahan (seperti style atau class dari parent)
    // diteruskan ke tag <img>, bukan container pembungkusnya.
    inheritAttrs: false,
});

type Props = {
    // Menerima prop class untuk styling tambahan jika diperlukan
    className?: HTMLAttributes['class'];
};

defineProps<Props>();
</script>

<template>
    <img
        src="/dmario.webp"
        alt="Logo D'Mario"
        :class="className"
        v-bind="$attrs"
        class="h-8 w-auto rounded-full object-cover"
    />
</template>