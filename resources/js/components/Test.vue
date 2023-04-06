<template>
    <div>
        <h1>Hello, {{ name }}!</h1>
    </div>
</template>

<script>
export default {
    name: 'ExampleComponent',
    data() {
        return {
            name: 'Vue 3'
        }
    }
}
</script>
