<template>
    <div
        class="order-compoent"
    >
        <div
            :class="{
                display: true,
                active: open
            }"
            @click="toggle"
            v-click-outside="hide"
        >
            {{ "resolutions.order" | trans }}
            <i class="fas fa-sort-down" />
        </div>
        <ul
            v-if="open"
            class="list"
        >
            <li
                @click="selectOrder('desc')"
            >
                <i
                    :class="{
                        fas: true,
                        'fa-check': order == 'desc'
                    }"
                />
                {{ "resolutions.new_results" | trans }}
            </li>
            <li
                @click="selectOrder('asc')"
            >
                <i
                    :class="{
                        fas: true,
                        'fa-check': order == 'asc'
                    }"
                />
                {{ "resolutions.old_results" | trans }}
            </li>
        </ul>
    </div>
</template>

<script>
import ClickOutside from 'vue-click-outside';

export default {
    name: 'order',
    data() {
        return {
            open: false,
            order: 'desc'
        };
    },
    methods: {
        toggle() {
            this.open = !this.open;
        },
        hide() {
            this.open = false;
        },
        selectOrder(order) {
            this.order = order;
            this.$emit('changed', order);
        }
    },
    directives: {
        ClickOutside
    }
};
</script>
