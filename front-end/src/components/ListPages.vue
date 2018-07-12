<template>
    <li class="page">
        <fill-image
            :src="img"
            :link="slug"
        />
        <section class="body">
            <h4>
                <a :href="slug">{{ title | truncate(63) }}</a>
            </h4>
            <p>
                <template
                    v-if="!hasHighlights"
                >
                    {{ content | truncate(224, 0, false) }}
                </template>
                <template
                    v-else
                >
                    <a :href="slug">[...]</a>
                    <span v-html="highlights[id].content.join('...')" />
                </template>
                <a :href="slug">[...]</a>
            </p>
        </section>
        <section
            class="type"
            v-if="tags.length > 0"
        >
            {{ tags | implode(' / ') }}
        </section>
    </li>
</template>

<script>
import { isEmpty } from 'ramda';

import FillImage from './Image';

export default {
    name: 'app-list-pages',
    props: {
        id: {
            type: String,
            required: true
        },
        img: {
            type: String,
            defualt: 'undefined',
            required: false
        },
        title: {
            type: String,
            required: true
        },
        content: {
            type: String,
            required: true
        },
        tags: {
            type: Array,
            default: () => {
                return [];
            },
            required: true
        },
        slug: {
            type: String,
            required: true
        },
        highlights: {
            type: Object,
            default: () => {
                return {};
            }
        }
    },
    computed: {
        hasHighlights() {
            return !isEmpty(this.highlights);
        }
    },
    components: {
        FillImage
    }
};
</script>