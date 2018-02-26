<template>
    <div>
        <section
            class="img"
            v-if="image"
        >
            <img
                :src="image.src"
                :alt="image.alt"
                :longdesc="image.longdesc"
            >
        </section>
        <section class="content">
            <h3>
                {{ title }}
            </h3>
            <p>
                {{ body }}
            </p>
        </section>
    </div>
</template>

<script>
import { has } from 'ramda';

export default {
    name: "app-list-pages",
    props: {
        data: {
            type: Object,
            required: true
        }
    },
    computed: {
        image() {
            const hasImg = has('image');
            const hasSrc = has('src');
            const hasAlt = has('alt');
            const hasLongdesc = has('longdesc');

            const image = hasImg(this.data) ? this.data.image : false;

            if (image && hasSrc(image)) {
                return {
                    src: image.src,
                    alt: hasAlt(image) ? image.alt : '',
                    longdesc: hasLongdesc(image) ? image.longdesc : ''
                };
            }

            return false
        },
        title() {
            const hasTitle = has('title');
            if (hasTitle(this.data)) {
                return this.data.title;
            }

            return false;
        },
        body() {
            const hasBody = has('body');
            if (hasBody(this.data)) {
                return this.data.body;
            }
            return false;
        }
    }
};
</script>