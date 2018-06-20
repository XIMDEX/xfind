<template>
    <div>
        <search
            v-if="!title"
            @submit="submitSearch"
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

const title = !isNil(window.$search.static) ? window.$search.static : '';

export default {
    name: 'finder',
    props: {
        total: {
            type: Number,
            default: 0
        },
        last: {
            type: String,
            default: ''
        }
    },
    data() {
        return {
            lastSearch: this.last,
            title: title ? title : ''
        };
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
        SearchParams
    }
};
</script>
