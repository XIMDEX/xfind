<template>
    <div class="data search">
        <h3 v-if="lastSearch !== ''">
            Resultados encontrados para:
            <span
                v-if="params !== ''"
                class="param"
                :title="params"
                @click="removeParam"
            >
                {{ params | truncate(10) }}
            </span>
        </h3>
        <aside>
            <b>{{ total }}</b> Resultados
        </aside>
    </div>
</template>

<script>
export default {
    name: 'search-params',
    props: {
        total: {
            type: Number,
            default: 0,
        },
        lastSearch: {
            type: String,
            default: '',
        },
    },
    data() {
        return {
            params: this.lastSearch,
        };
    },
    computed: {
        hasParams() {
            return false;
        },
    },
    methods: {
        removeParam() {
            this.params = '';
            this.$emit('updateParams', this.params);
        },
    },
    beforeUpdate() {
        this.params = this.lastSearch;
    },
};
</script>
