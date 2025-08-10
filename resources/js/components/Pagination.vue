<template>
  <div>
    <ul>
      <li v-for="post in laravelData.data" :key="post.id">{{ post.title }}</li>
    </ul>

    <TailwindPagination
        :data="laravelData"
        @pagination-change-page="getResults"
    />
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { TailwindPagination } from 'laravel-vue-pagination';

const laravelData = ref({});

const getResults = async (page = 1) => {
    const response = await fetch(`https://example.com/results?page=${page}`);
    laravelData.value = await response.json();
}

getResults();
</script>
