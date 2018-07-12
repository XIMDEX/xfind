<template>
    <section
        class="image"
    >
        <a
            v-if="hasLink && hasImage"
            :href="link"
        >
            <span>
                <i class="fas fa-external-link-alt" />
            </span>
            <img
                v-if="hasImage"
                :src="src"
                :alt="alt"
            >
        </a>
    </section>
</template>

<script>
import { isNil, isEmpty } from 'ramda';

export default {
    name: 'fill-image',
    props: {
        src: {
            type: String,
            default: 'undefined'
        },
        alt: {
            type: String,
            default: ''
        },
        link: {
            type: String,
            defualt: ''
        }
    },
    computed: {
        hasImage() {
            if (
                ['undefined', '', '#', ' '].indexOf(this.src) > -1 ||
                isNil(this.src) ||
                isEmpty(this.src)
            ) {
                return false;
            }
            return true;
        },
        hasLink() {
            if (
                ['undefined', '', '#', ' '].indexOf(this.link) > -1 ||
                isNil(this.link) ||
                isEmpty(this.link)
            ) {
                return false;
            }

            return true;
        }
    }
};
</script>
