<template>

<div class="card shadow-sm">
    <div class="card-body">
            <div v-if="status==='show'">
                <p v-if="errorMsg" class="card-text invalid-feedback">{{ errorMsg }}</p>
                <div class="d-flex flex-row align-items-center mb-3">
                    <h5 class="card-title d-inline m-0 me-auto">{{ header }}</h5>

                    
                    <div class="btn-group ms-2">
                <button type="button" class="btn btn btn-outline-danger">
                    <a @click="deleteCard"><i class="bi bi-trash3 text-dark"></i></a>
                  <span class="visually-hidden">Button</span>
                </button>
                <button type="button" class="btn btn-outline-success">
                    <a @click="status='edit'"><i class="bi bi-pen text-dark"></i></a>
                  <span class="visually-hidden">Button</span>
                </button>
              </div>
                </div>
                <p class="card-text"> {{ content }} </p>
                <p class="card-text">
                    <small class="text-body-secondary"> {{ link }} </small>
                </p>
            </div>
            <div v-else-if="status==='edit'">
                <div class="d-flex flex-row align-items-center">
                    <SelectField :modelValue="type" @update:modelValue="$emit('update:type', $event)" label="Тип" placeholder='текст' :options="{
                        text: 'текст',
                        link: 'ссылка'
                    }" />
                    <a href="#" @click="save" class="mb-3"><i class="bi bi-check-lg"></i></a>
                </div>
                    <InputField :modelValue="header" @update:modelValue="$emit('update:header', $event)" type="text"
                        label="Заголовок" placeholder='Заголовок поля' />
                
                <InputField :modelValue="content" @update:modelValue="$emit('update:content', $event)" type="text"
                    label="Описание" placeholder='Подробное описание'/>

                <div v-if="type === 'link'">
                    <InputField :modelValue="link" @update:modelValue="$emit('update:link', $event)" type="text"
                        label="Ссылка" placeholder='https://.....'/>
                    <InputField :modelValue="link_name" @update:modelValue="$emit('update:link_name', $event)" type="text"
                        label="Название ссылки"/>
                </div>
            </div>
            <div v-else-if="status==='save'">
                <div class="d-flex flex-row align-items-center mb-3 center-top">
                <div class="spinner-border text-secondary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
            </div>
            
        </div>
    </div>
</template>

<script>
import InputField from './InputField.vue'
import SelectField from './SelectField.vue'
export default {
    name: 'FieldComponent',
    components: { InputField, SelectField },
    props: {
        id: {
            type: String
        },
        header: {
            type: String
        },
        content: {
            type: String
        },
        link: {
            type: String
        },
        link_name: {
            type: String
        },
        type: {
            type: String
        },
        projectId: {
            type: String
        }

    },
    data() {
        return {
            status: this.id=='new'?'edit':'show',
            errorMsg: ''
        }
    },
    methods: {
        save() {
            const body = structuredClone(this.$props);
            if(body['id'] === 'new'){
                delete body['id'];
            }
            
            this.status = 'save';
            fetch(`/projects/${this.projectId}/field/${this.id}/edit`, {
                method: 'POST',
                body: JSON.stringify(body),
                headers: { 'Content-Type': 'application/json' }
            })
                .then((response) => { 
                    this.errorMsg = '';
                    return response.json(); })
                .then((data) => {
                    this.$emit('update:id', data.new_id);
                    this.status = 'show';
                }, 
                (error)=>{
                    this.errorMsg = error.message;
                    this.status = 'show';
                }
            );
            
        },
        deleteCard() {  
            const body = structuredClone(this.$props);
            fetch(`/projects/${this.projectId}/field/${this.id}/delete`, {
                method: 'POST',
                body: JSON.stringify(body),
                headers: { 'Content-Type': 'application/json' }
            })
            .then((response) => { 
                    this.errorMsg = '';
                    return response.json(); })
                .then((data) => {
                    //console.log(data.id);
                    this.$parent.deleteCard(data.id);
                });
        }
    },

}

</script>

<style lang="scss" scoped></style>