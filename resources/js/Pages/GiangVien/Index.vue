<template>
    <div>Hello Giảng Viên {{ user.name }}</div>
</template>
<script setup>
    defineProps({
  user: Object,
});
</script>