<template>
    <span :title="amount">
        <span v-if="amount < 0">-</span>
        <span v-if="plus && amount > 0">+</span>
        ${{ number }}.<small>{{ decimals }}</small>
    </span>
</template>

<script>

export default {
    name: "SpanNumber",
    props: {
        plus: { type: Boolean, default: false },
        amount: { type: Number, required: true}
    },
    computed: {
        number() {
            let number = Math.abs(this.amount).toFixed(0);
            return new Intl.NumberFormat('en-US').format(Math.abs(this.amount));
        },
        decimals(){
            let decimals = (this.amount.toFixed(2) + "").split(".")[1];
            return decimals ?? '00'
        }
    }
}
</script>

<style scoped>

</style>
