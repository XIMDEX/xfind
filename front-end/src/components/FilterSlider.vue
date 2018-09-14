<template>
    <div
        class="range-slider"
    >
        <aside
            class="display"
        >
            {{ value[0] }}
        </aside>
        <v-slider
            v-model="value"
            :data="options"
            :interval="3"
            :tooltip="'always'"
            :tooltip-dir="tooltipDir"
            :real-time="false"
            :tooltip-style="tooltipStyle"
            @drag-end="change"
        />
        <aside
            class="display"
        >
            {{ value[1] }}
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
            type: Array,
            required: true
        }
    },
    data() {
        return {
            value: this.startRange(this.options),
            tooltipDir: [
                "bottom",
                "bottom"
            ],
            tooltipStyle: [
                {
                    "backgroundColor": "#EC852C",
                    "borderColor": "#EC852C"
                },
                {
                    "backgroundColor": "#EC852C",
                    "borderColor": "#EC852C"
                }
            ]
        };
    },
    methods: {
        startRange(options) {
            const keys = options;
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
