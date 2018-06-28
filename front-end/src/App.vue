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
                :filters="facets"
                :last="last"
                :total="docFounds"
                :has-search="hasSearch"
                @submit="submitSearch"
            />
        </div>
        <div class="content search">
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
                :highlights="highlighting"
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
import { isNil, merge, isEmpty, hasIn, is } from 'ramda';
import { Stretch } from 'vue-loading-spinner';

import Paginate from 'vuejs-paginate';
import AppList from './components/List';
import Finder from './components/Finder';
import ViewTypes from './components/ViewTypes';
import Facets from './components/Facets';

const baseUrl = hasIn('src', window.$search) ? window.$search.src : null;
const type = hasIn('type', window.$search) ? window.$search.type : null;
const section = hasIn('section', window.$search)
    ? window.$search.section
    : null;

const staticSearch =
    !isNil(window.$search.static) && window.$search.static
        ? window.$search.static
        : false;

const types = {
    Xnews: 'noticias',
    Xfind: 'xfind'
};

export default {
    name: 'app',
    data() {
        return {
            last: '',
            loading: true,
            docFounds: 0,
            limit: 10,
            pager: {
                next: 1,
                page: 1,
                pages: 1,
                prev: 1
            },
            facets: {},
            highlighting: {},
            docs: [],
            query: null,
            listType: 'list',
            selectedFacet: null
        };
    },
    computed: {
        hasSearch() {
            return type !== 'Xnews';
        },
        page() {
            const { page } = this.pager;
            return page - 1 > 0 ? page - 1 : 0;
        },
        page_count() {
            const { pages } = this.pager;
            return pages;
        },
        facetsLength() {
            const keys = Object.keys(this.facets);
            return keys.length;
        }
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
            this.submitSearch(this.last);
        },
        submitSearch(data = '') {
            this.last = data;
            this.query = {
                exclude: true,
                content: `*${data}*`
            };

            if (isNil(data) || isEmpty(data)) {
                this.query = {};
            }

            if (!isNil(section) && !isEmpty(section)) {
                this.query['section'] = section;
            }

            if (!isNil(baseUrl) && !isEmpty(baseUrl)) {
                this._search(`${baseUrl}/${types[type]}`);
            }
        },
        _search(uri, filter) {
            const query = {
                page: this.page + 1,
                limit: this.limit
            };

            if (!isNil(section)) {
                query.section = section;
            }

            this.loading = true;

            this.$xfind
                .get(uri, merge(query, this.query))
                .then(({ docs, pager, facets, numFound, highlighting }) => {
                    this.docs = docs;
                    this.pager = pager;
                    this.docFounds = numFound;
                    this.facets = facets;
                    this.loading = false;
                    this.highlighting = is(Array, highlighting)
                        ? {}
                        : highlighting;
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
                        prev: 1
                    };
                    this.loading = false;
                    this.highlighting = {};
                });
        }
    },
    mounted() {
        this.last = '';
        const urlWindow = new URL(window.location.href);
        const content = !staticSearch
            ? urlWindow.searchParams.get('search')
            : '';

        this.submitSearch(content);
    },
    components: {
        AppList,
        Paginate,
        Finder,
        ViewTypes,
        Stretch,
        Facets
    }
};
</script>

<style lang="scss">
@import './scss/finder';
</style>
