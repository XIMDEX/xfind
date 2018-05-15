<template>
    <ul :class="{'items-list': true, 'mosaic': type === 'mosaic'}">
        <li
            v-if="docs.length <= 0"
            style="line-height: 154px; text-align: center;"
        >
            No hemos encontrado ningun resultado que coincida con su criterios de busquedas.
        </li>
        <app-list-pages
            v-else
            v-for="({author, content_flat, date, name, slug, tags, type}, index) in docs"
            :key="index"
            :title="name"
            :content="content_flat"
            :tags="tags"
            :slug="slug"
        />
    </ul>
</template>

<script>
import AppListPages from './ListPages';
import AppListDocs from './ListDocument';

export default {
    name: 'app-list',
    props: {
        type: {
            type: String,
            default: 'list',
        },
        page: {
            type: Number,
            default: 1,
        },
        limit: {
            type: Number,
            default: 1,
        },
        docs: {
            type: Array,
            default: () => {
                return [];
            },
        },
    },
    methods: {
        _isPage(type) {
            return (
                type === 'Xnews' || type === 'noticias' || type === 'noticia'
            );
        },
    },
    components: {
        AppListPages,
        AppListDocs,
    },
};
</script>
