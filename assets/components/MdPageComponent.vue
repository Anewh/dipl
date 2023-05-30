<template>
    
        
        <div class="mb-3">
        <div class="input-group input-group-sm mb-1">
            <span class="input-group-text"> Заголовок </span>

            <input
                   v-model="header"
                   class="form-control"
                   placeholder="заголовок страницы"
                   >
                   
                </div>
            </div>    


        <!-- <InputField :modelValue="page.header" @update:modelValue="$emit('update:page.header', $event)" type="text" -->
                        <!-- label="Заголовок" placeholder='Заголовок страницы' /> -->

        <!-- <input v-model="header" placeholder="отредактируй меня"> -->
        <!-- <p>Введённое сообщение: {{ header }}</p> -->
        
        
        <MdEditor v-model="page.file" language="en-US" @onSave="onSave" noMermaid preview={false} />
    
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
            
            this.page.header = this.header;
            console.log(this.page.header);
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