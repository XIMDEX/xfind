<template>
    <div
        class="range-slider"
    >
        <aside
            class="display"
        >
            {{ value[0] | date }}
        </aside>
        <v-slider
            v-model="value"
            :data="prepareRanges(options)"
            :interval="3"
            :tooltip="false"
            :real-time="false"
            @drag-end="change"
        />
        <aside
            class="display"
        >
            {{ value[1] | date }}
        </aside>
    </div>
</template>

<script>
import { isNil } from 'ramda';

import vSlider from 'vue-slider-component';

export default {
    name: 'filter-slider',
    props: {
        options: {
            type: Object,
            required: true
        }
    },
    data() {
        return {
            value: this.startRange(this.options)
        };
    },
    methods: {
        prepareRanges(options) {
            let result = [];
            for (const index in options) {
                let text = index;
                result.push(text);
            }

            return result;
        },
        startRange(options) {
            const keys = Object.keys(options);
            let first = keys[0];
            let last = keys[keys.length - 1];

            return [first, last];
        },
        change() {
            this.$emit('change', this.value);
        }
    },
    beforeUpdate() {
        if (isNil(this.value[0]) || isNil(this.value[1])) {
            this.value = this.startRange(this.options);
        }
    },
    components: {
        vSlider
    }
};
</script>
