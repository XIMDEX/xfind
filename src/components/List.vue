<template>
    <ul class="items-list">
        <li v-if="data.length <= 0">
            No hemos encontrado ningun resultado que coincida con su criterios de busquedas.
        </li>
        <li
            class="hits"
            v-else
            v-for="(value, index) in data"
            :key="index"
        >
            <app-list-pages
                v-if="_isPage(value)"
                :data="value"
            />
            <app-list-docs v-else/>
        </li>
    </ul>
</template>

<script>
import { has } from "ramda";
import AppListPages from "./ListPages";
import AppListDocs from "./ListDocument";

export default {
    name: "app-list",
    props: {
    data: {
        type: Array,
            default: () => {
                return [];
            }
        }
    },
    methods: {
        _isPage(element) {
            const hasType = has("type");
            return hasType(element) && element.type === "page";
        }
    },
    components: {
        AppListPages,
        AppListDocs
    }
};
</script>
