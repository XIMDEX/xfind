<template>
    <li class="page">
        <section
            class="image"
        >
            <img
                :src="image.src"
                :alt="image.alt"
            >
        </section>
        <section class="body">
            <h4>
                <a href="#slug">{{ title }}</a>
            </h4>
            <p>
                {{ content | truncate(224) }}
                [<a href="#slug">...</a>]
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
import { isNil } from 'ramda';

export default {
    name: 'app-list-pages',
    props: {
        img: {
            type: Object,
            defualt: () => {
                return null;
            },
            required: false,
        },
        title: {
            type: String,
            required: true,
        },
        content: {
            type: String,
            required: true,
        },
        tags: {
            type: Array,
            default: () => {
                return [];
            },
            required: true,
        },
        slug: {
            type: String,
            required: true,
        },
    },
    computed: {
        image() {
            let image = this.img;
            if (isNil(image)) {
                image = {
                    alt: '',
                    src: 'undefined',
                };
            }
            return image;
        },
    },
};
</script>