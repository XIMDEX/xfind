<template>
    <div id="list-type">
        <button
            v-for="({type: btnType, icon: btnIcon}, index) in buttons"
            :key="index"
            :class="{'active': type === btnType}"
            @click.prevent="changeType(btnType)"
        >
            <i :class="['fas', btnIcon]" />
        </button>
    </div>
</template>

<script>
export default {
    name: 'view-types',
    props: {
        type: {
            type: String,
            required: true,
        },
    },
    data() {
        return {
            buttons: [
                {
                    type: 'list',
                    icon: 'fa-th-list',
                },
                {
                    type: 'mosaic',
                    icon: 'fa-th-large',
                },
            ],
        };
    },
    methods: {
        changeType(type) {
            this.$emit('change', type);
        },
    },
};
</script>
