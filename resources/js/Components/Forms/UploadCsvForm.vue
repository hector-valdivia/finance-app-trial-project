<template>
    <form @submit.prevent="handleSubmit">
        <label class="block text-xs font-extrabold uppercase text-gray-500">
            .CSV File
        </label>
        <div class="relative rounded-md shadow-sm">
            <input type="file" id="file" ref="file" accept=".csv,.txt" @change="handleFileUpload"/>
        </div>

        <div class="flex justify-end gap-4 pt-6 mt-4 border-t border-gray-100">
            <f-button tag="a" @click.prevent="$emit('cancel', true)" variant="secondary">
                CANCEL
            </f-button>

            <f-button type="submit" :loading="loading">
                IMPORT
            </f-button>
        </div>
    </form>
</template>

<script>
import FButton from "~/Components/FButton";
export default {
    name: "UploadCsvForm",
    components: {FButton},
    data: () => ({
        loading: false,
        file: '',
        lines: ''
    }),
    methods: {
        handleFileUpload(){
            this.file = this.$refs.file.files[0];
        },
        processFile(){
            return new Promise((resolve) => {
                let reader = new FileReader();
                reader.readAsText(this.file);
                reader.onload = (event) => {
                    //get the file.
                    let csv = event.target.result;

                    //split and get the rows in an array
                    this.lines = csv.split('\n').length - 1;
                    resolve(this.lines)
                }
            })
        },
        async handleSubmit(){
            if (!this.file){ return false; }

            let formData = new FormData();
            formData.append('csv', this.file);

            await this.processFile();

            this.$emit('submit', {formData, lines: this.lines});
        }
    }
}
</script>

<style scoped>

</style>
