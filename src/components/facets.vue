<template>
    <aside class="facets">
        <div class="facet" v-for="(group, index) in data" :key="index">
            <h5>{{group.text}}</h5>
            
        </div>
    </aside>
</template>

<script>
    export default {
      data: {
        group: []
      }
    };
</script>
