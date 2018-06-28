<template>
    <div class="data search">
        <h3
            v-if="lastSearch !== '' || hasTitle"
            :class="{params: hasParams && !hasTitle}"
        >
            <template
                v-if="lastSearch"
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
        title: {
            type: String,
            require: false,
            default: ''
        }
    },
    data() {
        return {
            params: this.lastSearch
        };
    },
    computed: {
        hasParams() {
            return !isEmpty(this.params);
        },
        hasTitle() {
            return !isEmpty(this.title);
        }
    },
    methods: {
        removeParam() {
            this.params = '';
            this.$emit('updateParams', this.params);
        }
    },
    beforeUpdate() {
        this.params = this.lastSearch;
    }
};
</script>
