<template>
    <component
        :is="tag"
        :type="type"
        v-on="$listeners"
        :disabled="isDisabled"
        class="flex items-center rounded-md text-xs font-bold uppercase"
        :class="[btnClass, colorVariants]"
        :variant="variant"
        :variant-type="variantType"
        :size="size"
        :href="to"
        v-bind="$attrs"
    >
        <slot />
    </component>
</template>

<script>
export default {
    name: "FButton",
    props: {
        loading: {
            type: Boolean,
            default: false
        },
        tag: {
            type: String,
            default: "button"
        },
        disabled: {
            type: Boolean,
            default: false
        },
        variant: {
            type: String,
            default: "primary"
        },
        variantType: {
            type: String,
            default: "normal"
        },
        size: {
            type: String,
            default: "normal"
        },
        rounded: {
            type: String,
            default: "medium"
        },
        type: {
            type: String,
            default: ""
        },
        to: {
            type: String
        }
    },
    data() {
        return {
            isLoading: this.loading,
            isDisabled: this.disabled
        };
    },

    methods: {
        startLoading() {
            this.isLoading = true;
            this.isDisabled = true;
        },
        stopLoading() {
            this.isLoading = false;
            this.isDisabled = false;
        }
    },

    computed: {
        btnClass() {
            return {
                "base-spinner": this.isLoading === true,
                "cursor-not-allowed": this.isDisabled === true,
                "rounded-lg": this.rounded === "medium",
                "rounded-full": this.rounded === "large",
                "px-6 py-3": this.size === "normal" && this.variant !== 'link',
                "px-4 py-2": this.size === "small" && this.variant !== 'link'
            };
        },

        colorVariants() {
            switch (this.variant) {
                case "primary":
                    switch (this.variantType) {
                        case "normal":
                            switch (this.isDisabled) {
                                case true:
                                    return "border-blue-300 bg-blue-300 text-white";
                                    break;
                                default:
                                    break;
                            }
                            return "border-blue-600 bg-blue-600 hover:bg-blue-700 hover:border-blue-700 text-white";
                            break;
                        case "outline":
                            return "border-gray-200 text-blue-500 hover:text-blue-700";
                            break;
                        default:
                            break;
                    }
                    break;
                case "danger":
                    switch (this.variantType) {
                        case "normal":
                            switch (this.isDisabled) {
                                case true:
                                    return "border-red-300 bg-red-300 text-white";
                                    break;
                                default:
                                    break;
                            }
                            return "border-red-600 bg-red-600 hover:bg-red-700 hover:border-red-700 text-white";
                            break;
                        case "outline":
                            return "border-gray-200 text-red-500 hover:text-red-600";
                            break;
                        default:
                            break;
                    }
                    break;

                case "warning":
                    switch (this.variantType) {
                        case "normal":
                            return "border-orange-600 bg-orange-600 hover:bg-orange-700 hover:border-orange-700 text-white";
                            break;
                        default:
                            break;
                    }
                    break;

                case "success":
                    switch (this.variantType) {
                        case "normal":
                            switch (this.isDisabled) {
                                case true:
                                    return "border-green-300 bg-green-300 text-white";
                                    break;
                                default:
                                    break;
                            }
                            return "border-green-600 bg-green-600 hover:bg-green-700 hover:border-green-700 text-white";
                            break;
                        case "outline":
                            return "border-2 border-gray-200 text-green-500 hover:text-green-700";
                            break;
                        default:
                            break;
                    }
                    break;

                case "secondary":
                    switch (this.variantType) {
                        case "outline":
                            return "border-gray-300 text-gray-500 hover:text-blue-500";
                            break;
                        default:
                            return 'text-cool-gray-500 bg-blue-300 hover:bg-blue-400';
                            break;
                    }
                    break;

                case 'link':
                    return 'no-underline hover:underline px-1 py-2 text-indigo-600 text-xs'
                break;

                default:
                    break;
            }
        }
    },
    watch: {
        loading(value){
            this.isLoading = value;
            this.isDisabled = value;
        },
        disable(value){
            this.isDisabled = value;
        }
    }
};
</script>

<style scoped>
</style>
