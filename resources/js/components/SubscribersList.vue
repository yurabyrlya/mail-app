<script setup>
import DataTable from 'datatables.net-vue3';
import DataTablesCore from 'datatables.net-bs5';
import 'datatables.net-select';

import ApiKeyForm from "./ApiKey.vue";
import axios from "axios";
import { ref, onMounted, reactive} from "vue";

DataTable.use(DataTablesCore);

const columns = [
    { data: 'email' },
    { data: 'name' },
    { data: 'country' },
    { data: 'subscribe_date' },
    { data: 'subscribe_time' },
];

const table = ref();
const data = ref([]);
const cursor = reactive({
    next: null,
    prev: null
})

const apiKeyCreated = ref(false);
const loadApiKeyStatus = async () => {
    apiKeyCreated.value = (await axios.get('api/api-key')).data.api_key;
};

onMounted(async () => {
    await loadApiKeyStatus()
    let responseData = (await axios.get('api/subscribers')).data.original.data
    data.value = responseData[0];
    cursor.next = responseData[2].next_cursor;
    cursor.prev = responseData[2].prev_cursor;

})
const toPage =  async (pageId) => {
    let responseData = (await axios.get('api/subscribers?cursor='+ pageId)).data.original.data
    data.value = responseData[0];
    cursor.next = responseData[2].next_cursor;
    cursor.prev = responseData[2].prev_cursor;
}

const newSubscriber = reactive({
    name: '',
    email: '',
    country: '',
})

const updateSubscriber = reactive({
    id: null,
    name: '',
    country: '',
})

const showCreateForm = ref(false);
const showUpdateForm = ref(false);

const toggleCreateForm = () => {
    showCreateForm.value = !showCreateForm.value;
}
const toggleUpdateForm = () => {
    showUpdateForm.value = !showUpdateForm.value;
    if (showUpdateForm.value){
        table.value.dt.rows({ selected: true }).every(function () {
            let selected = this.data();
            updateSubscriber.id = selected.id;
            updateSubscriber.name = selected.name;
            updateSubscriber.country = selected.country;
        })
    }
}

const saveError = reactive({
    error: false,
    message: ''
});

const successResponse = reactive({
    success: false,
    message: '',
});

function add() {
    axios.post('api/subscribers', {
        name: newSubscriber.name,
        email: newSubscriber.email,
        country: newSubscriber.country,
    }).then(response => {
        successResponse.message = response.data.statusText;
        successResponse.success = true;
        saveError.error = false;

        if (response.status === 200){
            successResponse.message = 'the subscriber with specified email was already in the mailing list';
            return;
        }
       window.location.reload(); // add reactive for future

    }).catch(error => {
        saveError.error = true;
        successResponse.success = false;
        saveError.message = error.response.data.error;
    })
}
function update() {
        axios.put('api/subscribers/'+ updateSubscriber.id, {
            name: updateSubscriber.name,
            country: updateSubscriber.country,
        }).then(response => {
            successResponse.message = response.data.statusText;
            successResponse.success = true;
            saveError.error = false;
            window.location.reload(); // add reactive for future

        }).catch(error => {
            saveError.error = true;
            successResponse.success = false;
            saveError.message = error.response.data.error;
        })

}

function remove() {
    table.value.dt.rows({ selected: true }).every(function () {
        let selected = this.data();
        axios.delete('api/subscribers/'+ selected.id).then(response => {
            if (response.status === 204){
                successResponse.message = 'removed successfully';
                successResponse.success = true;
                saveError.error = false;
                let idx = data.value.indexOf(selected);
                data.value.splice(idx, 1);
            }
        }).catch(error => {
            saveError.error = true;
            successResponse.success = false;
            saveError.message = error.response.data.error;
        })
    })

}

</script>


<template>
    <div class="container">
        <api-key-form/>
        <div class="row" v-if="apiKeyCreated">
            <h1>Manage Mailerlite subscribers</h1>
            <div class="container">
                <div class="mt-2 mb-5">
                    <button
                        @click="toggleCreateForm"
                        class="btn btn-dark"
                    >Add new subscriber</button>
                    <button
                        @click="toggleUpdateForm"
                        class="btn btn-info mx-3"
                    >Update selected subscriber
                    </button>
                    <button
                        @click="remove"
                        class="btn btn-danger mx-3"
                    >Remove selected subscriber
                    </button>
                </div>
            </div>

            <div class="container mt-3 mb-3">
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

                <form id="create" v-if="showCreateForm">
                    <h3> Create new subscriber form</h3>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" v-model="newSubscriber.email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" v-model="newSubscriber.name" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="country" class="form-label">Country</label>
                        <select class="form-select" v-model="newSubscriber.country" id="country" name="country" required>
                            <option value="">-- Select Country --</option>
                            <option value="USA">United States</option>
                            <option value="UK">United Kingdom</option>
                            <option value="Canada">Canada</option>
                            <option value="Australia">Australia</option>
                        </select>
                    </div>
                    <button type="button" @click="add" class="btn btn-primary">Create</button>
                </form>

                <form id="create" v-if="showUpdateForm">
                    <h3> Update subscriber form</h3>
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" v-model="updateSubscriber.name" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="country" class="form-label">Country</label>
                        <select class="form-select" v-model="updateSubscriber.country" id="country" name="country" required>
                            <option value="">-- Select Country --</option>
                            <option value="USA">United States</option>
                            <option value="UK">United Kingdom</option>
                            <option value="Canada">Canada</option>
                            <option value="Australia">Australia</option>
                        </select>
                    </div>
                    <button type="button" @click="update" class="btn btn-primary">Update</button>
                </form>

            </div>
            <DataTable
                :columns="columns"
                :data="data"
                class="table table-hover table-striped"
                :options="{
                    select: true,
                    paging: false,
                }"
                ref="table"
            >
                <thead>
                <tr>
                    <th>Email</th>
                    <th>Name</th>
                    <th>Country</th>
                    <th>Subscribe date</th>
                    <th>Subscribe time</th>
                </tr>
                </thead>
            </DataTable>
            <div class="row">
                <nav aria-label="Table navigation">
                    <ul class="pagination">
                        <li class="page-item"><a class="page-link" :class="{'disabled': !cursor.prev}"  href="#" @click="toPage(cursor.prev)">Previous</a></li>
                        <li class="page-item"><a class="page-link" href="#" :class="{'disabled': !cursor.next}" @click="toPage(cursor.next)">Next</a></li>
                    </ul>
                </nav>
            </div>
        </div>

    </div>
</template>

<style>
@import 'datatables.net-bs5';
</style>
