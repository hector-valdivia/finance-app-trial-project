<template>
<AppLayout>
    <div class="mb-12 py-6 bg-gray-800">
        <div class="container mx-auto flex px-8">
            <div class="my-auto text-white flex flex-grow items-center">
                <h1 class="md:block hidden mr-4 text-2xl font-bold">
                    Your Balance
                </h1>

                <div class="flex gap-2 flex-row">
                    <f-button size="small" @click.prevent="modal_add_balance=!modal_add_balance" :disabled="uploading_csv">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                        Add Entry
                    </f-button>

                    <f-button size="small" @click.prevent="modal_import_csv=!modal_import_csv" :disabled="uploading_csv">
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
        <div v-if="uploading_csv" class="flex gap-4 justify-center mb-8 bg-orange-400 border border-orange-600 text-white text-bold px-4 py-3 rounded" role="alert">
            <i class="w-6 h-6 base-spinner"></i>
            <span class="font-bold">We are importing {{ lines }} balance entries. Sit tight</span>
        </div>

        <div v-if="$store.getters.entriesGroupKeys.length === 0" class="text-center font-bold text-sm mb-4 p-4 shadow-md bg-white rounded-md">
            NO ENTRIES
        </div>

        <DashboardGroupEntries
            v-for="(date, index) in $store.getters.entriesGroupKeys"
            :key="index"
            :date="date"
            :data="$store.getters.entriesGroup[date]" />

        <f-pagination
            :pagination="{ per_page, total_items, last_page }"
            :current-page="current_page"
            @pagechanged="onPageChange"/>
    </div>

    <f-modal v-if="modal_add_balance" size="3xl" title="Add Balance Entry" @close="modal_add_balance = false">
        <EntryForm ref="entryForm" @submit="handleSubmit" @cancel="modal_add_balance=false" />
    </f-modal>

    <f-modal v-if="modal_import_csv" size="3xl" title="Import balance entries" @close="modal_import_csv = false">
        <upload-csv-form ref="uploadCsvForm" @submit="handleUploadFile" />
    </f-modal>
</AppLayout>
</template>

<script>

import {} from 'vuex';
import DashboardGroupEntries from "./DashboardGroupEntries";
import AppLayout from '~/Layouts/Layout';
import SpanNumber from "~/Components/SpanNumber";
import EntryForm from "~/Components/Forms/EntryForm";
import FButton from "~/Components/FButton";
import FPagination from "~/Components/FPagination";
import FModal from "~/Components/FModal";
import UploadCsvForm from "~/Components/Forms/UploadCsvForm";


export default {
    name: "Dashboard",
    components: {UploadCsvForm, FModal, FPagination, FButton, EntryForm, SpanNumber, DashboardGroupEntries, AppLayout },
    props: {
        paginated: { type: Object, required: true },
        total: { type: Number, required: true }
    },
    data: () => ({
        modal_add_balance: false,
        modal_import_csv: false,
        uploading_csv: false,
        lines: 0,

        per_page: 0,
        current_page: 0,
        total_items: 0,
        last_page: 0
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

        handleUploadFile({formData, lines}){
            this.modal_import_csv = false;
            this.uploading_csv = true;

            this.lines = lines;

            let config = { headers: { 'Content-Type': 'multipart/form-data' } };
            axios.post( '/entry_csv', formData, config)
                .then((r) => {
                    this.$store.commit('UPDATE_BALANCE', r.data.total);

                    axios.get('/entry').then((r) => {
                        this.uploading_csv = false;
                        this.handlePagination(r.data);
                    });
                })
                .catch((error) => {
                    console.error(error);
                }).finally(() => {
                    this.uploading_csv = false;
                })
        },

        handlePagination({ data, per_page, current_page, total, last_page }){
            this.per_page     = per_page || 10;
            this.current_page = current_page || 0;
            this.total_items  = total || 0;
            this.last_page    = last_page || 0;
            this.$store.commit('SET_ENTRIES', (data || []));
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
        this.handlePagination(this.paginated);
    },
}
</script>

<style scoped>
    .modal-size{
        max-width: 50rem !important;
    }
</style>
