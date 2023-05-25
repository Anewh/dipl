<template>
    <p>Hello {{name}}</p>
</template>

<script>
    export default{
        name: 'MyComponent',
        props: {
        name: {
            type: String,
            default: 'text'
        }
    }
}
</script>