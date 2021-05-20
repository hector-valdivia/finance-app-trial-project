<template>
    <form @submit.prevent="handleSubmit">
        <div class="grid md:grid-cols-3 gap-4">
            <div>
                <f-input label="Label" v-model="entry.label" />
            </div>

            <div>
                <f-input type="datetime-local" label="Date" v-model="entry.date" />
            </div>

            <div>
                <f-input type="number" label="amount" :with-icon="true" v-model.number="entry.amount">
                    <template #icon>$</template>
                </f-input>
            </div>
        </div>


        <div class="flex justify-end gap-4 pt-6 mt-4 border-t border-gray-100">
            <f-button tag="a" @click.prevent="$emit('cancel', true)" variant="secondary">
                CANCEL
            </f-button>

            <f-button type="submit" :loading="loading">
                <span v-if="data && data.id">UPDATE ENTRY</span>
                <span v-else>SAVE ENTRY</span>
            </f-button>
        </div>
    </form>
</template>

<script>
import FInput from "~/Components/FInput";
import FButton from "~/Components/FButton";
export default {
    name: "EntryForm",
    components: {FInput,FButton},
    props: {
        data: Object
    },
    data: () => ({
        entry: {
            label: '',
            date: null,
            amount: 0
        },
        loading: false,
        modal_add_balance: false
    }),
    methods: {
        handleSubmit(){
            if (!this.entry.label || !this.entry.date || !this.entry.amount){
                return false;
            }

            this.$emit("submit", {...this.entry});
        },
    },
    created () {
        if (this.data){
            this.entry = {
                ...this.entry,
                ...this.data,
                date: $dayjs.utc(this.data.date).format('YYYY-MM-DDTHH:mm')
            };
        }
    }
}
</script>

<style scoped>

</style>
