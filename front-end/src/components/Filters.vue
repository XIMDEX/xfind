<template>
    <aside
        :class="{filters: true, open: open, min: hasMin}"
    >
        <div
            :class="{facet: true, slider: isDate(index)}"
            v-for="(options, index) in data"
            :key="index"
        >
            <label>
                {{ `resolutions.${index}` | trans }}
            </label>
            <div
                class="select"
                v-if="!isDate(index)"
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
                :options="prepareOptions(options, index, false)"
                @change="addFilter($event, index)"
            />
        </div>
    </aside>
</template>

<script>
import VSelect from 'vue-multiselect';
import { hasIn, isNil, is } from 'ramda';

import FilterSlider from './FilterSlider';
import { dateComps } from '../Core/Mappers/types';
import { formatDate } from '../Core/Date';

const STYLE = 'min';

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
        },
    },
    methods: {
        format($value, type, format) {
            let method = null;
            if (format !== '' && type !== '') {
                type = type.charAt(0).toUpperCase() + type.slice(1);
                method = `format${type}`;
            }

            if (is(Function, this[method])) {
                $value = this[method]($value, format);
            }
            return $value;
        },
        formatDate($value, $format) {
            return formatDate($value, $format);
        },
        isDate($val) {
            return hasIn($val, dateComps);
        },
        _format($val) {
            if (this.isDate($val)) {
                return dateComps[$val];
            }
            return '';
        },
        customLabel(option) {
            let text = option.text;
            let value = option.value;
            const textLength = text.length;

            if (textLength > 25 - length) {
                text = text.substring(0, 20) + '...';
            }

            return `${text} - ${value}`;
        },
        prepareOptions(options, type = '', isObj = true) {
            let result = [];
            for (const index in options) {
                let text = this.format(index, this.isDate(type) ? 'date' : '', this._format(type));
                let value = options[index];

                if (isObj) {
                    result.push({
                        text,
                        value
                    });
                    continue;
                }
                result.push(text);
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
