<script setup>
import { ref, reactive } from "vue";
import axios from "axios";

const apiKey = ref('');
const saveError = reactive({
    error: false,
    message: ''
});

const successResponse = reactive({
    success: false,
    message: '',
});
/**
 * Check if API Key added
 * @returns {Promise<void>}
 */

const saveApiKey = async () => {
    await axios.post('/api-key/validate', {
        'api_key': apiKey.value
    }).then(response => {

        successResponse.message = response.data.message;
        successResponse.success = response.data.success;
        saveError.error = false;
        window.location.reload();

    }).catch(error => {
        saveError.error = true;
        successResponse.success = false;

        if (error.response.data.errors?.api_key){
            saveError.message = error.response.data.errors.api_key[0];
        }
    })
}

</script>

<template>
    <div class="mt-5">
        <div
            class="alert alert-danger d-flex align-items-center"
            role="alert"
            v-if="saveError.error"
        >
            <div>{{saveError.message}}</div>
        </div>
        <div
            class="alert alert-success d-flex align-items-center"
            role="alert"
            v-if="successResponse.success"
        >
            <div>{{successResponse.message}}</div>
        </div>

        <form class="row g-3">
            <div class="col-8">
                <label
                    for="apiKey"
                    class="visually-hidden"
                ></label>
                <input
                    required
                    type="text"
                    class="form-control"
                    id="apiKey"
                    placeholder="Insert API Key here"
                    v-model="apiKey"
                >
            </div>
            <div class="col-4">
                <button
                    type="button"
                    class="btn btn-primary mb-3"
                    @click="saveApiKey"
                >Load key</button>
            </div>
        </form>
    </div>
</template>
