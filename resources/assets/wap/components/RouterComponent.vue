<template>
    <router-view v-slot="{ Component, route }">
        <keep-alive :max="10" :include="include">
            <component :is="Component" :key="route.name" />
        </keep-alive>
    </router-view>
</template>

<script setup lang="ts">
    const include = []
</script>

<style></style>
