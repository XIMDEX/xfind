<template>
    <div>
        <search
            @submit="submitSearch"
        />
        <search-params
            :last-search="lastSearch"
            :total="total"
            @updateParams="updateParams"
        />
    </div>
</template>

<script>
import Search from './Search';
import SearchParams from './SearchParams';

export default {
    name: 'finder',
    props: {
        total: {
            type: Number,
            default: 0,
        },
    },
    data() {
        return {
            lastSearch: '',
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
                last: value,
            });
        },
    },
    components: {
        Search,
        SearchParams,
    },
};
</script>
