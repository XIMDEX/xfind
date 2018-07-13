<template>
    <aside
        :class="{filters: true, open: open, min: hasMin}"
    >
        <div
            :class="{facet: true, slider: (index === 'date')}"
            v-for="(options, index) in data"
            :key="index"
        >
            <label>
                {{ `resolutions.${index}` | trans }}
            </label>
            <div
                class="select"
                v-if="index !== 'date'"
            >
                <v-select
                    v-model="selected[index]"
                    @input="addFilter($event, index)"
                    track-by="text"
                    :options="prepareOptions(options)"
                    select-label=""
                    deselect-label=""
                    :searchable="false"
                    :hide-selected="true"
                >
                    <template
                        slot="singleLabel"
                        slot-scope="props"
                    >
                        <span
                            class="option__title"
                        >
                            {{ props.option.text | truncate(45) }}
                        </span>
                        <span
                            class="option__count"
                        >
                            {{ props.option.value }}
                        </span>
                    </template>
                    <template
                        slot="option"
                        slot-scope="props"
                    >
                        <span
                            class="option__title"
                        >
                            {{ props.option.text | truncate(45) }}
                        </span>
                        <span
                            class="option__count"
                        >
                            {{ props.option.value }}
                        </span>
                    </template>
                </v-select>
            </div>
            <filter-slider
                v-else
                :options="options"
                @change="addFilter($event, index)"
            />
        </div>
    </aside>
</template>

<script>
import VSelect from 'vue-multiselect';
import { hasIn, isNil } from 'ramda';

import FilterSlider from './FilterSlider';

const STYLE =
    hasIn('$search', window) &&
    hasIn('style', window.$search) &&
    !isNil(window.$search.style)
        ? window.$search.style
        : 'default';

export default {
    name: 'facets',
    props: {
        data: {
            type: Object,
            required: true
        },
        filters: {
            type: Object,
            required: true
        },
        open: {
            type: Boolean,
            default: false
        }
    },
    data() {
        return {
            selected: {}
        };
    },
    computed: {
        hasMin() {
            return STYLE === 'min';
        }
    },
    methods: {
        customLabel(option) {
            let text = option.text;
            let value = option.value;
            const textLength = text.length;

            if (textLength > 25 - length) {
                text = text.substring(0, 20) + '...';
            }

            return `${text} - ${value}`;
        },
        prepareOptions(options) {
            let result = [];
            for (const index in options) {
                let text = index;
                let value = options[index];

                result.push({
                    text,
                    value
                });
            }
            return result;
        },
        addFilter(evt, index) {
            let filter = {};

            filter[index] = evt;
            if (!isNil(evt) && hasIn('text', evt)) {
                filter[index] = evt.text;
            }

            this.$emit('filters', filter);
        }
    },
    components: {
        VSelect,
        FilterSlider
    },
    beforeUpdate() {
        for (const key in this.selected) {
            if (!hasIn(key, this.filters)) {
                delete this.selected[key];
            }
        }
    }
};
</script>
