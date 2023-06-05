<template>
    <div class="mb-3">
        <div class="input-group input-group-sm mb-1">
            <span class="input-group-text"> Заголовок </span>

            <div v-if="isEditor == true">
                <input v-model="header" class="form-control" placeholder="заголовок страницы">
            </div>
            <div v-else>
                <input v-model="header" class="form-control" placeholder="заголовок страницы" disabled>
            </div>
        </div>
    </div>


    <!-- <InputField :modelValue="page.header" @update:modelValue="$emit('update:page.header', $event)" type="text" -->
    <!-- label="Заголовок" placeholder='Заголовок страницы' /> -->

    <!-- <input v-model="header" placeholder="отредактируй меня"> -->
    <!-- <p>Введённое сообщение: {{ header }}</p> -->

    <!-- :disabled='true'  -->
    <MdEditor v-model="page.file" language="en-US" @onSave="onSave" noMermaid :disabled="!isEditor" />
</template>
  
<script>
import { ref } from 'vue';
import { MdEditor } from 'md-editor-v3';
import 'md-editor-v3/lib/style.css';
import InputField from './InputField.vue'

export default {
    name: "MdPageComponent",
    components: { MdEditor, InputField },
    props: {
        pageData: {
            type: Object,
        },
        projectIdData: {
            type: Number,
        },
        isEditor: {
            type: Boolean,
        }
    },
    data() {
        return {
            page: structuredClone(this.pageData),
            projectId: structuredClone(this.projectId),
            header: this.pageData.header
        };
    },
    methods: {
        onSave(v, h) {
            //console.log(this.isEditor);
            this.page.header = this.header;
            //console.log(this.page.header);
            //console.log(this.$props.header);
            h.then((html) => {
                const body = structuredClone(this.page);
                fetch(`/page/${this.page.id}/edit`, {
                    // fetch(`/${this.page.project.id}/page/${this.page.id}/edit`, {
                    method: 'POST',
                    body: JSON.stringify(body),
                    headers: { 'Content-Type': 'application/json' }
                })
                    .then((response) => {
                        return response.json();
                    });
                // console.log(html);
            });
        },
    }
}

const text = ref('Hello Editor!');
</script>