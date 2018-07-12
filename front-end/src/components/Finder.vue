<template>
    <div>
        <search
            v-if="!title"
            :filters="params"
            @submit="submitSearch"
            @advanced="toogleAdvanced"
        />
        <filters
            v-if="true"
            :data="filters"
            :filters="params"
            :open="advanced"
            @filters="setFilters"
        />
        <search-params
            :title="title"
            :last-search="lastSearch"
            :filters="searchParams"
            :total="total"
            @updateParams="updateParams"
            @updateFilters="updateFilters"
        />
    </div>
</template>

<script>
import { isNil, merge } from 'ramda';

import Search from './Search';
import SearchParams from './SearchParams';
import Filters from './Filters';

const title =
    !isNil(window.$search.static) && window.$search.static
        ? window.$search.static
        : '';

export default {
    name: 'finder',
    props: {
        total: {
            type: Number,
            default: 0
        },
        search: {
            type: Array,
            default: () => []
        },
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
            title: title,
            advanced: false,
            params: {},
            searchParams: {}
        };
    },
    computed: {
        hasSearch() {
            return this.title === '';
        }
    },
    methods: {
        submitSearch({ current, last, filters }) {
            this.params = filters;
            this.searchParams = filters;
            this.lastSearch = last;
            this.$emit('submit', { current, filters });
        },
        updateParams(value) {
            this.submitSearch({
                current: '',
                last: value,
                filters: this.searchParams
            });
        },
        updateFilters({ filters, last }) {
            filters = isNil(filters) ? {} : filters;
            this.searchParams = filters;
            this.submitSearch({
                current: '',
                last,
                filters
            });
        },
        toogleAdvanced() {
            this.advanced = !this.advanced;
        },
        setFilters(evt) {
            let params = this.params;
            params = merge(params, evt);
            for (const key in params) {
                const value = params[key];
                if (isNil(value)) {
                    delete params[key];
                }
            }
            this.params = params;
        }
    },
    components: {
        Search,
        SearchParams,
        Filters
    },
    mounted() {
        const urlWindow = new URL(window.location.href);
        this.lastSearch =
            title === '' ? urlWindow.searchParams.get('search') : '';
    }
};
</script>
