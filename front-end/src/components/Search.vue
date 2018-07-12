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
        <button
            @click="advanced"
        >
            Avanzado
        </button>
        <button
            @click="clean"
        >
            Limpiar
        </button>
    </div>
</template>

<script>
export default {
    name: 'search',
    props: {
        filters: {
            type: Object,
            default: () => {
                return {};
            }
        }
    },
    data() {
        return {
            lastSearch: '',
            search: this.current
        };
    },
    methods: {
        submitSearch() {
            this.lastSearch = this.search;
            this.$emit('submit', {
                current: this.search,
                last: this.lastSearch,
                filters: this.filters
            });
        },
        advanced() {
            this.$emit('advanced', true);
        },
        clean() {
            this.lastSearch = this.search = '';
            this.$emit('submit', {
                current: this.search,
                last: this.lastSearch,
                filters: {}
            });
        }
    },
    components: {}
};
</script>
