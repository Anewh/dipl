<template>
    <div class="album py-5 bg-body-tertiary">
        <div class="container">
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">

                <div class="col" v-if="isEditor == true">
                    <div class="card shadow-sm">
                        <a href="#" @click="addCard" class="ms-8">
                            <div class="card-body">
                                <div class="row">
                                    <div class="w-25 mx-auto">
                                        <i class="bi bi-clipboard-plus text-secondary display-1"></i>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>

                <div v-for="field in project.fields" :key="field.uid">
                    <FieldComponent v-model:header="field.header" v-model:content="field.content" v-model:link="field.link"
                                    v-model:link_name="field.link_name" v-model:type="field.type" v-model:id="field.id"
                                    :projectId="project.id" :isEditor="isEditor" />

                </div>
            </div>
        </div>
    </div>
</template>

<script>
import FieldComponent from './FieldComponent.vue';

function randomInt(max) {
    return Math.floor(Math.random() * max);
}

export default {
    name: "ProjectComponent",
    components: { FieldComponent },
    props: {
        projectData: {
            type: Object,
        },
        isEditor: {
            type: String,
        },
    },
    data() {
        return {
            project: structuredClone(this.projectData)
        };
    },
    created() {
        console.log(this.isEditor);
        this.project.fields.forEach(element => {
            element.uid = randomInt(Number.MAX_SAFE_INTEGER);
        });
    },
    components: { FieldComponent },
    methods: {
        addCard() {
            this.project.fields.splice(0, 0, {
                uid: randomInt(Number.MAX_SAFE_INTEGER),
                header: '',
                context: '',
                link: '',
                link_name: '',
                type: '',
                id: 'new'
            })
        },
        deleteCard(cardId) {
            var indexDel = 0;
            for (var i = 0; i < this.project.fields.length; i++) {
                if (this.project.fields[i].id == cardId) {
                    indexDel = i;
                    console.log(indexDel);
                    console.log(this.project.fields[i].header);
                }
            }
            this.project.fields.splice(indexDel, 1);
        }
    },
}
</script>

<style lang="scss" scoped></style>