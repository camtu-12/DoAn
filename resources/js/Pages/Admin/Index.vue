<template>
    <div>Hello ADMIN {{ user.name }} hyhy</div>
</template>

<script setup>
    defineProps({
  user: Object,
});
</script>
