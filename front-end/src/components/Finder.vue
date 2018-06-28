<template>
    <div>
        <search
            v-if="!title"
            :filters="filters"
            @submit="submitSearch"
        />
        <filters
            v-if="true"
            :data="filters"
        />
        <search-params
            :title="title"
            :last-search="lastSearch"
            :total="total"
            @updateParams="updateParams"
        />
    </div>
</template>

<script>
import { isNil } from 'ramda';
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
            title: title
        };
    },
    computed: {
        hasSearch() {
            return this.title === '';
        }
    },
    methods: {
        submitSearch({ current, last }) {
            this.lastSearch = last;
            this.$emit('submit', current);
        },
        updateParams(value) {
            this.submitSearch({
                current: '',
                last: value
            });
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
