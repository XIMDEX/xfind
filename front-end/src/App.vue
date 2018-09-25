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
            <aside>
                <order
                    @changed="changeOrder"
                />
                <view-types
                    :type="listType"
                    @change="changeListType"
                />
            </aside>
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
import { isNil, hasIn, merge, isEmpty, is } from 'ramda';
import { Stretch } from 'vue-loading-spinner';
import Paginate from 'vuejs-paginate';

import AppList from './components/List';
import Finder from './components/Finder';
import ViewTypes from './components/ViewTypes';
import Facets from './components/Facets';
import Order from './components/Order';
import { $typesMap as types, dateComps } from './Core/Mappers/types';
import { parseDate, formatDate } from './Core/Date';

const baseFilters = {};

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
            selectedFacet: null,
            order: 'desc'
        };
    },
    computed: {
        hasSearch() {
            return this.$search.get('type') !== 'Xnews';
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
        isDate($val) {
            return hasIn($val, dateComps);
        },
        getPager(value) {
            this.pager = value;
        },
        changeListType(type) {
            this.listType = type;
        },
        changePage(page) {
            this.pager.page = page;
            let data = {
                current: this.last,
                filters: this.filters
            };

            this.submitSearch(data, false);
        },
        changeOrder(order) {
            this.order = order;
            let data = {
                current: this.last,
                filters: this.filters
            };

            this.submitSearch(data, false);
        },
        _prepareData(data) {
            if (is(String, data) && (!data.startsWith('"') && !data.endsWith('"'))) {
                data = data.split(' ').join(' AND ');
            }

            return data;
        },
        submitSearch(data = '', resetPages = true) {
            const _type = types[this.$search.get('type')];
            const route = this.$search.route(_type);
            const section = this.$search.get('section');

            let last = data;
            this.query = {
                exclude: true,
                content: `(${this._prepareData(data)})`
            };

            if (resetPages) {
                this.pager.page = 0;
            }

            if (is(Object, data)) {
                last = data.current;

                this.query.content = `(${this._prepareData(data.current)})`;
                let filters = data.filters;
                this.filters = filters;

                if (isNil(data.current) || isEmpty(data.current)) {
                    delete this.query.content;
                }
                delete data.current;

                for (const key in data.filters) {
                    let value = data.filters[key];
                    if (this.isDate(key)) {
                        value = parseDate(value)
                        console.log('parse', value);
                        value = formatDate(value, 'isoUtcDateTime');
                        value = `[${value[0]} TO ${value[1]}]`;
                    } else {
                        value = `"${value}"`;
                    }
                    this.query[key] = value;
                }
            }

            if (isNil(data) || isEmpty(data)) {
                this.query = {};
            }

            if (!isNil(section) && !isEmpty(section)) {
                this.query['section'] = section;
            }

            if (!isNil(route) && !isEmpty(route)) {
                this.last = last;
                this._search(route);
            }
        },
        _search(uri, filter) {
            const section = this.$search.get('section');
            let _filters = Object.assign(baseFilters, this.$search.get('filters'))

            const baseQuery = {
                page: this.page + 1,
                limit: this.limit,
                sort_date: this.order
            };

            if (!isNil(section)) {
                baseQuery.section = section;
            }

            const query = Object.assign(baseQuery, _filters)

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
        const content = !this.$search.get('staticSearch')
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
        Facets,
        Order
    }
};
</script>

<style lang="scss">
@import './scss/finder';
</style>
