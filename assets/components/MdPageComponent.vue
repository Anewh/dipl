<template>
    <div class="container">

    <MdEditor v-model="page.file" language="en-US" />
    </div>
</template>
  
<script>
import { ref } from 'vue';
import { MdEditor } from 'md-editor-v3';
import 'md-editor-v3/lib/style.css';

export default {
    name: "MdPageComponent",
    components: { MdEditor },
    props: {
        pageData: {
            type: Object,
        },
    },
    data() {
        return {
            page: structuredClone(this.pageData)
        };
    },
    methods: {
        addCard() {
            
        },
        deleteCard() {
            
        }
    },
}

const text = ref('Hello Editor!');
</script>