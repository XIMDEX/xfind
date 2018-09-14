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
            v-if="toggleable"
            @click="advanced"
        >
            Avanzado
        </button>
        <button
            @click="clean"
            class="simple"
        >
            Limpiar
        </button>
    </div>
</template>

<script>
const TOGGLEABLE = false;

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
            search: this.current,
            toggleable: TOGGLEABLE
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
