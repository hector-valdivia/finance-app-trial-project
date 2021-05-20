<template>
    <div class="flex justify-center w-full">
        <ul v-if="last_page > 1" class="flex pl-0 list-none rounded my-2">
            <li
                class="leading-tight bg-white border border-gray-300 border-r-0 ml-0 rounded-l hover:bg-gray-200"
                :class="{'bg-gray-200': isInFirstPage}">
                <button type="button"
                        class="py-2 px-3"
                        :class="{'cursor-not-allowed': isInFirstPage}"
                        :disabled="isInFirstPage"
                        @click="gotoFirst">
                    &laquo;
                </button>
            </li>

            <li class="leading-tight bg-white border border-gray-300 border-r-0 hover:bg-gray-200" :class="{'bg-gray-200': isInFirstPage}">
                <button type="button"
                        class="py-2 px-3"
                        :class="{'cursor-not-allowed': isInFirstPage}"
                        :disabled="isInFirstPage"
                        @click="gotoPrevious">
                    &lsaquo;
                </button>
            </li>

            <template v-if="showDots('left')">
                <li class="leading-tight bg-white border border-gray-300 border-r-0 hover:bg-gray-400" :class="{'bg-gray-600': isInFirstPage}">
                    <button type="button"
                            class="py-2 px-3"
                            :class="{'cursor-not-allowed': isInFirstPage}"
                            :disabled="isInFirstPage"
                            @click="gotoPageNumber(1)">
                        1
                    </button>
                </li>

                <li class="leading-tight bg-white border border-gray-300 border-r-0 hover:bg-gray-400">
                    <button type="button"
                            class="py-2 px-3"
                            :disabled="true">
                        ...
                    </button>
                </li>
            </template>

            <li v-for="(page, index) in pages"
                class="leading-tight bg-white border border-gray-300 border-r-0 hover:bg-gray-200"
                :key="`pages_${index}`"
                :class="{
                    'bg-blue-600 text-white': page === currentPage
                }">
                <button type="button"
                        class="py-2 px-3"
                        :class="{'cursor-not-allowed': page === currentPage}"
                        :disabled="page === currentPage"
                        @click="gotoPageNumber(page)">
                    {{ page }}
                </button>
            </li>

            <template v-if="showDots('right')">
                <li class="leading-tight bg-white border border-gray-300 border-r-0 hover:bg-gray-400">
                    <button type="button"
                            class="py-2 px-3"
                            :disabled="true">
                        ...
                    </button>
                </li>

                <li class="leading-tight bg-white border border-gray-300 border-r-0 hover:bg-gray-400" :class="{'bg-gray-600': isInLastPage}">
                    <button type="button"
                            class="py-2 px-3"
                            :class="{'cursor-not-allowed': isInLastPage}"
                            :disabled="isInLastPage"
                            @click="gotoPageNumber(last_page)">
                        {{ last_page }}
                    </button>
                </li>
            </template>

            <li class="leading-tight bg-white border border-gray-300 border-r-0 hover:bg-gray-200" :class="{'bg-gray-200': isInLastPage}">
                <button type="button"
                        class="py-2 px-3"
                        :class="{'cursor-not-allowed': isInLastPage}"
                        :disabled="isInLastPage"
                        @click="gotoNext">
                    &rsaquo;
                </button>
            </li>

            <li class="leading-tight bg-white border border-gray-300 border-r-0 rounded-r border-r hover:bg-gray-200" :class="{'bg-gray-200': isInLastPage}">
                <button type="button"
                        class="py-2 px-3"
                        :class="{'cursor-not-allowed': isInLastPage}"
                        :disabled="isInLastPage"
                        @click="gotoLast">
                    &raquo;
                </button>
            </li>
        </ul>
    </div>
</template>

<script>
export default {
    name: "FPagination",
    props: {
        currentPage: { type: Number, required: true, default: 1 },
        pagination: { type: Object, required: true, default: () => ({}) },
        maxVisibleButtons: { type: Number, required: false, default: 5 },
    },

    data: () => ({
        per_page: 10,
        total_items: 0,
        last_page: 0,
    }),

    watch: {
        pagination: {
            handler(pagination) {
                this.per_page = pagination.per_page || 10
                this.total_items = pagination.total_items || 0
                this.last_page = pagination.last_page || 0
            },
            immediate: true,
        },
    },

    computed: {
        isInFirstPage() {
            return this.currentPage === 1
        },

        isInLastPage() {
            return this.currentPage === this.last_page
        },

        pages() {
            const range = []

            for (let i = this.startPage; i <= this.endPage; i += 1) {
                if (i >0){
                    range.push(i)
                }
            }

            return range
        },

        startPage() {
            if (this.currentPage === 1) {
                return 1
            }

            if (this.currentPage === this.last_page) {
                return this.last_page - this.maxVisibleButtons + 1
            }

            return this.currentPage - 1
        },

        endPage() {
            return Math.min(this.startPage + this.maxVisibleButtons - 1, this.last_page)
        },
    },

    methods: {
        showDots(position = "left") {
            const number = position === "left" ? 1 : this.last_page
            const nextNumber = position === "left" ? 2 : this.last_page - 1

            return !this.pages.includes(number) || !this.pages.includes(nextNumber)
        },

        gotoFirst() {
            this.gotoPageNumber(1)
        },

        gotoLast() {
            this.gotoPageNumber(this.last_page)
        },

        gotoPrevious() {
            this.gotoPageNumber(this.currentPage - 1)
        },

        gotoNext() {
            this.gotoPageNumber(this.currentPage + 1)
        },

        gotoPageNumber(pageNumber) {
            this.$emit("pagechanged", pageNumber)
        },
    },
}
</script>

<style scoped>

</style>
