<template>
    <div
        @click="close"
        class="fixed z-10 inset-0 overflow-y-auto"
        aria-labelledby="modal-title"
        role="dialog"
        aria-modal="true">

        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

            <!-- This element is to trick the browser into centering the modal contents. -->
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <!-- Modal panel, show/hide based on modal state. -->
            <div
                @click.stop
                v-bind:class="issueModalSize"
                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle modal-width">
                <slot name="title">
                    <div class="pt-4 pl-4 pb-2 border border-b border-gray-200">
                        <span class="text-2xl font-bold text-grey">
                            {{ title }}
                        </span>
                    </div>
                </slot>

                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <slot></slot>
                </div>

                <slot name="footer"></slot>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "FModal",
    props: {
        title: {
            type: String,
            default: ""
        },
        size: {
            type: String,
            default: "md"
        }
    },
    data: () => ({
        paddingRight: 0,
        isModalOverflowing: false,
        isBodyOverflowing: false
    }),
    methods: {
        toggleBodyClass(addRemoveClass, className) {
            const el = document.body;

            if (addRemoveClass === "addClass") {
                el.classList.add(className);
            } else {
                el.classList.remove(className);
            }
        },
        close: function() {
            this.$emit("close");
        },

        // ----------------------------------------------------------------------
        // Thanks to:
        // https://github.com/twbs/bootstrap/blob/3b558734382ce58b51e5fc676453bfd53bba9201/js/src/modal.js
        //
        // the following methods are used to handle overflowing modals
        // ----------------------------------------------------------------------
        _adjustDialog() {
            this.isModalOverflowing =
                this.$el.scrollHeight > document.documentElement.clientHeight;

            if (!this.isBodyOverflowing && this.isModalOverflowing) {
                this.$el.style.paddingLeft = `${this._scrollbarWidth}px`;
            }

            if (this.isBodyOverflowing && !this.isModalOverflowing) {
                this.$el.style.paddingRight = `${this._scrollbarWidth}px`;
            }
        },

        _resetAdjustments() {
            this.$el.style.paddingLeft = "";
            this.$el.style.paddingRight = "";
        },

        _checkScrollbar() {
            const rect = document.body.getBoundingClientRect();
            this.isBodyOverflowing = rect.left + rect.right < window.innerWidth;
            this._scrollbarWidth = this._getScrollbarWidth();
        },

        _setScrollbar() {
            if (this.isBodyOverflowing) {
                // Adjust body padding
                const actualPadding = document.body.style.paddingRight;
                //const calculatedPadding = $(document.body).css("padding-right");

                this.paddingRight = actualPadding;

                /*
                $(document.body).css(
                    "padding-right",
                    `${parseFloat(calculatedPadding) + this._scrollbarWidth}px`
                );

                 */
            }
        },

        _resetScrollbar() {
            // Restore body padding
            const padding = this.paddingRight;

            // Reset storing var
            this.paddingRight = 0;

            document.body.style.paddingRight = padding ? padding : 0;
        },

        _getScrollbarWidth() {
            // thx d.walsh
            const scrollDiv = document.createElement("div");
            scrollDiv.className = "modal-scrollbar-measure";
            document.body.appendChild(scrollDiv);
            const scrollbarWidth =
                scrollDiv.getBoundingClientRect().width - scrollDiv.clientWidth;
            document.body.removeChild(scrollDiv);
            return scrollbarWidth;
        }
    },
    computed: {
        issueModalSize() {
            return "lg:max-w-" + this.size;
        }
    },
    watch: {
        isModalOverflowing: function(overflowing) {
            console.log(overflowing ? "modal is overflowing" : "");
        },
        isBodyOverflowing: function(overflowing) {
            console.log(overflowing ? "body is overflowing" : "");
        }
    },
    activated() {
        // console.log('activated');
    },
    created() {
        // console.log('created');
    },
    mounted() {
        document.addEventListener("keydown", e => {
            if (e.keyCode == 27) {
                this.close();
            }
        });

        this._checkScrollbar();

        this._setScrollbar();
        this._adjustDialog();

        this.toggleBodyClass("addClass", "overflow-hidden");
    },
    destroyed() {
        this._resetAdjustments();
        this._resetScrollbar();

        this.toggleBodyClass("removeClass", "overflow-hidden");
    }
}
</script>

<style scoped>
    .text-inherit {
        color: inherit;
    }

    @media (min-width: 640px){
        .modal-width {
            width: 90%;
        }
    }

    /*
        https://github.com/twbs/bootstrap/blob/fb2de04374a068594c84c1ec6aba6b5b3726ed27/scss/_modal.scss#L144
        Measure scrollbar width for padding body during modal show/hide
    */
    .modal-scrollbar-measure {
        position: absolute;
        top: -9999px;
        width: 50px;
        height: 50px;
        overflow: scroll;
    }

    /**
      tailwind.js colors var, see https://tailwindcss.com/docs/colors.

      Manually recreatd here.

      'smoke-darkest': 'rgba(0, 0, 0, 0.9)',
      'smoke-darker': 'rgba(0, 0, 0, 0.75)',
      'smoke-dark': 'rgba(0, 0, 0, 0.6)',
      'smoke': 'rgba(0, 0, 0, 0.5)',
      'smoke-light': 'rgba(0, 0, 0, 0.4)',
      'smoke-lighter': 'rgba(0, 0, 0, 0.25)',
      'smoke-lightest': 'rgba(0, 0, 0, 0.1)',
    **/

    .bg-smoke-darkest {
        background-color: rgba(0, 0, 0, .9);
    }

    .bg-smoke-darker {
        background-color: rgba(0, 0, 0, .75);
    }

    .bg-smoke-dark {
        background-color: rgba(0, 0, 0, .6);
    }

    .bg-smoke {
        background-color: rgba(0, 0, 0, .5);
    }

    .bg-smoke-light {
        background-color: rgba(0, 0, 0, .4);
    }

    .bg-smoke-lighter {
        background-color: rgba(0, 0, 0, .25);
    }

    .bg-smoke-lightest {
        background-color: rgba(0, 0, 0, .1);
    }

    .hover\:bg-smoke-darkest:hover {
        background-color: rgba(0, 0, 0, .9);
    }

    .hover\:bg-smoke-darker:hover {
        background-color: rgba(0, 0, 0, .75);
    }

    .hover\:bg-smoke-dark:hover {
        background-color: rgba(0, 0, 0, .6);
    }

    .hover\:bg-smoke:hover {
        background-color: rgba(0, 0, 0, .5);
    }

    .hover\:bg-smoke-light:hover {
        background-color: rgba(0, 0, 0, .4);
    }

    .hover\:bg-smoke-lighter:hover {
        background-color: rgba(0, 0, 0, .25);
    }

    .hover\:bg-smoke-lightest:hover {
        background-color: rgba(0, 0, 0, .1);
    }
</style>
