<template>
    <div
        class="mb-4 shadow-md bg-white rounded-md"
        @mouseover="hover = true"
        @mouseleave="hover = false"
    >
        <div class="flex gap-4 items-center px-4 py-2">
            <div class="flex-grow">
                <div class="font-bold">
                    {{ data.label }}
                </div>
                <div class="text-xs text-gray-500">
                    {{ data.date | datetime('DD MMM, YYYY [at] hh:mm a') }}
                </div>
            </div>

            <div v-if="hover" class="flex gap-4 px-4">
                <f-button variant="link" @click.prevent="editing=!editing">
                    EDIT
                </f-button>

                <f-button variant="link" @click.prevent="handleDelete" :loading="loading_delete">
                    DELETE
                </f-button>
            </div>

            <div class="text-lg font-bold">
                <span-number plus :amount="data.amount" />
            </div>
        </div>
        <div v-if="editing" class=" px-4 py-4 border-t border-gray-100">
            <EntryForm
                ref="form"
                :data="data"
                @cancel="editing=false"
                @submit="handleSubmit" />
        </div>
    </div>
</template>

<script>
import SpanNumber from "~/Components/SpanNumber";
import EntryForm from "~/Components/Forms/EntryForm";
import FButton from "~/Components/FButton";

export default {
    name: "DashboardEntry",
    components: {FButton, EntryForm, SpanNumber},
    props: {
        data: { type: Object, required: true }
    },
    data: () => ({
        hover: false,
        editing: false,
        loading: false,
        loading_delete: false,
    }),
    methods: {
        handleSubmit(data){
            this.$refs.form.loading = true;
            axios.put(`/entry/${this.data.id}`, data).then(r =>{
                this.$store.commit('UPDATE_BALANCE', - this.data.amount);
                this.$store.commit('UPDATE_BALANCE', data.amount);
                this.$store.commit('UPDATE_ENTRY', data);
            })
            .catch(error => {
                console.error(error)
            }).finally(() => {
                this.$refs.form.loading = false;
            })
        },

        handleDelete(){
            this.loading_delete = true;

            axios.delete(`/entry/${this.data.id}`).then(r =>{
                    this.$store.commit('UPDATE_BALANCE', - this.data.amount);
                    this.$store.commit('DELETE_ENTRY', this.data.id);
                })
                .catch(error => {
                    console.error(error)
                }).finally(() => {
                    this.loading_delete = false;
                })
        }
    }
}
</script>

<style scoped>
    .link {
        @apply .no-underline .text-indigo-600 .text-xs;
    }

    .link:hover {
        @apply underline;
    }
</style>
