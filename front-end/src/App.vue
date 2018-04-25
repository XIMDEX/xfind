<template>
    <section
        id="searcher"
        class="container"
    >
        <div class="top">
            <finder
                :total="docFounds"
                @submit="submitSearch"
            />
        </div>
        <div class="content">
            <paginate
                v-if="page_count > 1"
                :force-page="page"
                :page-range="3"
                :hide-prev-next="true"
                :page-count="page_count"
                :click-handler="changePage"
                container-class="pager"
            >
                <span slot="prevContent"><i class="fas fa-angle-left"/></span>
                <span slot="nextContent"><i class="fas fa-angle-right"/></span>
            </paginate>
            <app-list
                :docs="docs"
                :page="page+1"
                @pager="getPager"
            />
            <paginate
                v-if="page_count > 1"
                :force-page="page"
                :page-range="3"
                :hide-prev-next="true"
                :page-count="pager.pages"
                :click-handler="changePage"
                container-class="pager"
            >
                <span slot="prevContent"><i class="fas fa-angle-left"/></span>
                <span slot="nextContent"><i class="fas fa-angle-right"/></span>
            </paginate>
        </div>
    </section>
</template>

<script>
import { isNil, merge, isEmpty } from 'ramda';

import Paginate from 'vuejs-paginate';
import AppList from './components/List';
import Finder from './components/Finder';

export default {
    name: 'app',
    data() {
        return {
            docFounds: 0,
            limit: 20,
            pager: {
                next: 1,
                page: 1,
                pages: 1,
                prev: 1,
            },
            docs: [],
            query: null,
        };
    },
    computed: {
        page() {
            const { page } = this.pager;
            return page - 1 > 0 ? page - 1 : 0;
        },
        page_count() {
            const { pages } = this.pager;
            return pages;
        },
    },
    methods: {
        getPager(value) {
            this.pager = value;
        },
        changePage(page) {
            this.pager.page = page;
            this.submitSearch();
        },
        submitSearch(data = '') {
            this.query = {
                exclude: false,
                content_flat: `*${data}*`,
                name: `*${data}*`,
            };

            if (isNil(data) || isEmpty(data)) {
                this.query = null;
            }

            const type = 'noticias';
            this._search(`http://agpd.lh/api/v1/${type}`);
        },
        _search(uri, filter) {
            const query = {
                page: this.page + 1,
                limit: this.limit,
            };

            this.$xfind
                .get(uri, merge(query, this.query))
                .then(({ docs, pager, facets, numFound }) => {
                    this.docs = docs;
                    this.pager = pager;
                    this.docFounds = numFound;
                })
                .catch(error => {
                    console.error(error);
                });
        },
    },
    mounted() {
        const type = 'noticias';
        this._search(`http://agpd.lh/api/v1/${type}`);
    },
    components: {
        AppList,
        Paginate,
        Finder,
    },
};
</script>

<style lang="scss">
@import './scss/finder';
</style>
