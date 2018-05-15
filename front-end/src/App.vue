<template>
    <section
        id="searcher"
        class="container"
    >
        <div
            id="loading"
            v-if="loading"
        >
            <aside>
                <stretch background="#e27000" />
            </aside>
        </div>
        <div class="top">
            <finder
                :total="docFounds"
                @submit="submitSearch"
            />
        </div>
        <div class="content">
            <view-types
                :type="listType"
                @change="changeListType"
            />
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
                :type="listType"
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
import { Stretch } from 'vue-loading-spinner';

import Paginate from 'vuejs-paginate';
import AppList from './components/List';
import Finder from './components/Finder';
import ViewTypes from './components/ViewTypes';

const baseUrl = window.$search.src;

export default {
    name: 'app',
    data() {
        return {
            loading: true,
            docFounds: 0,
            limit: 10,
            pager: {
                next: 1,
                page: 1,
                pages: 1,
                prev: 1,
            },
            facets: [],
            docs: [],
            query: null,
            listType: 'list',
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
        changeListType(type) {
            this.listType = type;
        },
        changePage(page) {
            this.pager.page = page;
            this.submitSearch();
        },
        submitSearch(data = '') {
            this.query = {
                exclude: false,
                content: `*${data}*`,
            };

            if (isNil(data) || isEmpty(data)) {
                this.query = null;
            }

            const type = 'noticias';
            this._search(`${baseUrl}/${type}`);
        },
        _search(uri, filter) {
            const query = {
                page: this.page + 1,
                limit: this.limit,
            };

            this.loading = true;

            this.$xfind
                .get(uri, merge(query, this.query))
                .then(({ docs, pager, facets, numFound }) => {
                    this.docs = docs;
                    this.pager = pager;
                    this.docFounds = numFound;
                    this.facets = facets;
                    this.loading = false;
                })
                .catch(error => {
                    console.error(error);
                    this.docs = [];
                    this.facets = [];
                    this.docFounds = 0;
                    this.pager = {
                        next: 1,
                        page: 1,
                        pages: 1,
                        prev: 1,
                    };
                    this.loading = false;
                });
        },
    },
    mounted() {
        const type = 'noticias';
        this._search(`${baseUrl}/${type}`);
    },
    components: {
        AppList,
        Paginate,
        Finder,
        ViewTypes,
        Stretch,
    },
};
</script>

<style lang="scss">
@import './scss/finder';
</style>
