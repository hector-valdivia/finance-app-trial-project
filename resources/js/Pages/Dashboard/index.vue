<template>
<AppLayout>
    <div class="mb-12 py-6 bg-gray-800">
        <div class="container mx-auto flex px-8">
            <div class="my-auto text-white flex flex-grow items-center">
                <h1 class="md:block hidden mr-4 text-2xl font-bold">
                    Your Balance
                </h1>

                <div class="flex gap-2 flex-row">
                    <f-button size="small" @click.prevent="modal_add_balance=!modal_add_balance">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                        Add Entry
                    </f-button>

                    <f-button size="small" @click.prevent="modal_add_balance=!modal_add_balance">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                        Import CSV
                    </f-button>
                </div>
            </div>

            <div class="my-auto text-right font-bold text-xs uppercase tracking-tight leading-7 text-gray-400">
                Total Balance
                <span class="block text-3xl font-normal text-green-500">
                    <span-number :amount="$store.state.total" />
                </span>
            </div>
        </div>
    </div>

    <div class="container mx-auto mb-10 px-8">
        <DashboardGroupEntries
            v-for="(date, index) in $store.getters.entriesGroupKeys"
            :key="index"
            :date="date"
            :data="$store.getters.entriesGroup[date]" />

        <f-pagination
            :pagination="{ per_page: paginated.per_page, total: paginated.total, total_pages: paginated.last_page }"
            :current-page="paginated.current_page"
            @pagechanged="onPageChange"/>
    </div>

    <f-modal v-if="modal_add_balance" size="3xl" title="Add Balance Entry" @close="modal_add_balance = false">
        <EntryForm ref="entryForm" @submit="handleSubmit" @cancel="modal_add_balance=false" />
    </f-modal>

</AppLayout>
</template>

<script>

import {} from 'vuex';
import DashboardGroupEntries from "./DashboardGroupEntries";
import AppLayout from '~/Layouts/Layout';
import SpanNumber from "~/Components/SpanNumber";
import EntryForm from "~/Components/Forms/EntryForm";
import FButton from "../../Components/FButton";
import FPagination from "../../Components/FPagination";
import FModal from "../../Components/FModal";


export default {
    name: "Dashboard",
    components: {FModal, FPagination, FButton, EntryForm, SpanNumber, DashboardGroupEntries, AppLayout },
    props: {
        paginated: { type: Object, required: true },
        total: { type: Number, required: true }
    },
    data: () => ({
        modal_add_balance: false
    }),
    computed: {
        entriesGroup() {
            let data = [];

            if (this.entries?.data?.length){
                data = _.groupBy(this.entries.data, (entry) => {
                    return $dayjs.utc(entry.date).format('YYYY-MM-DD')
                });
            }

            return data;
        }
    },
    methods: {
        handleSubmit(data){
            this.$refs.entryForm.loading = true;
            axios.post('/entry', data).then(r => {
                this.modal_add_balance = false;
                data.id = r.data.id;
                this.$store.commit('ADD_ENTRY', data);
                this.$store.commit('UPDATE_BALANCE', data.amount);
            }).catch(error => {
                console.error(error)
            });
        },

        onPageChange(page){
            this.$inertia.visit('/dashboard', {
                method: 'get',
                data: { page }
            });
        }
    },
    created() {
        this.$store.commit('SET_BALANCE', this.total);
        this.$store.commit('SET_ENTRIES', this.paginated.data);
    },
}
</script>

<style scoped>
    .modal-size{
        max-width: 50rem !important;
    }
</style>
