<template>
    <div>
        <label v-if="label" :for="id" class="block text-xs font-extrabold uppercase text-gray-500">{{ label }}</label>
        <div class="relative rounded-md shadow-sm">
            <div v-if="withIcon" class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <span class="text-gray-500 sm:text-sm">
                    <slot name="icon"></slot>
                </span>
            </div>

            <input
                :type="type"
                :id="id"
                ref="input"
                class="focus:ring-indigo-500 border focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                :class="{
                    'pl-7': withIcon,
                    'pr-12': withAppend
                }"
                :value="value"
                v-bind="$attrs"
                @input="$emit('input', $event.target.value)"
                @keydown="$emit('keydown', $event)"
                @blur="$emit('blur', $event)"
                @keyup="$emit('keyup', $event)"
            />

            <div v-if="withAppend" class="absolute inset-y-0 right-0 flex items-center">
                <slot name="append"></slot>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'FInput',
    inheritAttrs: false,
    props: {
        id: {
            type: String,
            default() {
                return `text-input-${this._uid}`;
            }
        },
        type: {
            type: String,
            default: "text"
        },
        value: String | Number,
        label: String,
        errors: {
            type: Array,
            default: () => []
        },
        withIcon: {
            type: Boolean,
            default: false
        },
        withAppend: {
            type: Boolean,
            default: false
        },
        bordered: {
            type: Boolean,
            default: true
        }
    },

    methods: {
        focus() {
            this.$refs.input.focus();
        },
        select() {
            this.$refs.input.select();
        },
        setSelectionRange(start, end) {
            this.$refs.input.setSelectionRange(start, end);
        }
    },

    computed: {
        classes() {
            return {
                "border-2 focus:border-blue-600 focus:border-blue-600":
                    this.bordered === true,
                "border bg-gray-200 focus:bg-white": this.bordered === false
            };
        }
    }
};
</script>
