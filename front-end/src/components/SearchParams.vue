<template>
    <div class="data search">
        <h3
            v-if="(lastSearch !== '' || hasFilters) || hasTitle"
            :class="{params: hasParams || hasFilters && !hasTitle}"
        >
            <template
                v-if="lastSearch || hasFilters"
            >
                Resultados encontrados para:
                <span
                    v-if="hasParams"
                    class="param"
                    :title="params"
                    @click="removeParam"
                >
                    <i class="fas fa-times" />
                    {{ params | truncate(10) }}
                </span>
                <span
                    v-for="(value, index) in lastFilters"
                    class="param"
                    :title="value"
                    @click="removeFilter(index)"
                    :key="index"
                >
                    <i class="fas fa-times" />
                    <template
                        v-if="index === 'date'"
                    >
                        {{ value | date | implode(' - ') | truncate(20) }}
                    </template>
                    <template
                        v-else
                    >
                        {{ value | implode(' - ') | truncate(20) }}
                    </template>
                </span>
            </template>
            <template
                v-else
            >
                {{ title }}
            </template>
        </h3>
        <aside>
            <b>{{ total }}</b> Resultados
        </aside>
    </div>
</template>

<script>
import { isEmpty } from 'ramda';

export default {
    name: 'search-params',
    props: {
        total: {
            type: Number,
            default: 0
        },
        lastSearch: {
            type: String,
            default: ''
        },
        filters: {
            type: Object,
            default: () => {
                return {};
            }
        },
        title: {
            type: String,
            require: false,
            default: ''
        }
    },
    data() {
        return {
            params: this.lastSearch,
            lastFilters: this.filters
        };
    },
    computed: {
        hasParams() {
            return !isEmpty(this.params);
        },
        hasFilters() {
            return !isEmpty(this.lastFilters);
        },
        hasTitle() {
            return !isEmpty(this.title);
        }
    },
    methods: {
        removeParam() {
            this.params = '';
            this.$emit('updateParams', this.params);
        },
        removeFilter(index) {
            delete this.lastFilters[index];
            this.$emit('updateFilters', {
                filters: this.lastFilters,
                last: this.params
            });
        }
    },
    beforeUpdate() {
        this.params = this.lastSearch;
        this.lastFilters = this.filters;
    }
};
</script>
