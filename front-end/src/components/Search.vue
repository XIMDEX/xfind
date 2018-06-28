<template>
    <div class="search">
        <div class="input">
            <input
                type="text"
                name="search"
                title="Buscar"
                placeholder="Buscar"
                v-model="search"
                @keydown.enter="submitSearch"
            >
            <i
                class="icon fas fa-search"
            />
        </div>
        <button
            @click="submitSearch"
        >
            Buscar
        </button>
    </div>
</template>

<script>
export default {
    name: 'search',
    props: {},
    data() {
        return {
            lastSearch: '',
            search: ''
        };
    },
    methods: {
        submitSearch() {
            this.lastSearch = this.search;
            this.$emit('submit', {
                current: this.search,
                last: this.lastSearch
            });
            this.search = '';
        }
    },
    components: {}
};
</script>
