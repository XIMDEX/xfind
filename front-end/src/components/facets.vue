<template>
    <aside id="facets">
        <div
            class="facet"
            v-for="(facet, index) in data"
            :key="index"
        >
            <h5
                @click="toggle(index)"
            >
                {{ index }}
            </h5>
            <ul
                :ref="index"
                :class="{close: facetsClosed[index]}"
            >
                <li
                    v-for="(value, index) in facet"
                    :key="index"
                >
                    <span>{{ index }}</span>
                    <span>{{ value }}</span>
                </li>
            </ul>
        </div>
    </aside>
</template>

<script>
import { forEachObjIndexed, isNil, hasIn } from 'ramda';

export default {
    name: 'facets',
    props: {
        data: {
            type: Object,
            required: true
        },
        group: {
            type: Object,
            required: false,
            default: () => null
        }
    },
    data() {
        return {
            facetsClosed: {}
        };
    },
    methods: {
        toggle(ref) {
            this.$emit('selected', {
                open: ref,
                facet: null
            });
        },
        startFacets() {
            forEachObjIndexed((value, index) => {
                let close = true;
                if (
                    !isNil(this.group) &&
                    hasIn('open', this.group) &&
                    this.open === index
                ) {
                    close = false;
                }
                this.facetsClosed[index] = close;
            }, this.data);
        }
    },
    beforeUpdate() {
        this.startFacets();
    },
    mounted() {
        this.startFacets();
    }
};
</script>
