<template>
    <div class="mb-8">
        <div class="flex items-center mb-4">
            <span class="flex-grow text-gray-500 font-bold text-sm uppercase tracking-tight">
                {{ dateCalendar }}
            </span>
            <span class="text-lg text-gray-500 font-bold">
                <span-number plus :amount="total" />
            </span>
        </div>

        <div>
            <div v-if="data.length === 0" class="text-center font-bold text-sm mb-4 p-4 shadow-md bg-white rounded-md">
                NO ENTRIES
            </div>
            <DashboardEntry v-for="entry in data" :key="`entry-${entry.id}`" :data="entry" />
        </div>
    </div>
</template>

<script>
import DashboardEntry from './DashboardEntry';
import SpanNumber from "~/Components/SpanNumber";

export default {
    name: "DashboardItems",
    components: {SpanNumber, DashboardEntry },
    props: {
        date: { type: String, required: true },
        data: { type: Array, required: true }
    },
    data: () => ({
        humanize: ''
    }),
    computed: {
        dateCalendar() {
            let date = $dayjs(this.date);
            let date_text = date.format('ddd, DD MMM');

            if (date.isToday()){
                date_text = 'Today';
            }else if (date.isYesterday()){
                date_text = 'Yesterday';
            }

            return date_text
        },
        total(){
            return this.data.map(d => d.amount).reduce((a,b) => a + b);
        }
    },
}
</script>

<style scoped>

</style>
