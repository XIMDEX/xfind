<template>
    <aside class="filters">
        <div
            class="facet"
            v-for="(options, index) in data"
            :key="index"
        >
            <label>
                {{ index }}
            </label>
            <v-select
                v-if="index !== 'date'"
                v-model="filters[index]"
                :options="prepareOptions(options)"
                :settings="{width:'100%'}"
            />
            <v-slider
                v-else
                :value="startRange(options)"
                :data="prepareRanges(options)"
                :interval="3"
                :tooltipDir="['bottom','top']"
            />
        </div>
    </aside>
</template>

<script>
import VSelect from 'v-select2-component';
import dateFormat from 'dateformat';
import vSlider from 'vue-slider-component';

export default {
    name: 'facets',
    props: {
        data: {
            type: Object,
            required: true
        }
    },
    data() {
        return {
            filters: {}
        };
    },
    methods: {
        prepareOptions(options) {
            let result = [];
            for (const index in options) {
                const id = index;
                let text = index;
                try {
                    text = dateFormat(index, 'dd/mm/yyyy');
                } catch (e) {}
                result.push({
                    id,
                    text
                });
            }

            return result;
        },
        prepareRanges(options) {
            let result = [];
            for (const index in options) {
                let text = index;
                try {
                    text = dateFormat(index, 'dd/mm/yyyy');
                } catch (e) {}
                result.push(text);
            }

            return result;
        },
        startRange(options) {
            let result = [];
            const keys = Object.keys(options);
            let first = keys[0];
            let last = keys[keys.length - 1];
            try {
                first = dateFormat(first, 'dd/mm/yyyy');
                last = dateFormat(last, 'dd/mm/yyyy');
            } catch (e) {}

            return [first, last];
        }
    },
    components: {
        VSelect,
        vSlider
    }
};
</script>
