<template>
    <div class="col-sm-6 col-lg-4 mb-4">
        <div class="card-body">
            <div v-if="status==='show'" class="card">
                <p v-if="errorMsg" class="card-text invalid-feedback">{{ errorMsg }}</p>
                <!-- <h5 class="card-title">{{ header }}</h5> -->

                <div class="d-flex flex-row align-items-center mb-3">
                    <h5 class="card-title d-inline m-0 me-auto">{{ header }}</h5>

                    <a href="#" @click="status='edit'"><i class="bi bi-pen"></i></a>
                </div>
                <p class="card-text"> {{ content }} </p>
                <p class="card-text">
                    <small class="text-body-secondary"> {{ link }} </small>
                </p>
            </div>
            <div v-else-if="status==='edit'">
                <SelectField :modelValue="type" @update:modelValue="$emit('update:type', $event)" label="Тип" :options="{
                    text: 'текст',
                    link: 'ссылка'
                }" />
                <div class="d-flex flex-row align-items-center mb-3">
                    <InputField :modelValue="header" @update:modelValue="$emit('update:header', $event)" type="text"
                        label="Заголовок" />
                    <a href="#" @click="save"><i class="bi bi-check-lg"></i></a>
                </div>
                <InputField :modelValue="content" @update:modelValue="$emit('update:content', $event)" type="text"
                    label="Описание" />

                <div v-if="type === 'link'">
                    <InputField :modelValue="link" @update:modelValue="$emit('update:link', $event)" type="text"
                        label="Ссылка" />
                    <InputField :modelValue="link_name" @update:modelValue="$emit('update:link_name', $event)" type="text"
                        label="Название ссылки" />
                </div>
            </div>
            <div v-else-if="status==='save'">
                <div class="spinner-border text-secondary" role="status">
                    <span class="visually-hidden">Loading...</span>
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
            status: 'show',
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
            
        }
    },

}

</script>

<style lang="scss" scoped></style>