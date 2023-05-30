<template>
    <div>
        <ul class="list-group list-group-flush">
            <div class="container">
                <div v-for="page in pages" :key="page.uid">
                    <li class="list-group-item">
                        <div class="row align-items-start">
                            <div class="col">
                                <a href="#" class="list-group-item-action">
                                    {{ page.header }}
                                </a>
                            </div>
                            <div class="col-2">
                                <a href="#" class="">
                                    +
                                </a>
                            </div>
                            <div class="col-2">

                                <a href="#" class="">
                                    x
                                </a>
                            </div>
                        </div>
                    </li>
                </div>
            </div>
        </ul>
        <!-- <div class="container">
            <div class="row align-items-start">
                <div class="col">
                    Одна из трех колонок
                </div>
                <div class="col-2">
                    +
                </div>
                <div class="col-2">
                    x
                </div>
            </div>
        </div> -->
    </div>
    <!-- <button type="button" class="btn btn-primary">Primary button</button> -->
    <!-- {{ pages }} -->

</template>

<script>
import ContextMenu from 'add-context-menu';
import ContextMenuItem from 'add-context-menu';

function randomInt(max) {
    return Math.floor(Math.random() * max);
}

export default {
    name: 'PageListComponent',
    components: { ContextMenu, ContextMenuItem },

    props: {
        pagesData: {
            type: Object,
        },
    },
    data() {
        return {
            pages: structuredClone(this.pagesData),
        };
    },
    created() {
        this.pages.forEach(element => {
            element.uid = randomInt(Number.MAX_SAFE_INTEGER);
        });
    },

}
</script>

<style lang="scss" scoped></style>